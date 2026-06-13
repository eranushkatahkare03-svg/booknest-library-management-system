<?php
session_start();
header('Content-Type: application/json');

$studentId = $_SESSION["library_number"] ?? null;
if (!$studentId) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

if (!isset($_POST['book_id'])) {
    echo json_encode(["success" => false, "message" => "Missing book ID"]);
    exit;
}

$bookId = $_POST['book_id'];

$conn = new mysqli("localhost", "root", "1234", "booknest");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM wishlist WHERE student_id = ? AND book_id = ?");
$stmt->bind_param("ss", $studentId, $bookId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Could not remove book"]);
}
?>
