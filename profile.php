<?php
session_start();
include "db.php"; // Ensure database connection is included

// Check if user is logged in
if (!isset($_SESSION["library_number"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$library_number = $_SESSION["library_number"];

// Fetch user details from student_details table
$query = "SELECT STUD_NAME, EMAIL FROM booknest.student_details WHERE library_number = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $library_number);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

// Fetch assigned books from user_activity table
$query0 = "SELECT book_id FROM booknest.user_activity WHERE user_id = ? AND action = 'issued'";
$stmt0 = $conn->prepare($query0);
$stmt0->bind_param("s", $library_number);
$stmt0->execute();
$result0 = $stmt0->get_result();

$assignedBooks = [];
while ($row = $result0->fetch_assoc()) {
    $assignedBooks[] = $row;
}
$stmt0->close();

// Fetch returned books from user_activity table
$query1 = "SELECT book_id, timestamp FROM booknest.user_activity WHERE user_id = ? AND action = 'returned'";
$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("s", $library_number);
$stmt1->execute();
$result1 = $stmt1->get_result();

$returnedBooks = [];
while ($row = $result1->fetch_assoc()) { // FIXED: using $result1 instead of $result
    $returnedBooks[] = $row;
}
$stmt1->close();

// Fetch total points and fine
$query2 = "SELECT SUM(points) as totalPoints, SUM(fine) as totalFine FROM booknest.user_activity WHERE user_id = ?";
$stmt2 = $conn->prepare($query2);
$stmt2->bind_param("s", $library_number);
$stmt2->execute();
$result2 = $stmt2->get_result();
$stats = $result2->fetch_assoc();
$stmt2->close();

$totalPoints = $stats["totalPoints"] ?? 0;
$totalFine = $stats["totalFine"] ?? 0;

// Return data as JSON
echo json_encode([
    "user" => $user,
    "assignedBooks" => $assignedBooks,
    "returnedBooks" => $returnedBooks,
    "totalPoints" => $totalPoints,
    "totalFine" => $totalFine
]);

$conn->close();
?>
