<?php
include '../db.php';

// Leave Type Distribution
$typeQuery = "SELECT leave_type, COUNT(*) as total FROM leaves GROUP BY leave_type";
$typeResult = $conn->query($typeQuery);
$leaveTypes = [];
$leaveTypeCounts = [];
while ($row = $typeResult->fetch_assoc()) {
    $leaveTypes[] = $row['leave_type'];
    $leaveTypeCounts[] = (int)$row['total'];
}

$monthQuery = "SELECT MONTHNAME(leave_start_date) as month, COUNT(*) as total 
               FROM leaves 
               GROUP BY MONTH(leave_start_date)";
$monthResult = $conn->query($monthQuery);
$leaveMonths = [];
$leaveMonthCounts = [];
while ($row = $monthResult->fetch_assoc()) {
    $leaveMonths[] = $row['month'];
    $leaveMonthCounts[] = (int)$row['total'];
}

// Leave Reasons Distribution
$reasonQuery = "SELECT leave_reason, COUNT(*) as total FROM leaves GROUP BY leave_reason";
$reasonResult = $conn->query($reasonQuery);
$leaveReasons = [];
$leaveReasonCounts = [];
while ($row = $reasonResult->fetch_assoc()) {
    $leaveReasons[] = $row['leave_reason'];
    $leaveReasonCounts[] = (int)$row['total'];
}

$statusQuery = "SELECT leave_status, COUNT(*) as total FROM leaves GROUP BY leave_status";
$statusResult = $conn->query($statusQuery);
$leaveStatuses = [];
$leaveStatusCounts = [];
while ($row = $statusResult->fetch_assoc()) {
    $leaveStatuses[] = $row['leave_status'];
    $leaveStatusCounts[] = (int)$row['total'];
}

$conn->close();

echo json_encode([
    "leaveTypes" => $leaveTypes,
    "leaveTypeCounts" => $leaveTypeCounts,
    "leaveMonths" => $leaveMonths,
    "leaveMonthCounts" => $leaveMonthCounts,
    "leaveReasons" => $leaveReasons,
    "leaveReasonCounts" => $leaveReasonCounts,
    "leaveStatuses" => $leaveStatuses,
    "leaveStatusCounts" => $leaveStatusCounts
]);
