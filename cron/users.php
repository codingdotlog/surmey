<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function normalize_turkish_phone_numbers($text) {
    preg_match_all('/(?:\+?90|0)?\s*\(?5\d{2}\)?[\s-]*\d{3}[\s-]*\d{2}[\s-]*\d{2}/', $text, $matches);

    $normalized = [];

    foreach ($matches[0] as $raw) {
        // Rakam dışı karakterleri kaldır
        $digits = preg_replace('/\D/', '', $raw);

        // Olası durumlar:
        // 905XXXXXXXXX  → 5XXXXXXXXX
        // 05XXXXXXXXX   → 5XXXXXXXXX
        // 5XXXXXXXXX    → 5XXXXXXXXX

        if (strlen($digits) == 12 && substr($digits, 0, 2) === "90") {
            // 90 + 5XXXXXXXXX
            $digits = substr($digits, 2);
        } elseif (strlen($digits) == 11 && substr($digits, 0, 1) === "0") {
            // 0 + 5XXXXXXXXX
            $digits = substr($digits, 1);
        } elseif (strlen($digits) == 10 && substr($digits, 0, 1) === "5") {
            // zaten istediğimiz format
        } else {
            continue;
        }

        $normalized[] = $digits;
    }

    return array_unique($normalized);
}

if ($_GET["action"] == "users") {
	$ORACLEconnection = oci_connect("HAUS", "HAUS", "//10.10.50.50:1521/PROD", 'AL32UTF8');

	$dbs = ["surmey", "flamingo"];

	foreach ($dbs as $dbName) {
		try {
			$pdo = new PDO("mysql:host=localhost;port=3333;dbname=$dbName;charset=utf8", "root", "Le8Of67IRExo", array(PDO::ATTR_PERSISTENT => true));
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			print $e->getMessage();
			die();
		}
		//DELETE TABLE
		$delete = $pdo->prepare("TRUNCATE TABLE personals");
		$delete->execute();
		$delete->closeCursor();

		if ($dbName != "flamingo") {
			$insert = $pdo->prepare("INSERT INTO personals (id, fullname) VALUES (:id, :fullname)");
			$insert->bindValue(":id", "0");
			$insert->bindValue(":fullname", "0");
			$insert->execute();
			$insert->closeCursor();
		}

		# '05',  => ssh centriplus
		# '06',  => centri
		# '90',  => belenkuyu
		# '91',  => turbocentri
		
		$companies = "'01', '07', '20', '21', '22', '23', '24', '25', '26', '27', '29'";
		#if($dbName = "flamingo")
			#$companies = "'01'";
		
		$sql = oci_parse($ORACLEconnection, "SELECT DISTINCT IASHCMPER.PERSID AS REGNO, IASHCMPER.CONTACTNUM, IASADRBOOKCONTACT.DISPLAY AS FULLNAME, IASADRBOOKCONTACT.HMOBILE AS PHONE1, IASADRBOOKCONTACT.BMOBILE AS PHONE2, CASE   WHEN IASADRBOOKCONTACT.EMAIL IS NULL THEN IASUSERS.MAILADRESS   ELSE IASADRBOOKCONTACT.EMAIL END       AS EMAIL, IASBAS082X4.STEXT  AS DEPARTMENT, IASADRBKCNTID.TCKIMLIKNO  AS TC, IASADRBOOKCONTACT.GENDER  AS GENDER, CASE   WHEN IASHCMPER.EMPLTYPE = 0 THEN 1   ELSE 0 END       AS STATUS, IASHCMSALARY.PERACCGRP, IASHCM203X12.STEXT AS PERACCGRPT, IASBAS083X5.STEXT  AS ORGPLACETEXT, IASADRBKCNTREC.COMPANY, IASADRBKCNTREC.STARTDATE, (SELECT MIN(BKCNTREC.STARTDATE) FROM   IASADRBKCNTREC BKCNTREC WHERE  BKCNTREC.CLIENT = '00'        AND BKCNTREC.CONTACTNUM =   IASHCMPER.CONTACTNUM) as startdate2, IASADRBOOKCONTACT.BIRTHDAY FROM   IASHCMPER inner join IASADRBOOKCONTACT  ON( IASADRBOOKCONTACT.CLIENT = IASHCMPER.CLIENT    AND IASADRBOOKCONTACT.CONTACTNUM = IASHCMPER.CONTACTNUM    AND IASADRBOOKCONTACT.CTYPE = 1 ) inner join IASADRBKCNTREC  ON ( IASADRBKCNTREC.CLIENT = IASHCMPER.CLIENT     AND IASADRBKCNTREC.CONTACTNUM = IASHCMPER.CONTACTNUM ) inner join IASADRBKCNTORG  ON ( IASADRBKCNTORG.CLIENT = IASHCMPER.CLIENT     AND IASADRBKCNTORG.CONTACTNUM = IASHCMPER.CONTACTNUM ) left outer join IASADRBKCNTID     ON ( IASADRBKCNTID.CLIENT = IASHCMPER.CLIENT   AND IASADRBKCNTID.CONTACTNUM = IASHCMPER.CONTACTNUM ) left outer join IASUSERS     ON ( IASUSERS.CLIENT = IASHCMPER.CLIENT   AND IASUSERS.CONTACTNUM = IASHCMPER.CONTACTNUM ) left outer join IASBAS005X IASBAS005X3     ON ( IASBAS005X3.CLIENT = IASADRBKCNTREC.CLIENT   AND IASBAS005X3.COMPANY = IASADRBKCNTREC.COMPANY   AND IASBAS005X3.PLANT = IASADRBKCNTREC.PLANT   AND IASBAS005X3.LANGU = 'T' ) left outer join IASBAS082X IASBAS082X4     ON ( IASBAS082X4.CLIENT = IASADRBKCNTORG.CLIENT   AND IASBAS082X4.COMPANY = IASADRBKCNTREC.COMPANY   AND IASBAS082X4.PLANT = IASADRBKCNTREC.PLANT   AND IASBAS082X4.DEPARTCODE = IASADRBKCNTORG.DEPARTMENT   AND IASBAS082X4.LANGU = 'T' ) left outer join IASBAS083X IASBAS083X5     ON ( IASBAS083X5.CLIENT = IASADRBKCNTORG.CLIENT   AND IASBAS083X5.COMPANY = IASADRBKCNTREC.COMPANY   AND IASBAS083X5.WORKTITLE = IASADRBKCNTORG.WORKTITLE   AND IASBAS083X5.LANGU = 'T' ) left outer join IASBAS081X IASBAS081X9     ON ( IASBAS081X9.CLIENT = IASADRBKCNTORG.CLIENT   AND IASBAS081X9.COMPANY = IASADRBKCNTREC.COMPANY   AND IASBAS081X9.ORGPLACE = IASADRBKCNTORG.ORGPLACE   AND IASBAS081X9.LANGU = 'T' ) left outer join (SELECT *  FROM   IASHCMSALARY  WHERE  CLIENT = '00'  AND PAYROLLGRP = 'NORM'  AND VALIDFROM = (SELECT Max(SALARY.VALIDFROM)FROM   IASHCMSALARY SALARY WHERE  SALARY.CLIENT = '00' AND SALARY.PERSID =     IASHCMSALARY.PERSID AND SALARY.PAYROLLGRP =     'NORM')) IASHCMSALARY     ON IASHCMPER.PERSID = IASHCMSALARY.PERSID left outer join IASHCM203X IASHCM203X12     ON ( IASHCM203X12.CLIENT = IASHCMSALARY.CLIENT   AND IASHCM203X12.COMPANY = IASADRBKCNTREC.COMPANY   AND IASHCM203X12.CLASSDEGREE = IASHCMSALARY.PERACCGRP   AND IASHCM203X12.LANGU = 'T' ) WHERE  IASHCMPER.CLIENT = '00' AND IASADRBKCNTREC.STARTDATE = (SELECT Max(BKCNTREC.STARTDATE) FROM   IASADRBKCNTREC BKCNTREC WHERE  BKCNTREC.CLIENT = '00'        AND BKCNTREC.CONTACTNUM =   IASHCMPER.CONTACTNUM) AND IASADRBKCNTORG.VALIDFROM = (SELECT Max(BKCNTORG.VALIDFROM) FROM   IASADRBKCNTORG BKCNTORG WHERE  BKCNTORG.CLIENT = '00'        AND BKCNTORG.CONTACTNUM =   IASHCMPER.CONTACTNUM) AND IASADRBKCNTREC.COMPANY IN ( $companies ) AND IASHCMPER.ISDELETED = 0 AND IASHCMPER.ISDUMMY = 0 AND IASHCMPER.EMPLTYPE = 0");
		oci_execute($sql);
		$columns = ["PHONE1", "PHONE2"];

		$nums = [];

		while ($print = oci_fetch_array($sql, OCI_ASSOC + OCI_RETURN_NULLS)) {
			$personalId = $print["REGNO"];
			$contactNum = $print["CONTACTNUM"];

			foreach ($columns as $column) {
				if (empty($print[$column]))
					continue;

				$print[$column] = current(normalize_turkish_phone_numbers($print[$column]));
				/*$columnValue = preg_replace('/\D/', '', $print[$column]);

				if (@str_starts_with($columnValue, "00905"))
					$print[$column] = substr($columnValue, 4, strlen($columnValue));

				if (@str_starts_with($columnValue, "05"))
					$print[$column] = substr($columnValue, 1, strlen($columnValue));*/
			}

			$print["TC"] = preg_replace('/\D/', '', $print["TC"]);

			if ($personalId == 50098 && $contactNum == '000000011771')
				continue;

			$insert = $pdo->prepare("INSERT INTO personals (id, contactnum, fullname, phone1, phone2, email, department, tc, gender, status, type, typeText, designation, company,birthday, startedDate) VALUES (:id, :contactnum, :fullname, :phone1, :phone2, :email, :department, :tc, :gender, :status, :type, :typeText, :designation, :company, :birthday, :startedDate)");
			$insert->bindParam(":id", $personalId);
			$insert->bindParam(":contactnum", $contactNum);
			$insert->bindParam(":fullname", $print["FULLNAME"]);
			$insert->bindParam(":phone1", $print["PHONE1"]);
			$insert->bindParam(":phone2", $print["PHONE2"]);
			$insert->bindParam(":email", $print["EMAIL"]);
			$insert->bindParam(":department", $print["DEPARTMENT"]);
			$insert->bindParam(":tc", $print["TC"]);
			$insert->bindParam(":gender", $print["GENDER"]);
			$insert->bindParam(":status", $print["STATUS"]);
			$insert->bindParam(":type", $print["PERACCGRP"]);
			$insert->bindParam(":typeText", $print["PERACCGRPT"]);
			$insert->bindParam(":designation", $print["ORGPLACETEXT"]);
			$insert->bindParam(":company", $print["COMPANY"]);

			$date = DateTime::createFromFormat('d.m.Y', $print["BIRTHDAY"] ?? "01.01.1970");
			$birthday = $date->format('Y-m-d');
			$insert->bindParam(":birthday", $birthday);

			$date = DateTime::createFromFormat('d.m.Y H:i:s', $print["STARTDATE2"]);
			$startedDate = $date->format('Y-m-d H:i:s');
			$insert->bindParam(":startedDate", $startedDate);


			$insert->execute();
			$insert->closeCursor();

			if ($dbName == "flamingo") {


				// check personal profile has exists
				$selectResult = $pdo->prepare("select count(id) result from personal_profiles where personal_id = $personalId");
				$selectResult->execute();

				$resultFlag = !$selectResult->fetch(PDO::FETCH_OBJ)->result;
				$selectResult->closeCursor();

				if ($resultFlag) {
					$photoPath = __DIR__ . "/../../httpdocs/personel/$contactNum.jpg";


					if (!file_exists($photoPath)) {
						$nums[] = $contactNum;
						continue;
					}


					$newFileName = md5($contactNum);
					$targetPhotoPath = __DIR__ . "/../../flamingo.hauscloud.net/storage/app/public/profile-photos/$newFileName.jpg";
					if (!copy($photoPath, $targetPhotoPath))
						continue;

					$photo = "profile-photos/$newFileName.jpg";

					$query = "insert into personal_profiles (personal_id, profile_photo) values(:personalId, :photo)";
					$insertResult = $pdo->prepare($query);
					$insertResult->bindParam(":personalId", $personalId);
					$insertResult->bindParam(":photo", $photo);

					$insertResult->execute();
					$insertResult->closeCursor();
				}
			}
		}

		#echo "select * from personals where contactnum in (".join("','", $nums).")";
	}


	echo "OK";
}
