<?php
require_once '../db.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo json_encode($result);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id              = intval($_POST['id']);
    $firstname       = $_POST['firstname'];
    $lastname        = $_POST['lastname'];
    $mobile_number   = $_POST['mobile_number'];
    $email           = $_POST['email'];
    $date_of_birth   = $_POST['date_of_birth'];
    $gender          = $_POST['gender'];
    $nationality     = $_POST['nationality'];
    $address         = $_POST['address'];
    $city            = $_POST['city'];
    $state           = $_POST['state'];
    $zip_code        = $_POST['zip_code'];
    $marital_status  = $_POST['marital_status'];
    $employee_id     = $_POST['employeeID'];
    $designation     = $_POST['designation'];
    $employee_type   = $_POST['employee_type'];
    $salary_grade    = $_POST['salary_grade'];
    $department      = $_POST['department'];
    $cs_eligibility  = $_POST['cs_eligibility'];
    $working_days    = $_POST['working_days'];
    $joining_date    = $_POST['joining_date'];

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
    function uploadFile($fieldName, $uploadPath, $currentFile = "")
    {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === 0) {
            $filePath = $uploadPath . basename($_FILES[$fieldName]['name']);
            move_uploaded_file($_FILES[$fieldName]['tmp_name'], $filePath);
            return $filePath;
        }
        return $currentFile ?: "";
    }

    $avatar           = uploadFile("avatar", $uploadDirs["avatar"], $_POST['old_avatar'] ?? null);
    $gov_id           = uploadFile("gov_id", $uploadDirs["gov_id"], $_POST['old_gov_id'] ?? null);
    $cv               = uploadFile("cv", $uploadDirs["cv"], $_POST['old_cv'] ?? null);
    $service_record   = uploadFile("service_record", $uploadDirs["service_record"], $_POST['old_service_record'] ?? null);
    $appointment_paper = uploadFile("appointment_paper", $uploadDirs["appointment_paper"], $_POST['old_appointment_paper'] ?? null);
    $tor              = uploadFile("tor", $uploadDirs["tor"], $_POST['old_tor'] ?? null);
    $cs_certificate   = uploadFile("cs_certificate", $uploadDirs["cs_certificate"], $_POST['old_cs_certificate'] ?? null);
    $pds              = uploadFile("pds", $uploadDirs["pds"], $_POST['old_pds'] ?? null);

    $password = !empty($_POST['password']) && $_POST['password'] !== "********"
        ? md5($_POST['password'])
        : null;


    if ($password) {
        $sql = "UPDATE employees SET 
        firstname=?, lastname=?, password=?, mobile_number=?, email=?, date_of_birth=?, gender=?, nationality=?, 
        address=?, city=?, state=?, zip_code=?, marital_status=?, employee_id=?, designation=?, employee_type=?, 
        salary_grade=?, department=?, cs_eligibility=?, working_days=?, joining_date=?, 
        avatar=?, government_id=?, cv=?, service_record=?, appointment_paper=?, tor=?, cs_certificate=?, pds=? 
        WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            str_repeat("s", 29) . "i",
            $firstname,
            $lastname,
            $password,
            $mobile_number,
            $email,
            $date_of_birth,
            $gender,
            $nationality,
            $address,
            $city,
            $state,
            $zip_code,
            $marital_status,
            $employee_id,
            $designation,
            $employee_type,
            $salary_grade,
            $department,
            $cs_eligibility,
            $working_days,
            $joining_date,
            $avatar,
            $gov_id,
            $cv,
            $service_record,
            $appointment_paper,
            $tor,
            $cs_certificate,
            $pds,
            $id
        );
    } else {
        $sql = "UPDATE employees SET 
        firstname=?, lastname=?, mobile_number=?, email=?, date_of_birth=?, gender=?, nationality=?, 
        address=?, city=?, state=?, zip_code=?, marital_status=?, employee_id=?, designation=?, employee_type=?, 
        salary_grade=?, department=?, cs_eligibility=?, working_days=?, joining_date=?, 
        avatar=?, government_id=?, cv=?, service_record=?, appointment_paper=?, tor=?, cs_certificate=?, pds=? 
        WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            str_repeat("s", 28) . "i",
            $firstname,
            $lastname,
            $mobile_number,
            $email,
            $date_of_birth,
            $gender,
            $nationality,
            $address,
            $city,
            $state,
            $zip_code,
            $marital_status,
            $employee_id,
            $designation,
            $employee_type,
            $salary_grade,
            $department,
            $cs_eligibility,
            $working_days,
            $joining_date,
            $avatar,
            $gov_id,
            $cv,
            $service_record,
            $appointment_paper,
            $tor,
            $cs_certificate,
            $pds,
            $id
        );
    }


    if ($stmt->execute()) {
        header("Location: employee.php?msg=updated");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
