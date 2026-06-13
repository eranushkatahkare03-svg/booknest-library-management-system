<?php
session_start();

$studentId = $_SESSION["library_number"] ?? null;
if (!$studentId) {
    echo "You must be logged in to view your wishlist.";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "1234";
$database = "booknest";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Join wishlist and book_details tables
$query = "SELECT b.BOOK_ID, b.BOOK_NAME, b.LOCATION, b.IMG 
          FROM wishlist w
          JOIN book_details b ON w.book_id = b.BOOK_ID
          WHERE w.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Wishlist</title>
    
    <link rel="stylesheet" href="wishlist.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link rel="stylesheet" href="styles.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link href="https://fonts.googleapis.com/css2?family=Omnibus+Type&display=swap" rel="stylesheet">
<style>
  .remove-btn {
    background: transparent;
    border: none;
    color: #d9534f;
    cursor: pointer;
    margin-top: 8px;
    font-size: 18px;
}
.remove-btn:hover {
    color: #c9302c;
}

  </style>

</head>
<body>
<nav class="navbar">
      <div class="logo">
        <img src="final_logo.png" alt="BookNest Logo" />
        <span class="brand-name">BookNest</span>
      </div>
      <ul class="nav-links">
        <li><a href="main.html">Home</a></li>
        <li><a href="cat.html">Category</a></li>
        <li><a href="ebooks.html">E-Books</a></li>
        <li>
          <div class="search-bar">
            <input type="text" placeholder="Search books..." />
            <button><i class="fas fa-search"></i></button>
          </div>
        </li>
        <li>
          <a href="wish.html"><i class="fas fa-heart"></i></a>
        </li>
        <li>
          <a href="profile.html"><i class="fas fa-user"></i></a>
        </li>
      </ul>
    </nav>
    <br>
    <br>
    <br>
    <br> <br>
  
    <div class="carousel-container">
        <div class="carousel" id="wishlist-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-card" onclick='openModal(<?= json_encode($row) ?>)'>
                    <div class="book-container">
                        <img src="<?= htmlspecialchars($row['IMG']) ?>" alt="<?= htmlspecialchars($row['BOOK_NAME']) ?>" class="book-image"/>
                        <div class="book-info">
                            <h3 class="book-title"><?= htmlspecialchars($row['BOOK_NAME']) ?></h3>
                      
                            <button class="issue-btn" onclick="event.stopPropagation(); openScanner()">Issue Book</button>
                            <button class="remove-btn" onclick="event.stopPropagation(); removeFromWishlist('<?= $row['BOOK_ID'] ?>')">
    <i class="fas fa-trash-alt"></i>
</button>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Modal (Optional if already present) -->
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
            document.querySelector('.modal-title').innerText = book.BOOK_NAME;
            document.querySelector('.modal-description span').innerText = book.LOCATION;
            document.getElementById("bookModal").style.display = "block";
        }
        function closeModal() {
            document.getElementById("bookModal").style.display = "none";
        }
    </script>
     <!-- Footer Section -->
     <footer class="footer">
      <div class="footer-container">
        <div class="footer-section about">
          <h3>About Us</h3>
          <p>
            BookNest is your one-stop digital library solution, providing easy
            access to books and library services with an intuitive interface and
            advanced features.
          </p>
        </div>
        <div class="footer-section faq">
          <h3>FAQ</h3>
          <ul>
            <li><a href="#">How to issue a book?</a></li>
            <li><a href="#">What is the fine for late returns?</a></li>
            <li><a href="#">How can I renew a book online?</a></li>
            <li><a href="#">How to access digital books?</a></li>
          </ul>
        </div>
        <div class="footer-section library-info">
          <h3>Library Info</h3>
          <p>
            <strong>Library Hours:</strong> Mon-Fri: 8 AM - 8 PM, Sat: 10 AM - 4
            PM
          </p>
          <p>
            <strong>Location:</strong> Bharati Vidyapeeth Institute of
            Technology
          </p>
        </div>
        <div class="footer-section contact">
          <h3>Contact Us</h3>
          <p><i class="fas fa-phone"></i> +91-9876543210</p>
          <p><i class="fas fa-phone"></i> +91-9123456789</p>
          <p><i class="fas fa-envelope"></i> support@booknest.com</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 BookNest. All Rights Reserved.</p>
      </div>
    </footer>
    <script>
function removeFromWishlist(bookId) {
    if (!confirm("Remove this book from your wishlist?")) return;

    fetch("remove_wishlist.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "book_id=" + encodeURIComponent(bookId)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Book removed from wishlist!");
            location.reload(); // Or dynamically remove the element from DOM
        } else {
            alert("Failed to remove book: " + data.message);
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Something went wrong.");
    });
}
</script>

</body>
</html>
