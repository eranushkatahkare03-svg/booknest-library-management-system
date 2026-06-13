<?php
// Get similar books by genre
$genre = $book['genre'];
$sql = "SELECT * FROM books WHERE genre = ? AND id != ? LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $genre, $book_id);
$stmt->execute();
$recommendations = $stmt->get_result();
?>

<?php while ($rec = $recommendations->fetch_assoc()): ?>
    <div class="book">
        <img src="<?= $rec['cover_image']; ?>" alt="<?= $rec['title']; ?>">
        <h4><?= $rec['title']; ?></h4>
        <a href="book.php?id=<?= $rec['id']; ?>">View Book</a>
    </div>
<?php endwhile; ?>