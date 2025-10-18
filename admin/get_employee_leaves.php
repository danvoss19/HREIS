<?php
require_once '../db.php';

if (isset($_GET['employee_id'])) {
    $employeeId = $_GET['employee_id'];

    $employeeSql = "SELECT id FROM employees WHERE employee_id = ?";
    $employeeStmt = $conn->prepare($employeeSql);
    $employeeStmt->bind_param("s", $employeeId);
    $employeeStmt->execute();
    $employeeResult = $employeeStmt->get_result();

    if ($employeeResult->num_rows > 0) {
        $employeeData = $employeeResult->fetch_assoc();
        $employeeDbId = $employeeData['id'];

        $sql = "SELECT l.*, e.firstname, e.lastname, e.employee_id 
                FROM leaves l 
                JOIN employees e ON l.leave_user_name = e.id 
                WHERE l.leave_user_name = ? 
                ORDER BY l.leave_start_date DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employeeDbId);
        $stmt->execute();
        $result = $stmt->get_result();

        $leaves = [];
        while ($row = $result->fetch_assoc()) {
            $leaves[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($leaves);
    } else {
        header('Content-Type: application/json');
        echo json_encode([]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}
