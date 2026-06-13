<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php'; // Ensure db.php correctly sets $conn

// Read and decode JSON input
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

// Debug: Log received JSON data
error_log("Received JSON: " . $json_data);

// Validate JSON input
if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON input."]);
    exit;
}

// Extract values safely
$student_id = $data["student_id"] ?? null;
$book_barcode = $data["book_barcode"] ?? null;
$book_price = isset($data["book_price"]) ? floatval($data["book_price"]) : null;

// Debug: Log extracted values
error_log("Extracted Data - Student ID: $student_id, Book Barcode: $book_barcode, Book Price: $book_price");

// Check if required values exist
if (!$student_id || !$book_barcode || $book_price === null) {
    echo json_encode(["success" => false, "message" => "Missing required parameters."]);
    exit;
}

// Check database connection
if (!isset($conn)) {
    echo json_encode(["success" => false, "message" => "Database connection error."]);
    exit;
}

// Get the current fine amount
$query = "SELECT fine FROM user_activity WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$stmt->bind_result($current_fine);
$stmt->fetch();
$stmt->close();

// Ensure $current_fine is a valid number
$current_fine = floatval($current_fine ?? 0);

// Calculate new fine
$new_fine = $current_fine + $book_price;

// Debug: Log fine update
error_log("Updating fine for student_id: $student_id | New fine: $new_fine");

// Update fine amount
$query = "UPDATE user_activity SET fine = ? WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ds", $new_fine, $student_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Fine updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Fine update failed: No rows affected."]);
}

// Close connections
$stmt->close();
$conn->close();
exit;
?>
