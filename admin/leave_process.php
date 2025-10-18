<?php
require_once '../db.php';
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employeeName   = $conn->real_escape_string($_POST['employeeName']);
    /*    $department     = $conn->real_escape_string($_POST['department']); */
    $leaveType      = $conn->real_escape_string($_POST['leaveType']);
    $startDate      = $conn->real_escape_string($_POST['startDate']);
    $endDate        = $conn->real_escape_string($_POST['endDate']);
    $duration       = $conn->real_escape_string($_POST['duration']);
    $resumptionDate = $conn->real_escape_string($_POST['resumptionDate']);
    $reason         = $conn->real_escape_string($_POST['reason']);

    $leaveStatus = "Pending";

    $sql = "INSERT INTO leaves 
        (leave_user_name, leave_duration, leave_start_date, leave_end_date, leave_resumption_date, leave_type, leave_reason, leave_status) 
        VALUES 
        ('$employeeName', '$duration', '$startDate', '$endDate', '$resumptionDate', '$leaveType', '$reason', '$leaveStatus')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Leave application submitted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}

$conn->close();
