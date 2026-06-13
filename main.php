<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BookNest</title>
    <link rel="stylesheet" href="styles.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link href="https://fonts.googleapis.com/css2?family=Omnibus+Type&display=swap" rel="stylesheet">

  </head>
  
   

      <!-- Navigation Bar -->
    <nav class="navbar" id="nav">
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
        <li><a href="ebooks.html">E-Books</a></li>
        <li>
          <div class="search-bar">
  <input type="text" id="search-bar" placeholder="Search books..." />
  <button onclick="searchBooks()"><i class="fas fa-search"></i></button>
</div>
         
        </li>
        
        <li>
          <a href="wishlist.php"><i class="fas fa-heart"></i> </a>
        </li>
        <li>
          <a href="profile.html"><i class="fas fa-user"></i></a>
        </li>
      </ul>
    </nav>
    
    
  <div class="hero-banner">
        <video class="hero-video" autoplay muted loop id="trigger-video">
            <source src="hero.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
  

        
    <!--Hero content-->

  <div class="hero-content">
    <h2>READ, EXPLORE, DISCOVER</h2>
    <h3>Knowledge is just a click away</h3>
  </div>
  </div>

<div class="carousel-container">
  <div class="carousel" id="book-section">
      <?php
      $conn = new mysqli("localhost", "root", "1234", "booknest");
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      session_start();
$studentId = $_SESSION["library_number"];
      

      $query = "SELECT BOOK_ID, BOOK_NAME, AUTHOR_NAME, NO_COPIES, DESCRIPTION, IMG,LOCATION FROM book_details";
      $result = $conn->query($query);

      if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
            $bookId = $row['BOOK_ID']; 
      ?>

<div class="book-card" onclick='openModal(<?= json_encode($row) ?>)'>
    <div class="book-container">
        <img src="<?= htmlspecialchars($row['IMG']) ?>" alt="<?= htmlspecialchars($row['BOOK_NAME']) ?>" class="book-image"/>
        <div class="book-info">
            <h3 class="book-title"><?= htmlspecialchars($row['BOOK_NAME']) ?></h3>
            <p class="book-location"><strong>Location:</strong> <?= htmlspecialchars($row['LOCATION']) ?></p>
            <a href="example.html">
                <button class="issue-btn" onclick="openScanner()">Issue Book</button>
            </a>

            <!-- Wishlist Button with embedded PHP values -->
            <button onclick='event.stopPropagation(); addToWishlist(<?= $bookId ?>, <?= json_encode($studentId) ?>)'>❤️ Wishlist</button>
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
<script>
 document.addEventListener("DOMContentLoaded", () => {
  fetch(`books.json?t=${new Date().getTime()}`)
      .then(response => response.json())
      .then(booksData => {
          books = booksData; // Store books globally
          console.log("Fetched books:", books);

          // Fetch copies from the database
          return fetch('get_copies.php');
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              books.forEach(book => {
                  const bookTitleUpper = book.title.toUpperCase();
                  if (data.copies[bookTitleUpper] !== undefined) {
                      book.copies = parseInt(data.copies[bookTitleUpper], 10);
                  }
              });
          } else {
              console.error("Failed to fetch copies:", data.message);
          }

  
      })
      .catch(error => console.error("Error loading books:", error));
});

function searchBooks() {
  let query = document.getElementById("search-bar").value.trim().toLowerCase();
  let resultsDiv = document.getElementById("book-section");

  console.log("Search query:", query); // Debugging log

  // Ensure books are loaded before searching
  if (!Array.isArray(books) || books.length === 0) {
      console.error("Books array is empty or not loaded yet.");
      return;
  }
// If search input is empty, display all books
/*    if (query === "") {
      console.log("Search is empty, displaying all books.");
      renderBooks(books);
      return;
  } */

  // Filter books by checking if any string property contains the search term
  let filteredBooks = books.filter(book =>
      Object.values(book).some(value =>
          typeof value === "string" && value.toLowerCase().includes(query)
      )
  );

  console.log("Filtered books:", filteredBooks); // Debugging log

  // Display search results or "No books found"
  if (filteredBooks.length === 0) {
      resultsDiv.innerHTML = "<p>No books found.</p>";
  } else {
      renderBooks(filteredBooks);
  }
}
// Function to render book cards
function renderBooks(books) {
  const bookSection = document.getElementById('book-section');
  bookSection.innerHTML = books.map(book => createBookCard(book)).join('');
}

// Function to create a book card
function createBookCard(book) {
  return `
      <div class="book-card">
          <div class="book-container">
              <img src="${book.img}" alt="${book.BOOK_NAME}" class="book-image" 
                  onclick="openModal('${book.BOOK_NAME}','${book.DESCRIPTION}', ${book.NO_COPIES})">
              <div class="book-info">
                  <h3 class="book-title">${book.BOOK_NAME}</h3>
                  <p class="book-location"><strong>Location:</strong> ${book.location}</p>
                  <a href="example.html">
                      <button class="issue-btn" onclick="openScanner()">Issue Book</button>
                  </a>
                  <button onclick="addToWishlist($row['BOOK_NAME'], \"$studentId\")">❤️</button>

              </div>
          </div>
      </div>
  `;

}

function addToWishlist(bookId, studentId) {
    fetch("add_to_wishlist.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            student_id: studentId,
            book_id: bookId
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => console.error("Error:", error));
}


  </script>

</html>