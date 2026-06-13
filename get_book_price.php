<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php'; // Ensure this defines $conn

// Validate input
if (!isset($_GET["barcode"])) {
    echo json_encode(["success" => false, "message" => "Missing barcode parameter."]);
    exit;
}

$barcode = $_GET["barcode"];

// Prepare the SQL query
$query = "SELECT price FROM book_details WHERE book_name = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL error: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $barcode);
$stmt->execute();
$stmt->bind_result($book_price);
$stmt->fetch();
$stmt->close();

// Ensure book_price is valid
$book_price = floatval($book_price ?? 0);

// Return JSON response
echo json_encode(["success" => true, "book_price" => $book_price]);
$conn->close();
?>
