<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$servername = "localhost";
$username = "root";
$password = "1234";
$database = "booknest";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$studentId = $data["student_id"] ?? null;
$bookId = $data["book_id"] ?? null;

if (!$studentId || !$bookId) {
    echo json_encode(["success" => false, "message" => "Missing data"]);
    exit;
}

// Check if already added
$check = $conn->prepare("SELECT * FROM wishlist WHERE student_id = ? AND book_id = ?");
$check->bind_param("si", $studentId, $bookId);
$check->execute();
$result = $check->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Already in wishlist"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO wishlist (student_id, book_id) VALUES (?, ?)");
$stmt->bind_param("si", $studentId, $bookId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Book added to wishlist"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add", "error" => $stmt->error]);
}

$conn->close();
?>
