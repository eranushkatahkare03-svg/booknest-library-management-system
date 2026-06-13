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

          // Display updated books
          renderBooks(books);
      })
      .catch(error => console.error("Error loading books:", error));
});

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
              <img src="${book.image}" alt="${book.title}" class="book-image" 
                  onclick="openModal('${book.title}','${book.description}', ${book.copies})">
              <div class="book-info">
                  <h3 class="book-title">${book.title}</h3>
                  <p class="book-location"><strong>Location:</strong> ${book.location}</p>
                  <a href="example.html">
                      <button class="issue-btn" onclick="openScanner()">Issue Book</button>
                  </a>
                  <button class="wishlist-btn" data-book-id="${book.id}" onclick="addToWishlist('${book.id}')">
                      <i class="fas fa-heart"></i>
                  </button>
              </div>
          </div>
      </div>
  `;
}

// Function to open a modal with book details
function openModal(title, description, copies) {
  const modal = document.getElementById("bookModal");
  modal.querySelector(".modal-title").innerText = title;
  modal.querySelector(".modal-copies span").innerText = copies;
  modal.querySelector(".modal-description span").innerText = description;

  // Show modal
  modal.style.display = "block";
}

// Function to close the modal
function closeModal() {
  document.getElementById("bookModal").style.display = "none";
}


// Hide all modals initially
document.addEventListener("DOMContentLoaded", function () {
  const modals = document.querySelectorAll(".modal");
  modals.forEach(modal => (modal.style.display = "none"));
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
  if (query === "") {
      console.log("Search is empty, displaying all books.");
      renderBooks(books);
      return;
  }

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
// ✅ Ensure only one event listener per button
wishlistButtons.forEach((button) => {
  button.removeEventListener("click", handleWishlistClick);
  button.addEventListener("click", handleWishlistClick);
});

// ✅ Function to Add to Wishlist & Avoid Duplicate Alerts
function addToWishlist(button) {
  console.log("🚀 addToWishlist() called!");

  let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

  let bookCard = button.closest(".book-card");
  let bookTitle = bookCard.querySelector(".book-title").textContent.trim();
  let bookLocation = bookCard
    .querySelector(".book-location")
    .textContent.trim();
  let bookImage = bookCard.querySelector("img")?.getAttribute("src");

  if (!bookImage) {
    console.error("🚨 Image source is missing!");
    return;
  }

  let book = { title: bookTitle, location: bookLocation, image: bookImage };

  console.log("🔍 Checking if book is in wishlist:", book);

  // ✅ Check if book already exists before adding
  let isBookInWishlist = wishlist.some(
    (item) => item.title === book.title && item.location === book.location
  );

  if (isBookInWishlist) {
    alert(`⚠ "${bookTitle}" is already in your wishlist!`);
    return; // ✅ Stop execution here
  }

  // ✅ Add book to wishlist *only if not already present*
  wishlist.push(book);
  localStorage.setItem("wishlist", JSON.stringify(wishlist));

  alert(`✅ "${bookTitle}" has been successfully added to your wishlist!`);
  console.log(`✅ "${bookTitle}" added to wishlist!`);
}