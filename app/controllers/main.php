<?php

namespace App\Controllers;

use App\Models\Survey;
use App\Models\User;
use Core\{Controller, Request};
use Core\Attributes\route;

class Main extends Controller
{
    #[route(method: route::get | route::xhr_get, session: "user", otherwise: "/auth")]
    public function index()
    {
        $this->view("main", "dashboard", "Surmey", [
            "user" => User::info(),
            "surveyCount" => Survey::count(User::id()),
            "participateCount" => Survey::participateCount(User::id()),
            "userCount" => $this->db->select("count(id)")->from("users")->first()
        ]);
    }

    public function auth()
    {
        if (session_check("user"))
            redirect("/");

        session_remove("tempPin");
        if (session_check("tempPin"))
            redirect("/pin");

        $this->render("auth", [
            "title" => lang("login.required")
        ]);
    }

    #[route(method: route::xhr_post, uri: "auth")]
    public function authAjax()
    {
        if (session_check("user"))
            errorlang("authorize.error");

        $post = Request::post();
        $check = validate($post, [
            "userNameOrEmail" => ["name" => lang("username.or.email"), "required" => true, "dash" => true],
            "password" => ["name" => lang("password"), "required" => true, "min" => 6, "max" => 32],
            "csrf" => ["name" => lang("security.code"), "required" => true]
        ]);

        if ($check)
            error($check);
        elseif (! check_csrf($post->csrf))
            errorlang("csrf.error", scrollTo: true);

        $isEmail = strpos($post->userNameOrEmail, "@");
        if ($isEmail && ! validate_email($post->userNameOrEmail))
            error(lang("validation.email_error"));

        if (! $isEmail && ! alpha_dash($post->userNameOrEmail))
            error(lang("validation.alpha.dash.error", lang("username.or.email")));

        $password = hash("sha256", $post->password);

        $memberDetail = User::validateAuth($post->userNameOrEmail, $password);
        if (!$memberDetail)
            error(lang("wrong.auth.info"));

        while (User::exists("pin_token", ($pin = join(randomSequence(6)))));

        User::updateBy("id", $memberDetail->id, ["pin_token" => $pin]);
        session_set("tempPin", hash("sha256", $pin));


        #disabled for now
        #\SmsHelper::send($memberDetail->phone, "form.hauscloud için onay kodu: ". $pin);
        #success(redirect: "/pin");

        session_regenerate_id(true);

        session_set("user", $memberDetail);
        success(redirect: "/");
    }

    #[route(method: route::xhr_get | route::get)]
    public function pin()
    {
        if (session_check("user") || !session_check("tempPin"))
            redirect("/");

        $tempPin = session_get("tempPin");
        if (!$tempPin)
            redirect("/");

        $this->render("auth-pin", [
            "title" => "PIN",
            "token" => $tempPin
        ]);
    }

    #[route(method: route::xhr_post, uri: "pin")]
    public function validatePin()
    {
        $post = Request::post();
        $validate = validate($post, [
            "pin" => ["name" => "pin", "min" => 6, "max" => 6],
            "token" => ["name" => "token", "min" => 64, "max" => 64],
            "csrf" => ["name" => lang("security.code"), "required" => true]
        ]);

        if ($validate)
            error($validate);
        elseif (! check_csrf($post->csrf))
            errorlang("csrf.error");

        $tempPin = session_get("tempPin");
        if ($post->token != $tempPin)
            error("UNDEFINED_TOKEN");
        elseif (hash("sha256", $post->pin) != $tempPin)
            errorlang("pin.error");

        if (! ($userInfo = User::validatePin($post->token, $post->pin)))
            warninglang("pin.error");

        session_remove("tempPin");

        session_regenerate_id(true);

        session_set("user", $userInfo);
        success(redirect: "/");
    }

    #[route(method: route::xhr_post, uri: "change-password", session: "user")]
    public function changePassword()
    {
        $post = Request::post();
        $validate = validate($post, [
            "password" => ["name" => lang("password"), "required" => true, "min" => 6, "max" => 32],
            "newPassword" => ["name" => lang("new.password"), "required" => true, "min" => 6, "max" => 32, "no-match" => "password"],
            "newPasswordConfirm" => ["name" => lang("new.password.confirm"), "required" => true, "min" => 6, "max" => 32, "match" => "newPassword"]
        ]);

        if ($validate)
            warning($validate);

        $post->password = hash("sha256", $post->password);
        $post->newPassword = hash("sha256", $post->newPassword);

        $info = User::info();

        if ($post->password != $info->password)
            warninglang("password.not.correct");
        elseif ($post->newPassword == $info->prevPassword)
            warninglang("password.used.before");

        $result = User::update([
            "prevPassword" => $info->password,
            "password" => $post->newPassword
        ]);

        if ($result) {
            session_remove("user");
            session_destroy();
            successlang("password.successfully.changed", redirect: "/auth:2000");
        }

        getDataError();
    }

    #[route(method: route::get | route::xhr_get)]
    function signout()
    {
        session_remove("user");
        session_destroy();
        success(redirect: "/");
    }

    #[route(method: route::get | route::xhr_get)]
    function successfully($slug)
    {
        $survey = Survey::exists("slug", $slug);
        if (!$survey || $survey->status != 1)
            warning("SURVEY_ERROR: $slug");
        
        $this->render("survey/successfully", [
            "title" => $survey->title." anketine başarıyla katıldınız!"
        ]);
    }

    #[route(method: route::get, uri: "d")]
    public function participateSurvey(string $slug)
    {
        $survey = Survey::exists("slug", $slug);
        if (!$survey || $survey->status != 1)
            redirect("/");

        if (session_check("user") || !$survey->verifyPhone)
            session_set("survey", $survey);

        if (!$survey->verifyPhone) {
            session_set("surveySlug", $slug);
            session_set("surveyId", $survey->id);
            /*session_set("participator", (object)[
                "personalId" => 0
            ]);*/
        }
        
        if ($survey->verifyPhone && !session_check("user")) {
            session_set("surveySlug", $slug);
            session_set("surveyId", $survey->id);
            redirect("/participate");
        }

        $this->render("survey/participate", ["title" => $survey->title, "survey" => $survey]);
    }
}
