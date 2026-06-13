<?php
include 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable JSON response headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

// Read and decode JSON input
$data = json_decode(file_get_contents("php://input"), true);
//error_log("Raw Input: " . $data);

if ($data === null) {
    error_log("JSON Decoding failed: " . json_last_error_msg());
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid JSON format"]);
    exit;
}

// Extract variables securely
$lib_no = $data['lib_no'] ?? null;
$password = $data['password'] ?? null;

error_log("Extracted: lib_no = " . ($lib_no ?: 'NULL') . ", password = " . ($password ?: 'NULL'));

if (!$lib_no || !$password) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Library Number or Password missing."]);
    exit;
}

// Prepare SQL query
$sql = "SELECT library_number FROM STUDENT_DETAILS WHERE library_number = ? AND PASSWORD = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("SQL Prepare Error: " . $conn->error);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$stmt->bind_param("ss", $lib_no, $password);
if (!$stmt->execute()) {
    error_log("SQL Execution Error: " . $stmt->error);
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database query failed"]);
    exit;
}

$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $_SESSION["library_number"] = $lib_no;
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Login successful!"]);
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid Library Number or Password."]);
}

// Close resources
$stmt->close();
$conn->close();
?>
