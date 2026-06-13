<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");



// Database connection
$host = "localhost";
$username = "root"; // Change this if needed
$password = "1234";     // Change this if needed
$dbname = "booknest"; // Ensure this database exists

$conn = new mysqli($host, $username, $password, $dbname);

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Sanitize input

$lib_no = $data['lib_no'] ?? '';
$enrollment = $data['enrollment'] ?? '';
$name = $data['name'] ?? '';
$password = $data['password'] ?? '';
$email = $data['email'] ?? '';
$phone = $data['phone'] ?? '';

$n='no';

// Insert into database
$sql = "INSERT INTO student_details(library_number, ENROLLMENT_NO, STUD_NAME, PASSWORD, EMAIL, MOBILE_NO,ASSIGNED_BOOK) VALUES(?, ?, ?, ?, ?, ?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $lib_no, $enrollment, $name, $password, $email, $phone,$n);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL prepare failed: " . $conn->error]);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Registration successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to register"]);
}

// Close connections
$stmt->close();
$conn->close();
?>
