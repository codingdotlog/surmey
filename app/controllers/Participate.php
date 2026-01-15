<?php

namespace App\Controllers;

use App\Helpers\Mail;
use App\Models\Participator;
use App\Models\Personal;
use App\Models\Survey;
use Core\{Controller, Request, Config, Database};
use Core\Attributes\route;
use App\Models\User;

class Participate extends Controller
{
	public function index()
	{
		if (! session_check("surveySlug"))
			redirect("/");

		$slug = session_get("surveySlug");
		$survey = Survey::exists("slug", $slug);

		#session_destroy();
		if (session_check("tempPin"))
			redirect("/participate/pin");

		if (session_check("participator"))
			redirect("/d/$slug");

		$this->render("survey/auth", [
			"title" => Config::get()->title . " - " . $survey->title,
			"survey" => $survey
		]);
	}

	#[route(method: route::xhr_post, uri: "verify-step1")]
	public function verifyPhone()
	{
		if (session_check("tempPin"))
			warning(redirect: "/participate/pin");

		if (! session_check("surveySlug"))
			redirect("/");

		$slug = session_get("surveySlug");
		if (session_check("participator"))
			warning(redirect: "/d/$slug");

		$surveyId = session_get("surveyId");

		$post = Request::post();
		if (! check_csrf($post->csrf))
			warninglang("csrf.error");

		$phone = isset($post->phone) ? trim((string)$post->phone) : "";
		$email = isset($post->email) ? trim((string)$post->email) : "";
		$requestedChannel = isset($post->channel) ? trim((string)$post->channel) : "";

		if ($requestedChannel && !in_array($requestedChannel, ["phone", "email"]))
			warninglang("participate.verify.method.invalid");

		$personalId = 0;
		$channel = "";

		// If a channel is explicitly selected, only accept that path.
		if ($requestedChannel === "email") {
			if ($email === "")
				warninglang("participate.verify.email.required");

			if (! validate_email($email))
				warning(lang("validation.email_error"));

			$personalId = (int) Personal::existsByEmail($email);
			$channel = "email";
		} elseif ($requestedChannel === "phone") {
			if ($phone === "")
				warninglang("participate.verify.phone.required");

			$validate = validate((object)[
				"phone" => $phone,
				"csrf" => $post->csrf
			], [
				"phone" => ["name" => "Telefon", "required" => true, "min" => 10, "phone" => "true"],
				"csrf" => ["name" => "token", "required" => true]
			]);

			if ($validate)
				warning($validate);

			$phone = str_replace(["(", ")", " "], "", $phone);
			$personalId = (int) Personal::exists($phone);
			$channel = "phone";
		} else {
			// Backwards compatibility: if channel isn't provided, infer it from filled input.
			if ($phone === "" && $email === "")
				warninglang("participate.verify.contact.required");

			if ($email !== "") {
				if (! validate_email($email))
					warninglang("validation.email_error");

				$personalId = (int) Personal::existsByEmail($email);
				$channel = "email";
			} else {
				$validate = validate((object)[
					"phone" => $phone,
					"csrf" => $post->csrf
				], [
					"phone" => ["name" => "Telefon", "required" => true, "min" => 10, "phone" => "true"],
					"csrf" => ["name" => "token", "required" => true]
				]);

				if ($validate)
					warning($validate);

				$phone = str_replace(["(", ")", " "], "", $phone);
				$personalId = (int) Personal::exists($phone);
				$channel = "phone";
			}
		}

		if (! $personalId)
			warninglang("participate.personal.not.found");

		if (Participator::checkSurveyIsParticipated($surveyId, $personalId, isDone: true))
			warninglang("participate.already.answered");

		# find a unique usable pin for the participator
		while (Participator::checkToken(($pin = join(randomSequence(6)))));

		$post->pin = $pin;
		$tokenTime = time();

		$last4 = $channel === "phone"
			? substr($phone, max(strlen($phone) - 4, 0), 4)
			: "";

		$maskedEmail = "";
		if ($channel === "email") {
			$parts = explode("@", $email, 2);
			$local = $parts[0] ?? "";
			$domain = $parts[1] ?? "";
			$maskedLocal = strlen($local) <= 2 ? $local : substr($local, 0, 2) . str_repeat("*", max(strlen($local) - 2, 0));
			$maskedEmail = $maskedLocal . "@" . $domain;
		}

		# set temporary pin value to session as tempPin
		session_set("tempPin", (object) [
			"token" => hash("sha256", $pin),
			"time" => $tokenTime,
			"channel" => $channel,
			"phone" => $phone,
			"email" => $email,
			"maskedEmail" => $maskedEmail,
			"phoneLastNum4" => $channel === "phone" ? (substr($last4, 0, 2) . " " . substr($last4, 2, 4)) : ""
		]);

		$answerId = Participator::answerExists($surveyId, $personalId);
		if (! $answerId)
			$result = $this->db->from("answers")->insert([
				"surveyId" => $surveyId,
				"personalId" => $personalId,
				"token" => $pin,
				"tokenTime" => $tokenTime
			]);
		else
			$result = $this->db->from("answers")
				->where("id", "=", $answerId)
				->update([
					"token" => $pin,
					"tokenTime" => $tokenTime,
					"done" => 0
				]);


		$surveyId = session_get("surveyId");
		$survey = Survey::exists("id", $surveyId);

		if ($channel === "phone") {
			$messages[] = ["no" => $phone, "msg" => lang("participate.sms.otp.body", $pin)];
			\SmsHelper::sendOtp($phone, lang("participate.sms.otp.body.ascii", $pin));
		} else {
			$content = lang("participate.email.otp.body", $pin);
			Mail::send(lang("participate.email.otp.subject"), [$email], $content);
		}

		if ($result)
			success(redirect: "/participate/pin");

		getDataError();
	}

	#[route(method: route::xhr_get | route::get)]
	public function pin()
	{
		if (! session_check("tempPin"))
			redirect("/");

		$tempPin = session_get("tempPin");
		if (! $tempPin)
			redirect("/");

		$elapsedTime = time() - $tempPin->time;
		if ($elapsedTime > 120) {
			session_destroy();
			redirect("/");
		}

		$this->render("survey/pin", [
			"title" => lang("participate.verify.title"),
			"pin" => $tempPin,
			"time" => $tempPin->time
		]);
	}

	#[route(method: route::xhr_post, uri: "pin")]
	public function verifyPin()
	{
		if (! session_check("surveySlug"))
			redirect("/");

		$slug = session_get("surveySlug");
		if (session_check("participator"))
			warning(redirect: "/d/$slug");

		$post = Request::post();
		$validate = validate($post, [
			"pin" => ["name" => "pin", "required" => true, "min" => 6, "max" => 6],
			"token" => ["name" => "token", "required" => true, "min" => 64, "max" => 64],
			"csrf" => ["name" => lang("security.code"), "required" => true]
		]);

		if ($validate)
			error($validate);
		elseif (! check_csrf($post->csrf))
			errorlang("csrf.error");

		$tempPin = session_get("tempPin");
		if ($post->token != $tempPin->token)
			error("UNDEFINED_TOKEN");
		elseif (hash("sha256", $post->pin) != $tempPin->token)
			errorlang("pin.error");

		if (! ($participatorInfo = Participator::checkToken($post->pin)))
			warninglang("pin.error");

		session_remove("tempPin");

		#session_regenerate_id(true);

		session_set("participator", $participatorInfo);
		success(redirect: "/d/$slug");
	}

	#[route(method: route::xhr_post, session: "participator", uri: "apply")]
	public function apply()
	{
		if (! session_check("surveySlug"))
			redirect("/");

		$slug = session_get("surveySlug");

		$surveyId = session_get("surveyId");
		if (! session_check("participator"))
			warning("INVALID_SURVEY");

		$survey = Survey::exists("id", $surveyId);
		if (! $survey)
			error("INVALID_SURVEY_DATA");

		$user = session_get("participator");
		if ($survey->verifyPhone) {
			if (Participator::checkSurveyIsParticipated($user->personalId, true))
				warning("Bu anketi daha önce zaten cevapladınız!");
		}

		$post = Request::post();

		$rules = [];
		$validate2 = false;
		$errors = [];
		

		$formData = json_decode($survey->data);
		foreach ($formData as $key => $value) {
			if ($value->type == "description")
				continue;

			// Koşullu soru kontrolü - tüm soru tipleri için
			$index = -1;
			$hasCondition = false;

			foreach ($formData as $dkey => $dvalue) {
				// Eğer bu soru koşula sahipse
				if (!empty($dvalue->conditions)) {
					foreach ($dvalue->conditions as $i => $p) {
						if ($p->value == $value->slug) {
							$hasCondition = true; // Bu soru bir koşula bağlı
							$parentAnswer = $post->{$dvalue->slug} ?? null;

							if ($p->index == $parentAnswer) {
								$index = $i;
								break 2; // iki foreach'ten çık
							}
						}
					}
				}
			}

			// Eğer koşula bağlıysa ve koşul sağlanmamışsa bu soruyu atla
			if ($hasCondition && $index == -1) {
				continue;
			}

			switch ($value->type) {
				case "radio":
					$rules[$value->slug] = [
						"name" => $value->title,
						#"required" => $value->isRequired,
						"min" => 0,
						"max" => count($value->answers) - 1
					];

					if ($value->isRequired)
						$rules[$value->slug]["required"] = $value->isRequired;

					break;

				case "checkbox":

					if ($value->isRequired) {
						$actions = [];

						for ($i = 0; $i < count($value->answers); $i++)
							$actions[$i] = isset($post->{$value->slug . $i});

						$validate2 = ! in_array(true, $actions);

						if ($validate2)
							$errors[] = $value->title . " sorusunu cevaplayınız!";
					}

					break;

				case "textarea":
					$rules[$value->slug] = [
						"name" => $value->title . "<br>",
						"min" => 4,
						"max" => 1000
					];

					if ($value->isRequired)
						$rules[$value->slug]["required"] = $value->isRequired;

					break;
			}
		}

		$validate = validate($post, $rules);

		if (count($errors) != 0)
			warning(join("<br>", $errors));

		if ($validate)
			warning($validate);

		$result = false;
		if ($survey->verifyPhone) {
			$result = $this->db->from("answers")
				->where("surveyId", "=", $surveyId)
				->where("personalId", "=", $user->personalId)
				->update([
					"data" => data_json($post),
					"done" => 1
				]);
		} else {
			$result = $this->db->from("answers")
				->insert([
					"surveyId" => $surveyId,
					"personalId" => 0,
					"data" => data_json($post),
					"done" => 1
				]);
		}

		if ($result) {
			session_destroy();
			successlang("survey.successfully.answered", redirect: "/successfully/$slug:2500");
		}

		getDataError();
	}

	#[route(method: route::xhr_get, uri: "data")]
	public function getSurveyData()
	{
		if (session_check("survey"))
			$survey = session_get("survey");
		else {
			if (! session_check("participator"))
				die("PARTICIPATE_ERRROR");

			if (! session_check("surveyId"))
				die("data-not-found");

			$surveyId = session_get("surveyId");
			if (! session_check("participator"))
				warning("INVALID_SURVEY");

			$survey = Survey::exists("id", $surveyId);
			if (! $survey)
				error("INVALID_SURVEY_DATA");
		}

		die($survey->data);
	}
}
