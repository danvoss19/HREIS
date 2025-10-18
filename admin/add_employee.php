<?php
include_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDirs = [
        "avatar"            => "uploads/avatars/",
        "gov_id"            => "uploads/gov_id/",
        "cv"                => "uploads/cv/",
        "service_record"    => "uploads/service_record/",
        "appointment_paper" => "uploads/appointment_paper/",
        "tor"               => "uploads/tor/",
        "cs_certificate"    => "uploads/cs_certificate/",
        "pds"               => "uploads/pds/"
    ];

    foreach ($uploadDirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    function uploadFile($fieldName, $uploadPath)
    {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === 0) {
            $filePath = $uploadPath . basename($_FILES[$fieldName]['name']);
            move_uploaded_file($_FILES[$fieldName]['tmp_name'], $filePath);
            return $filePath;
        }
        return null;
    }

    $avatar          = uploadFile("avatar", $uploadDirs["avatar"]);
    $gov_id          = uploadFile("gov_id", $uploadDirs["gov_id"]);
    $cv              = uploadFile("cv", $uploadDirs["cv"]);
    $service_record  = uploadFile("service_record", $uploadDirs["service_record"]);
    $appointment_paper = uploadFile("appointment_paper", $uploadDirs["appointment_paper"]);
    $tor             = uploadFile("tor", $uploadDirs["tor"]);
    $cs_certificate  = uploadFile("cs_certificate", $uploadDirs["cs_certificate"]);
    $pds             = uploadFile("pds", $uploadDirs["pds"]);

    $username = strtolower($_POST['firstname'] . "." . $_POST['lastname']);
    $password = md5($_POST['password']);
    $stmt = $conn->prepare("
        INSERT INTO employees 
        (employee_id, avatar, username, firstname, lastname, mobile_number, date_of_birth, marital_status, gender, nationality, address, city, state, zip_code, designation, employee_type, salary_grade, department, cs_eligibility, working_days, joining_date, government_id, cv, service_record, appointment_paper, tor, cs_certificate, pds, email, password, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')
    ");
    $stmt->bind_param(
        "ssssssssssssssssssssssssssssss",
        $_POST['employeeID'],
        $avatar,
        $username,
        $_POST['firstname'],
        $_POST['lastname'],
        $_POST['mobile_number'],
        $_POST['date_of_birth'],
        $_POST['marital_status'],
        $_POST['gender'],
        $_POST['nationality'],
        $_POST['address'],
        $_POST['city'],
        $_POST['state'],
        $_POST['zip_code'],
        $_POST['designation'],
        $_POST['employee_type'],
        $_POST['salary_grade'],
        $_POST['department'],
        $_POST['cs_eligibility'],
        $_POST['working_days'],
        $_POST['joining_date'],
        $gov_id,
        $cv,
        $service_record,
        $appointment_paper,
        $tor,
        $cs_certificate,
        $pds,
        $_POST['email'],
        $password
    );

    try {
        $stmt->execute();
        header("Location: employee.php?msg=added");
        exit;
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            header("Location: employee.php?msg=duplicate");
        } else {
            $error = urlencode($e->getMessage());
            header("Location: employee.php?msg=error&details=$error");
        }
        exit;
    }
}
