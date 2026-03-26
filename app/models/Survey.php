<?php

namespace App\Models;

use Core\{Model, Database};

final class Survey extends Model
{
    public const ALL = 0;
    public const ACTIVE = 1;
    public const PASSIVE = 2;

    public static function all(int $userId, $status = self::ACTIVE)
    {
        /*return Database::get()->select("count(answers.id) answersCount, surveys.*")
                ->from("surveys")
                ->leftJoin("answers", "answers.surveyId = surveys.id")
                ->where("surveys.userId", "=", $userId)
                ->results();*/

        $query = "
            select (
                select count(DISTINCT CASE
                    WHEN surveys.anonymous = 1 THEN answers.id
                    ELSE answers.personalId
                END)
                from answers
                where answers.surveyId = surveys.id and data IS NOT NULL and done = 1
            ) answersCount, 
            surveys.*
            from surveys
            where surveys.userId = ?" . ($status != self::ALL ? " and surveys.status = ?" : "");

        $params = [$userId];
        if ($status != 0)
            array_push($params, $status);

        return Database::get()->query($query)->results(params: $params);
    }

    public static function exists(string $column, string $value)
    {
        return Database::get()->from("surveys")->where($column, "=", $value)->result();
    }

    public static function reset(int $surveyId)
    {
        return Database::get()->from("answers")->where("surveyId", value: $surveyId)->delete();
    }

    public static function existsByUserId(int $userId, string $column, string $value)
    {
        return Database::get()->from("surveys")->where("userId", "=", $userId)->where($column, "=", $value)->result();
    }

    public static function update(string $surveyId, array $data)
    {
        return Database::get()->from("surveys")->where("id", "=", $surveyId)->update($data);
    }

    public static function count(int $userId)
    {
        return Database::get()->select("count(id)")->from("surveys")->where("userId", "=", $userId)->where("status", "=", 1)->first();
    }

    public static function participators(int $surveyId)
    {
        // İlk olarak benzersiz personalId'leri al
        $uniqueParticipants = Database::get()
            ->select("answers.personalId, MAX(answers.id) as maxId")
            ->from("answers")
            ->where("answers.surveyId", value: $surveyId)
            ->where("answers.done", value: 1)
            ->groupBy(["answers.personalId"])
            ->results();
        
        // Her personalId için tam veriyi al
        $participators = [];
        foreach ($uniqueParticipants as $participant) {
            $fullData = Database::get()
                ->select("answers.*, personals.fullname, personals.department")
                ->from("answers")
                ->leftJoin("personals", "personals.id = answers.personalId")
                ->where("answers.id", "=", $participant->maxId)
                ->result();
            
            if ($fullData) {
                $participators[] = $fullData;
            }
        }
        
        return $participators;
    }

    public static function participateCount(int $userId)
    {
        return Database::get()->select("count(DISTINCT answers.personalId)")
            ->from("answers")
            ->join("surveys", "surveys.id = answers.surveyId")
            ->where("surveys.userId", "=", $userId)
            ->where("answers.done", "=", 1)
            ->first();
    }

    public static function generateReportData($survey)
    {
        $questionData = json_decode($survey->data);
        if ((int) $survey->anonymous === 1) {
            // Anonim anketlerde personalId sabit/boş olabildiği için her yanıtı ayrı katılımcı kabul et.
            $answerData = Database::get()
                ->select("answers.id, answers.personalId, answers.data")
                ->from("answers")
                ->where("surveyId", "=", $survey->id)
                ->where("done", "=", 1)
                ->results();

            foreach ($answerData as $row) {
                $row->participantKey = $row->id;
                $row->fullname = null;
                $row->department = null;
            }
        } else {
            $answerData = Database::get()
                ->select("answers.personalId, MAX(answers.id) as maxId, personals.fullname, personals.department")
                ->from("answers")
                ->leftJoin("personals", "personals.id = answers.personalId")
                ->where("surveyId", "=", $survey->id)
                ->where("done", "=", 1)
                ->groupBy(["answers.personalId"])
                ->results();
            
            // Her personalId için en son cevabı al
            $finalAnswerData = [];
            foreach ($answerData as $row) {
                $fullAnswer = Database::get()
                    ->from("answers")
                    ->where("id", "=", $row->maxId)
                    ->result();
                
                if ($fullAnswer) {
                    $fullAnswer->participantKey = $row->personalId;
                    $fullAnswer->fullname = $row->fullname;
                    $fullAnswer->department = $row->department;
                    $finalAnswerData[] = $fullAnswer;
                }
            }
            
            $answerData = $finalAnswerData;
        }

        $generatedData = [];

        $groupIndex = 0;
        #for IK
        if ($survey->id == 1)
            $groups = ["Yönetici-Çalışan İlişkileri", "İletişim", "Göreve İlişkin Sorular", "Çalışma Koşulları", "Eğitim /Gelişim", "Kariyer", "Ücret Sosyal Yardımlar ve Ödüllendirme", "Genel Algı"];
        else
            $groups = ["default"];

        $isFirstDescription = true;

        foreach ($questionData as $question) {
            if ($question->type == "description") {
                if ($isFirstDescription)
                    $isFirstDescription = false;
                else
                    $groupIndex++;

                continue;
            }

            // Koşullu soru kontrolü - bu soru koşula bağlı mı?
            $hasCondition = false;
            $parentQuestion = null;
            $parentAnswerIndex = null;
            
            foreach ($questionData as $parentQ) {
                if (!empty($parentQ->conditions)) {
                    foreach ($parentQ->conditions as $cond) {
                        if ($cond->value == $question->slug) {
                            $hasCondition = true;
                            $parentQuestion = $parentQ;
                            $parentAnswerIndex = $cond->index;
                            break 2;
                        }
                    }
                }
            }

            $group0 = $groups[$groupIndex];
            
            // Radio için tüm katılımcıları döndüren fonksiyon
            $searchAll = function () use ($question, $answerData, $hasCondition, $parentQuestion, $parentAnswerIndex) {
                return array_filter($answerData, function ($aw_V, $aw_K) use ($question, $hasCondition, $parentQuestion, $parentAnswerIndex) {
                    if(!$aw_V || !$aw_V->data)
                        return false;

                    $decodedJson = json_decode($aw_V->data, JSON_OBJECT_AS_ARRAY);
                    if (! $decodedJson)
                        return false;

                    // Koşullu soru kontrolü - eğer bu soru koşula bağlıysa, parent sorunun cevabını kontrol et
                    if ($hasCondition && $parentQuestion) {
                        $parentAnswer = $decodedJson[$parentQuestion->slug] ?? null;
                        if ($parentAnswer != $parentAnswerIndex) {
                            return false; // Koşul sağlanmamış, bu cevabı dahil etme
                        }
                    }

                    // Bu soruyu cevaplayan katılımcıları döndür
                    return array_key_exists($question->slug, $decodedJson);

                }, ARRAY_FILTER_USE_BOTH);
            };
            
            $search = function ($k) use ($question, $answerData, $hasCondition, $parentQuestion, $parentAnswerIndex) {
                return array_filter($answerData, function ($aw_V, $aw_K) use ($question, $k, $hasCondition, $parentQuestion, $parentAnswerIndex) {
                    if(!$aw_V || !$aw_V->data)
                        return;

                    $decodedJson = json_decode($aw_V->data, JSON_OBJECT_AS_ARRAY);
                    if (! $decodedJson)
                        return;

                    // Koşullu soru kontrolü - eğer bu soru koşula bağlıysa, parent sorunun cevabını kontrol et
                    if ($hasCondition && $parentQuestion) {
                        $parentAnswer = $decodedJson[$parentQuestion->slug] ?? null;
                        if ($parentAnswer != $parentAnswerIndex) {
                            return false; // Koşul sağlanmamış, bu cevabı dahil etme
                        }
                    }

                    $s = $question->type == "checkbox" ? $question->slug . $k : $question->slug;

                    $exists = array_key_exists($s, $decodedJson);
                    if ($exists && $question->type == "radio")
                        return $decodedJson[$s] == $k;

                    return $exists;

                }, ARRAY_FILTER_USE_BOTH);
            };

            // Radio ve checkbox için farklı işlem
            if ($question->type == "radio") {
                // Radio için: Her katılımcıyı bir kez kontrol et ve cevabını bul
                $allParticipants = $searchAll();
                
                // Önce tüm cevaplar için boş array oluştur
                foreach ($question->answers as $answerKey => $answer) {
                    $generatedData[$group0][$question->type . "::" . $question->title][$answer] = [];
                }
                
                // Her katılımcı için cevabını bul ve ekle
                foreach ($allParticipants as $fValue) {
                    $decodedJson = json_decode($fValue->data, JSON_OBJECT_AS_ARRAY);
                    
                    if (! isset($decodedJson[$question->slug]))
                        continue;
                    
                    $answerValue = $decodedJson[$question->slug];
                    $answerText = $question->answers[$answerValue] ?? null;
                    
                    if ($answerText) {
                        $generatedData[$group0][$question->type . "::" . $question->title][$answerText][] = (object) [
                            "id" => $fValue->participantKey ?? $fValue->personalId,
                            "fullname" => $fValue->fullname,
                            "department" => $fValue->department,
                            "value" => $answerValue
                        ];
                    }
                }
            } else {
                // Checkbox için: Her cevap seçeneği için ayrı kontrol
                foreach ($question->answers as $answerKey => $answer) {
                    $filtered = $search($answerKey);
                    if (! count($filtered))
                        $generatedData[$group0][$question->type . "::" . $question->title][$answer] = [];

                    foreach ($filtered as $fValue) {
                        $slug = $question->slug . $answerKey;

                        $decodedJson = json_decode($fValue->data, JSON_OBJECT_AS_ARRAY);

                        if (! isset($decodedJson[$slug]))
                            continue;

                        $answerValue = $decodedJson[$slug];

                        $generatedData[$group0][$question->type . "::" . $question->title][$answer][] = (object) [
                            "id" => $fValue->participantKey ?? $fValue->personalId,
                            "fullname" => $fValue->fullname,
                            "department" => $fValue->department,
                            "value" => $answerValue
                        ];
                    }
                }
            }

            if ($question->type != "textarea")
                continue;

            $data = $searchAll();

            foreach ($data as $answer) {
                $answerValue = json_decode($answer->data, JSON_OBJECT_AS_ARRAY)[$question->slug];
                $generatedData[$group0][$question->type . "::" . $question->title][] = (object) [
                    "id" => $answer->participantKey ?? $answer->personalId,
                    "fullname" => $answer->fullname,
                    "department" => $answer->department,
                    #"answer" => $answerTitle,
                    "value" => $answerValue
                ];

            }
        }

        return $generatedData;
    }

    public static function report($survey)
    {
        $generatedData = self::generateReportData($survey);

        $result = [];

        $group0Data = current($generatedData);
        if (! $group0Data)
            $group0Data = [];

        foreach ($group0Data as $key => $question) {
            $split = explode("::", $key);

            $type = $split[0];
            $title = $split[1];

            $result[$title] = [
                "type" => $type
            ];

            $result[$title]["list-json"] = [];

            $totalCount = 0;
            if ($type === "radio" || $type === "checkbox") {
                // Radio ve Checkbox için benzersiz katılımcı sayısını hesaplamak için
                $uniqueParticipantIds = [];
                
                foreach ($question as $answerK => $answerV) {
                    $count = count($answerV);
                    $result[$title]["answers"][$answerK] = $count;
                    
                    // Radio ve Checkbox için benzersiz katılımcı ID'lerini topla
                    foreach ($answerV as $participant) {
                        // personalId veya id field'ını kontrol et
                        $participantId = $participant->id ?? $participant->personalId ?? null;
                        if ($participantId && !in_array($participantId, $uniqueParticipantIds)) {
                            $uniqueParticipantIds[] = $participantId;
                        }
                    }
                }
                
                // Radio ve Checkbox için benzersiz katılımcı sayısını kullan
                $totalCount = count($uniqueParticipantIds);

                $result[$title]["total"] = $totalCount;
            } else {
                // Textarea için benzersiz katılımcı sayısını hesaplamak için
                $uniqueParticipantIds = [];
                $emptyCount = $fillCount = 0;
                
                foreach ($question as $answerK => $answerV) {
                    // personalId veya id field'ını kontrol et
                    $participantId = $answerV->id ?? $answerV->personalId ?? null;
                    
                    // Benzersiz katılımcı kontrolü
                    if ($participantId && !in_array($participantId, $uniqueParticipantIds)) {
                        $uniqueParticipantIds[] = $participantId;
                        
                        if (empty($answerV->value))
                            $emptyCount++;
                        else {
                            $fillCount++;
                            $result[$title]["list-json"][] = $answerV;
                        }
                    }
                }

                $result[$title]["answers"] = [
                    "Dolu" => $fillCount,
                    "Boş" => $emptyCount
                ];

                $totalCount = count($uniqueParticipantIds);

                $result[$title]["list-json"] = data_json($result[$title]["list-json"]);
            }

            $result[$title]["total"] = $totalCount;
        }

        return $result;
    }
}
