<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Carousel</title>
    <link rel="stylesheet" href="mag.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link href="https://fonts.googleapis.com/css2?family=Omnibus+Type&display=swap" rel="stylesheet">
  </head>
  <body>
   

    

<!-- Navigation Bar -->
    <nav class="navbar">
      <div class="logo">
        <img
          src="final_logo.png"
          alt="BookNest Logo"
          
        />
         <span class="brand-name">BookNest</span>
      </div>
      <ul class="nav-links">
       <li><a href="main.html">Home</a></li>
        <li><a href="cat.html">Category</a></li>
        <li>
          <div class="search-bar">
            <input type="text" placeholder="Search books..." />
            <button><i class="fas fa-search"></i></button>
          </div>
        </li>
        <li>
          <a href="wish.html"><i class="fas fa-heart"></i> </a>
        </li>
        <li>
          <a href="profile.html"><i class="fas fa-user"></i></a>
        </li>
      </ul>
    </nav>
<br><br>

<div class="hero-section">
      <h1>MAGZINE</h1>
   </div>


   <div class="carousel-container">
    <div class="carousel" id="book-section">
        <?php
        $conn = new mysqli("localhost", "root", "1234", "booknest");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
  
        $query = "SELECT BOOK_ID, BOOK_NAME, AUTHOR_NAME, NO_COPIES, DESCRIPTION, IMG,LOCATION FROM book_details where CATEGORY='MAGAZINE'";
        $result = $conn->query($query);
  
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
  
            <div class="book-card" onclick='openModal(<?= json_encode($row) ?>)'>
            <div class="book-container">
                <img src=<?= htmlspecialchars($row['IMG']) ?> alt=<?= htmlspecialchars($row['BOOK_NAME']) ?> class="book-image"/>
                <div class="book-info">
                    <h3 class="book-title"><?= htmlspecialchars($row['BOOK_NAME']) ?></h3>
                    <p class="book-location"><strong>Location:</strong> <?= htmlspecialchars($row['LOCATION']) ?></p>
                    <a href="example.html">
                        <button class="issue-btn" onclick="openScanner()">Issue Book</button>
                    </a>
                    <button class="wishlist-btn" data-book-id="${book.id}" onclick="addToWishlist(<?= htmlspecialchars($row['BOOK_NAME']) ?>)">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
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


   <!-- Footer Section -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-section about">
      <h3>About Us</h3>
      <p>
        BookNest is your one-stop digital library solution, providing easy access
        to books and library services with an intuitive interface and advanced
        features.
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
        <strong>Library Hours:</strong> Mon-Fri: 8 AM - 8 PM, Sat: 10 AM - 4 PM
      </p>
      <p>
        <strong>Location:</strong> Bharati Vidyapeeth Institute of Technology
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

  </body>
</html>