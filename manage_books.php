<!DOCTYPE html>
<html>
<head>
    <title>Book Carousel</title>
    <style>
        .carousel-container {
            padding: 20px;
        }
        .book-card {
            width: 200px;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            border-radius: 8px;
            display: inline-block;
            cursor: pointer;
            background-color: #f9f9f9;
        }
        .book-title {
            font-weight: bold;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            width: 400px;
            border-radius: 10px;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 10px; right: 15px;
            font-size: 22px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="carousel-container">
    <div class="carousel" id="book-section">
        <?php
        $conn = new mysqli("localhost", "root", "1234", "booknest");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT BOOK_ID, BOOK_NAME, AUTHOR_NAME, NO_COPIES, DESCRIPTION FROM book_details";
        $result = $conn->query($query);

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
            <div class="book-card" onclick='openModal(<?= json_encode($row) ?>)'>
                <p class="book-title"><?= htmlspecialchars($row['BOOK_NAME']) ?></p>
                <p><strong>Author:</strong> <?= htmlspecialchars($row['AUTHOR_NAME']) ?></p>
                <p><strong>Copies:</strong> <?= $row['NO_COPIES'] ?></p>
            </div>
        <?php
            endwhile;
        else:
            echo "<p>No books available.</p>";
        endif;

        $conn->close();
        ?>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal" id="bookModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 class="modal-title"></h2>
        <p class="modal-copies"><strong>Copies Available:</strong> <span></span></p>
        <p class="modal-description"><strong>Description:</strong> <span></span></p>
    </div>
</div>

<script>
function openModal(book) {
    document.querySelector(".modal-title").textContent = book.BOOK_NAME;
    document.querySelector(".modal-copies span").textContent = book.NO_COPIES;
    document.querySelector(".modal-description span").textContent = book.DESCRIPTION || "No description available";

    document.getElementById("bookModal").style.display = "block";
}

function closeModal() {
    document.getElementById("bookModal").style.display = "none";
}
</script>

</body>
</html>
