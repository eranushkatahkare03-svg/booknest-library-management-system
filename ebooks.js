document.addEventListener("DOMContentLoaded", function () {
    console.log("📌 DOM Loaded! Initializing event listeners...");
  
    const wishlistButtons = document.querySelectorAll(".wishlist-btn");
  
    wishlistButtons.forEach((button) => {
      button.addEventListener("click", function (event) {
        event.stopPropagation();
        addToWishlist(this);
      });
    });
  
    const readBookButtons = document.querySelectorAll(".read-book-btn");
  
    readBookButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const bookLink = button.getAttribute("data-link");
        if (bookLink) {
          window.open(bookLink, "_blank");
        } else {
          console.error("Book link not found!");
        }
      });
    });
  });
  
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
  
    let isBookInWishlist = wishlist.some(
      (item) => item.title === book.title && item.location === book.location
    );
  
    if (isBookInWishlist) {
      alert(`⚠ "${bookTitle}" is already in your wishlist!`);
      return;
    }
  
    wishlist.push(book);
    localStorage.setItem("wishlist", JSON.stringify(wishlist));
  
    alert(`✅ "${bookTitle}" has been successfully added to your wishlist!`);
    console.log(`✅ "${bookTitle}" added to wishlist!`);
  }
  
  function changebg() {
    var navbar = document.getElementById("nav");
    var scrollval = window.scrollY;
    if (scrollval < 550) {
      navbar.classList.remove("bgcolor");
    } else {
      navbar.classList.add("bgcolor");
    }
  }
  
  window.addEventListener("scroll", changebg);
  
  const books = [
    { name: "The Alchemist", author: "Paulo Coelho" },
    { name: "Atomic Habits", author: "James Clear" },
    { name: "Harry Potter", author: "J.K. Rowling" },
    { name: "The Book Thief", author: "Markus Zusak" },
  ];
  
  function displayBooks(filteredBooks) {
    const bookList = document.getElementById("book-list");
    bookList.innerHTML = "";
    filteredBooks.forEach((book) => {
      const bookItem = document.createElement("li");
      bookItem.textContent = `${book.name} by ${book.author}`;
      bookList.appendChild(bookItem);
    });
  }
  
  function searchBooks() {
    const searchInput = document.getElementById("search-bar").value.toLowerCase();
    const bookCards = document.querySelectorAll(".book-card");
  
    bookCards.forEach((card) => {
      const title = card.querySelector(".book-title").textContent.toLowerCase();
      const location = card
        .querySelector(".book-location")
        .textContent.toLowerCase();
  
      if (title.includes(searchInput) || location.includes(searchInput)) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  
    if (!searchInput) {
      bookCards.forEach((card) => (card.style.display = "block"));
    }
  }
  
  document
    .getElementById("search-bar")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        searchBooks();
      }
    });