<?php
require 'db.php';

// Get all books
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>BookNest - Home</title>
</head>

<body>
    <h1>Welcome to BookNest</h1>

    <?php while ($book = $result->fetch_assoc()): ?>
        <div class="book">
            <img src="<?= $book['cover_image']; ?>" alt="<?= $book['title']; ?>">
            <h3><?= $book['title']; ?></h3>
            <p>By <?= $book['author']; ?></p>
            <a href="book.php?id=<?= $book['id']; ?>">View Book</a>
        </div>
    <?php endwhile; ?>

</body>

</html>