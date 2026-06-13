<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
include("db.php"); // should define $conn as a mysqli object

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["student_id"]) || empty($data["student_id"])) {
        echo json_encode(["success" => false, "message" => "Student ID not provided."]);
        exit;
    }

    $student_id = $data["student_id"];

    $sql = "UPDATE booknest.user_activity SET Fine = 0 WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id); // "s" = string

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Fine cleared successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to clear fine."]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
