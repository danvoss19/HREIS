<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: employee.php?msg=deleted");
    } else {
        header("Location: employee.php?msg=delete_error");
    }
    exit;
} else {
    header("Location: employee.php?msg=invalid");
    exit;
}
