<?php
session_start();
header("Content-Type: application/json");

// Database connection (update with your credentials)
$conn = new mysqli("localhost", "root", "", "library_db");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["action"]) || !isset($data["book"])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit();
}

$action = $data["action"];
$bookName = $conn->real_escape_string($data["book"]); // Sanitize input
$userId = $_SESSION["user_id"] ?? null; // Get logged-in user ID

if (!$userId) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

// Handle adding to wishlist
if ($action === "add") {
    // Check if the book is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE user_id = ? AND book_name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("is", $userId, $bookName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Book already in wishlist"]);
        exit();
    }

    // Insert book into wishlist
    $insertQuery = "INSERT INTO wishlist (user_id, book_name) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("is", $userId, $bookName);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Book added to wishlist"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add book"]);
    }
}

// Close connection
$conn->close();
?>
