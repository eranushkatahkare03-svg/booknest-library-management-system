<?php
ob_start(); // Start output buffering to prevent unwanted output
header("Content-Type: application/json"); // Ensure JSON response
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "booknest";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Log raw input for debugging
$raw_input = file_get_contents("php://input");
file_put_contents("debug_log.txt", "Raw Input: " . $raw_input . PHP_EOL, FILE_APPEND); // Store input in a log file

$data = json_decode($raw_input, true);

// Validate JSON decoding

$studentID = $data["student_id"] ?? null;
$book_name = $data["book_barcode"] ?? null;

var_dump($studentID,$book_name);
// Validate required fields
if (!$studentID || !$book_name) {
    echo json_encode(["success" => false, "message" => "Missing student_id or book_name"]);
    exit;
}

// Debug: Log extracted values
file_put_contents("debug_log.txt", "Extracted Data: student_id=$studentID, book_name=$book_name" . PHP_EOL, FILE_APPEND);

$n = 'no';
$null = null;

// Update student table
$updateStudent = "UPDATE booknest.student_details SET assigned_book = ?, barcode = ?, issue_date = ? WHERE library_number = ?";
$stmt = $conn->prepare($updateStudent);
$stmt->bind_param("ssss", $n, $null, $null, $studentID);
$studentUpdated = $stmt->execute();

if (!$studentUpdated) {
    echo json_encode(["success" => false, "message" => "Failed to update student table", "error" => $stmt->error]);
    exit;
}

// Update book details
$updateBooks = "UPDATE booknest.book_details SET no_copies = no_copies + 1 WHERE book_name = ?";
$stmt1 = $conn->prepare($updateBooks);
$stmt1->bind_param("s", $book_name);
$booksUpdated = $stmt1->execute();

if (!$booksUpdated) {
    echo json_encode(["success" => false, "message" => "Failed to update book details", "error" => $stmt1->error]);
    exit;
}
$p=0;
// Update user activity
$updateUA = "UPDATE booknest.user_activity SET action = ?, points = points + 10";
$r = 'returned';
$stmt2 = $conn->prepare($updateUA);
$stmt2->bind_param('s', $r);
$userActivityUpdated = $stmt2->execute();

if (!$userActivityUpdated) {
    echo json_encode(["success" => false, "message" => "Failed to update user activity", "error" => $stmt2->error]);
    exit;
}

// Capture email output and ensure clean JSON response
ob_start();
require 'testmail/send_email_return.php';
$emailOutput = ob_get_clean(); // Store email script output

$response = [
    "success" => true,
    "student_id" => $studentID,
    "message" => "Student and book records updated successfully!",
    "email_output" => $emailOutput
];

// Clean buffer to avoid unwanted output
ob_end_clean();
echo json_encode($response);

// Close connection
$conn->close();
?>
