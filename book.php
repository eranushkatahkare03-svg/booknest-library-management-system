<?php
require 'db.php';

// Get book ID from URL
$book_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch book details
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

// Increase view count
$conn->query("UPDATE books SET views = views + 1 WHERE id = $book_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $book['title']; ?></title>
</head>

<body>
    <h1><?= $book['title']; ?></h1>
    <img src="<?= $book['cover_image']; ?>" alt="<?= $book['title']; ?>">
    <p>By <?= $book['author']; ?></p>
    <p><?= $book['description']; ?></p>

    <h2>Recommended Books:</h2>
    <?php include 'recommendations.php'; ?>

</body>

</html>