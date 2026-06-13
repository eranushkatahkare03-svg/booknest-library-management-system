<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database Connection
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "booknest";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get JSON Input
$input = json_decode(file_get_contents("php://input"), true);
$barcode = $input["barcode"] ?? null;

if (!$barcode) {
    echo json_encode(["success" => false, "message" => "Barcode is required"]);
    exit;
}

// Check if Book Exists
$checkQuery = "SELECT * FROM book_details WHERE book_name = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("s", $barcode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Book not found"]);
    exit;
}

// Update Book Status as Lost
$updateQuery = "UPDATE book_details SET status = 'lost', no_copies = no_copies - 1 WHERE book_name = ?";
$stmt2 = $conn->prepare($updateQuery);
$stmt2->bind_param("s", $barcode);
$updated = $stmt2->execute();

if ($updated) {
    echo json_encode(["success" => true, "message" => "Book marked as lost"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update book status", "error" => $stmt2->error]);
}

$stmt->close();
$stmt2->close();
$conn->close();
?>
