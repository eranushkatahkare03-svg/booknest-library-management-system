document.addEventListener("DOMContentLoaded", function () {
    const books = [
        { id: 1, title: "Java Programming", category: "department-books", msbte: false },
        { id: 2, title: "Python for Beginners", category: "department-books", msbte: false },
        { id: 3, title: "Tech Monthly", category: "magazines", msbte: false },
        { id: 4, title: "MSBTE Java Guide", category: "msbte-books", msbte: true }
    ];
    
    function renderBooks() {
        books.forEach(book => {
            const categorySection = document.getElementById(book.category);
            if (categorySection) {
                const bookList = categorySection.querySelector(".book-list");
                const bookCard = document.createElement("div");
                bookCard.classList.add("book-card");
                
                bookCard.innerHTML = `
                    <h3>${book.title} ${book.msbte ? '✅' : ''}</h3>
                    <button class="issue-btn" data-id="${book.id}">Issue Book</button>
                    <button class="wishlist-btn" data-id="${book.id}">❤️ Wishlist</button>
                    <button class="view-location-btn" data-id="${book.id}">View Location</button>
                `;
                
                bookList.appendChild(bookCard);
            }
        });
    }

    // JavaScript to handle Location Pop-up
document.addEventListener("DOMContentLoaded", function () {
    const locationButtons = document.querySelectorAll(".location-btn");
    const modal = document.getElementById("locationModal");
    const closeBtn = document.querySelector(".close-btn");

    locationButtons.forEach(button => {
        button.addEventListener("click", function () {
            modal.style.display = "flex";
        });
    });

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});


    function handleIssueBook(event) {
        if (event.target.classList.contains("issue-btn")) {
            event.target.disabled = true;
            event.target.textContent = "Issued";
        }
    }

    function handleWishlist(event) {
        if (event.target.classList.contains("wishlist-btn")) {
            alert("Added to Wishlist!");
        }
    }

    function handleViewLocation(event) {
        if (event.target.classList.contains("view-location-btn")) {
            const bookId = event.target.dataset.id;
            alert(`Showing location for Book ID: ${bookId}`);
        }
    }
    
    document.body.addEventListener("click", function (event) {
        handleIssueBook(event);
        handleWishlist(event);
        handleViewLocation(event);
    });
    
    renderBooks();
});