<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wishlist</title>
    <link rel="stylesheet" href="wishlist.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
  </head>
  <body>
    <!-- Navigation Bar -->
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

    <!-- Hero Section -->
    <div class="hero-section">
      <h1>WISHLIST</h1>
    </div>

    <!-- Wishlist Container -->
    <div class="wishlist-container">
      <div id="wishlist-items" class="wishlist-books">
        <!-- Books will be displayed here -->
      </div>
    </div>

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
    <script src="wish.js"></script>
  </body>
</html>