<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE leaves SET leave_status = '$status' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Leave status updated to $status"]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
}
$conn->close();
