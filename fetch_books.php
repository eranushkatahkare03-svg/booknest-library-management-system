<?php
header('Content-Type: application/json');
include 'db.php';

// Fetch books from database
$sql = "SELECT book_id,book_name, description, no_copies, location, image FROM book_details";
$result = $conn->query($sql);

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

// Return JSON response
echo json_encode($books);

$conn->close();
?>
