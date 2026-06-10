<?php

namespace App\Controllers;

use App\Helpers\Mail;
use App\Models\User;
use App\Models\Survey;
use Core\{Controller, Request, Database};
use Core\Attributes\route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class Reports extends Controller
{
    #[route(method: route::get | route::xhr_get, session: "user", otherwise: "/auth")]
    public function watch(int $surveyId)
    {
        $survey = Survey::existsByUserId(User::id(), "id", $surveyId);
        if (!$survey)
            redirect();

        $result = Survey::report($survey);

        $departments = $this->db
            ->select("DISTINCT department")
            ->from("personals")
            ->where("id", "!=", "0")
            ->results();
        $departmentList = array_map(fn($dept) => $dept->department, $departments);

        $personList = $this->db
            ->select("id, fullname")
            ->from("personals")
            ->where("id", "!=", "0")
            ->where("status", "=", 1)
            ->orderBy("fullName", "ASC")
            ->results();

        $args = [
            "surveyTitle" => $survey->title,
            "user" => User::info(),
            "data" => $result,
            "surveyId" => $surveyId,
            "departmentList" => $departmentList,
            "personList" => $personList,
            "anonymous" => !$survey->verifyPhone
        ];

        if ($survey->verifyPhone)
            $args["participators"] = Survey::participators($surveyId);

        $this->view("main", "reports", lang("reports"), $args);
    }

    #[route(method: route::xhr_get)]
    public function reset(int $surveyId)
    {
        $survey = Survey::existsByUserId(User::id(), "id", $surveyId);
        if (!$survey)
            redirect();

        $count = Survey::reset($surveyId);
        if ($count)
            success(refresh: true);

        getDataError();
    }

    #[route(method: route::get)]
    public function csv(int $surveyId)
    {
        $survey = Survey::existsByUserId(User::id(), "id", $surveyId);
        if (!$survey)
            redirect();

        ob_clean();

        header('Pragma: private');
        header('Cache-control: private, must-revalidate');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $csvFileName = preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '-', $survey->title));
        header('Content-Disposition: attachment; filename=' . $csvFileName . '.xlsx');
        header('Cache-Control: max-age=0');


        $questionData = json_decode($survey->data);

        $isAnonymousSurvey = !$survey->verifyPhone;

        $questions = [];
        foreach ($questionData as $value) {
            if ($value->type != "description")
                $questions[] = $value;
        }

        $titleRow = $isAnonymousSurvey ? ['Katılımcı'] : ['Sicil No', 'Ad Soyad'];
        foreach ($questions as $q)
            $titleRow[] = html_entity_decode(strip_tags(trim($q->title)));

        $rows = [$titleRow];

        if ($isAnonymousSurvey) {
            $participators = $this->db
                ->select("id, data")
                ->from("answers")
                ->where("surveyId", "=", $surveyId)
                ->where("done", "=", 1)
                ->orderBy("id", "ASC")
                ->results();
        } else {
            // Kimlikli anket: her cevabı personals ile eşleştir (asla Anonim etiketi kullanma)
            $participators = $this->db
                ->select("answers.id, answers.personalId, answers.data, answers.done, personals.fullname")
                ->from("answers")
                ->leftJoin("personals", "personals.id = answers.personalId")
                ->where("answers.surveyId", "=", $surveyId)
                ->where("answers.done", "=", 1)
                ->orderBy("answers.id", "ASC")
                ->results();
        }

        $anonIndex = 0;
        foreach ($participators as $participator) {
            if ($isAnonymousSurvey) {
                $anonIndex++;
                $row = ["Anonim #" . $anonIndex];
            } else {
                $row = [
                    $participator->personalId,
                    $participator->fullname ?? $participator->fullName ?? ''
                ];
            }

            $answerData = json_decode($participator->data ?? '', JSON_OBJECT_AS_ARRAY);
            if (! is_array($answerData))
                $answerData = [];

            foreach ($questions as $question) {
                $row[] = self::formatAnswerCell($question, $answerData);
            }

            $rows[] = $row;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Veriler');
        $sheet->fromArray($rows);

        for ($colIndex = 0; $colIndex < count($titleRow); $colIndex++) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $cellCoordinate = $columnLetter . '1'; // 1. satır

            $sheetStyle = $sheet->getStyle($cellCoordinate);

            $sheetStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFC00000');
            $sheetStyle->getFont()->setColor(new Color(Color::COLOR_WHITE))->setSize(12);
            $sheetStyle->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('FF888888');
        }


        $result = Survey::report($survey);

        $startRow = 1;
        $chartsPerRow = 3;
        $chartWidth = 10; // Grafiğin genişliği (sütun sayısı)
        $chartHeight = 20; // Grafik yüksekliği (satır sayısı)

        $i = 0;
        $worksheet = new Worksheet(title: "Raporlar");

        foreach ($result as $key => $value) {
            // Kategoriler ve değerler dizisi
            $categories = array_map(fn($v) => html_entity_decode(strip_tags($v)), array_keys($value["answers"]));
            $values = array_map(fn($v) => html_entity_decode(strip_tags($v)), array_values($value["answers"]));
            // Dizileri hücrelere yazma
            $categoryColumnIndex = ($i % $chartsPerRow) * $chartWidth + 1; // Sütun indeksi (1'den başlar)
            $valueColumnIndex = $categoryColumnIndex + 1; // Bir sonraki sütun

            $categoryColumn = Coordinate::stringFromColumnIndex($categoryColumnIndex);
            $valueColumn = Coordinate::stringFromColumnIndex($valueColumnIndex);

            $startDataRow = $startRow + $i * $chartHeight;

            foreach ($categories as $index => $category) {
                $worksheet->setCellValue($categoryColumn . ($startDataRow + $index), $category);
                $worksheet->setCellValue($valueColumn . ($startDataRow + $index), $values[$index]);
            }

            // Grafik verileri
            $dataSeriesValues = new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_NUMBER,
                "Raporlar!\${$valueColumn}\$" . $startDataRow . ":\${$valueColumn}\$" . ($startDataRow + count($values) - 1)
            );
            $xAxisTickValues = new DataSeriesValues(
                DataSeriesValues::DATASERIES_TYPE_STRING,
                "Raporlar!\${$categoryColumn}\$" . $startDataRow . ":\${$categoryColumn}\$" . ($startDataRow + count($categories) - 1)
            );

            $series = new DataSeries(
                DataSeries::TYPE_BARCHART,
                DataSeries::GROUPING_CLUSTERED,
                range(0, count([$dataSeriesValues]) - 1),
                [],
                [$xAxisTickValues],
                [$dataSeriesValues]
            );

            $plotArea = new PlotArea(null, [$series]);
            $title = new Title(html_entity_decode(strip_tags($key)));

            $chart = new Chart(
                'chart' . ($i + 1),
                $title,
                new Legend(Legend::POSITION_RIGHT, null, false),
                $plotArea
            );

            // Grafiklerin pozisyonlarını dinamik olarak ayarlama
            $columnOffset = ($i % $chartsPerRow) * $chartWidth; // Yatay offset
            $rowOffset = floor($i / $chartsPerRow) * $chartHeight; // Dikey offset

            $chart->setTopLeftPosition(Coordinate::stringFromColumnIndex($columnOffset + 1) . ($startRow + $rowOffset));
            $chart->setBottomRightPosition(Coordinate::stringFromColumnIndex($columnOffset + $chartWidth) . ($startRow + $rowOffset + $chartHeight - 1));
            $worksheet->addChart($chart);

            $i++;
        }

        $spreadsheet->addSheet($worksheet);
        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $writer->save('php://output');
    }

    #[route(method: route::xhr_post)]
    public function sendNotification(int $surveyId)
    {
        $survey = Survey::existsByUserId(User::id(), "id", $surveyId);
        if (!$survey)
            redirect();

        $post = Request::post();
        $types = explode(",", $post->types);
        
        $validate = validate($post, [
            "types" => ["name" => "types", "required" => true],
            "sms_message" => ["name" => "SMS mesajı", "max" => 300],
            "email_message" => ["name" => "E-posta mesajı"],
            "recipientType" => ["name" => "recipient_type", "required" => true],
            "recipients" => ["name" => "recipients"]
        ]);

        if ($validate)
            warning($validate);

        if(!in_array("sms", $types) && !in_array("email", $types))
            warning("Lütfen en az bir iletişim türü seçin");

        if(!in_array($post->recipientType, ["all", "department", "individual", "legal"]))
            warning("Lütfen geçerli bir alıcı türü seçin");

        if ($post->recipientType === "legal" && in_array("email", $types))
            warning("Tüzel kişi bildirimleri yalnızca SMS ile gönderilebilir");

        if(in_array("sms", $types) && (!isset($post->sms_message) || empty($post->sms_message)))
            warning("Lütfen SMS mesajı girin");

        if(in_array("email", $types) && (!isset($post->email_message) || empty($post->email_message)))
            warning("Lütfen geçerli bir e-posta mesajı girin");

        $contacts = [];
        $legalPhoneCount = 0;

        if ($post->recipientType === "legal") {
            if (!in_array("sms", $types))
                warning("Tüzel kişi için SMS seçmelisiniz");

            $legalPhones = self::parseManualPhones($post->legal_phones ?? "");
            if (empty($legalPhones))
                warning("Lütfen en az bir geçerli telefon numarası girin");

            $legalPhoneCount = count($legalPhones);
            foreach ($legalPhones as $phone)
                $contacts[] = (object) ["phone1" => $phone, "phone2" => null, "email" => null];
        } else {
            $query = $this->db->select("fullname, phone1, phone2, email, department")
                ->from("personals")
                ->where("status", value: 1)
                ->where("id", "!=", "0");

            if ($post->recipientType === "department") {
                if (empty($post->recipients))
                    warning("Lütfen en az bir departman seçin");
                $query->in("department", $post->recipients);
            } else if ($post->recipientType === "individual") {
                if (empty($post->recipients))
                    warning("Lütfen en az bir kişi seçin");
                $query->in("id", $post->recipients);
            }

            $contacts = $query->results();
        }
        #echo $this->db->lastQuery();

        $success = [];
        $errors = [];

        if (in_array("sms", $types)) {
            $messages = [];
            $post->sms_message = preg_replace('/[\xF0-\xF7][\x80-\xBF]{3}/', '', $post->sms_message);

            foreach ($contacts as $contact) {
                if (!empty($contact->phone1))
                    $messages[] = ["no" => $contact->phone1, "msg" => $post->sms_message];
                if (!empty($contact->phone2))
                    $messages[] = ["no" => $contact->phone2, "msg" => $post->sms_message];
            }

            if (empty($messages))
                warning("SMS gönderilecek geçerli telefon numarası bulunamadı");

            $result = \SmsHelper::send($messages);
            if ($result->code == 0)
                $success[] = "SMS'ler başarıyla gönderildi";
            else
                $errors[] = "SMS Hatası: " . $result->status;
        }

        if (in_array("email", $types)) {
            # Some emails are empty, so we need to filter them out
            $contacts = array_filter($contacts, fn($contact) => !empty($contact->email));
            if (empty($contacts))
                warning("E-posta gönderilecek kişi bulunamadı.");
            
            #print_r(array_map(fn($contact) => $contact->email, $contacts));
            Mail::send($post->email_subject, array_map(fn($contact) => $contact->email, $contacts), $post->email_message);
            $success[] = "E-Postalar başarıyla gönderildi";
        }

        if (!empty($errors)) {
            warning(implode("<br>", $errors));
        } else {
            $recipientLabel = $post->recipientType === "legal"
                ? $legalPhoneCount . " numaraya"
                : count($contacts) . " kişiye";
            success(implode("<br>", $success). "<br> " . $recipientLabel . " başarıyla gönderildi.");
        }
    }

    private static function formatAnswerCell(object $question, array $answerData): string
    {
        $parts = [];

        foreach ($answerData as $answerKey => $answerValue) {
            if (! str_starts_with((string) $answerKey, $question->slug))
                continue;

            if ($question->type == "checkbox") {
                $label = $question->answers[$answerValue] ?? '';
                if ($label !== '')
                    $parts[] = html_entity_decode(strip_tags(trim($label)));
            } elseif ($question->type == "textarea") {
                return html_entity_decode(strip_tags(trim((string) $answerValue)));
            } else {
                return html_entity_decode(strip_tags(trim($question->answers[$answerValue] ?? '')));
            }
        }

        return implode(' → ', $parts);
    }

    private static function parseManualPhones(string $input): array
    {
        $parts = preg_split('/[\s,;]+/', trim($input), -1, PREG_SPLIT_NO_EMPTY);
        $phones = [];

        foreach ($parts as $raw) {
            $digits = preg_replace('/\D/', '', $raw);

            if (strlen($digits) === 12 && str_starts_with($digits, '90'))
                $digits = substr($digits, 2);
            elseif (strlen($digits) === 11 && str_starts_with($digits, '0'))
                $digits = substr($digits, 1);

            if (strlen($digits) === 10 && str_starts_with($digits, '5'))
                $phones[] = $digits;
        }

        return array_values(array_unique($phones));
    }
}
