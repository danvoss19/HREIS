<?php
require_once '../db.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $employeeId = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if ($employee) {
        echo json_encode($employee);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }
} else {
    echo json_encode(['error' => 'No employee ID provided']);
}
