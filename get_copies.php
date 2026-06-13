<?php
include 'db.php';

// Get book copies from the database
$sql = "SELECT UPPER(book_name) AS book_name, no_copies FROM book_details";
$result = $conn->query($sql);

$copies_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $copies_data[$row["book_name"]] = (int) $row["no_copies"]; // Convert to integer in PHP
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json'); // Ensure correct JSON response
echo json_encode(["success" => true, "copies" => $copies_data]);
exit;
?>
