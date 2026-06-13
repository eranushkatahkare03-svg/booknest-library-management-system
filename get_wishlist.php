<?php
session_start();
include "db_connection.php"; // Ensure this file connects to your database

$user_id = $_SESSION['user_id']; // Assuming user is logged in

// Fetch wishlist book IDs for the logged-in user
$sql = "SELECT wishlist FROM user_activity WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wishlistBooks = [];
while ($row = $result->fetch_assoc()) {
    $wishlistBooks[] = $row['wishlist']; // Storing book IDs
}

if (!empty($wishlistBooks)) {
    // Fetch book details from book_details using book IDs
    $placeholders = implode(',', array_fill(0, count($wishlistBooks), '?'));
    $sql = "SELECT BOOK_ID, BOOK_NAME, location, image FROM book_details WHERE BOOK_ID IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($wishlistBooks)), ...$wishlistBooks);
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }

    echo json_encode(["success" => true, "wishlist" => $books]);
} else {
    echo json_encode(["success" => false, "message" => "No books in wishlist"]);
}

$stmt->close();
$conn->close();
?>
