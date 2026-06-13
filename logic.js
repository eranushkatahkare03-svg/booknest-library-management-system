document.addEventListener("DOMContentLoaded", function () {
    const bookCard = document.querySelector(".book-card");
    const modal = document.getElementById("bookModal");
    const closeBtn = document.querySelector(".close-btn");
  
    // Show Modal on Hover
    bookCard.addEventListener("mouseover", function () {
      modal.style.display = "flex";
    });
  
    // Hide Modal on Clicking Close Button
    closeBtn.addEventListener("click", function () {
      modal.style.display = "none";
    });
  
    // Hide Modal on Clicking Outside
    window.addEventListener("click", function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    let scannerInput = document.getElementById("scannerInput");

    scannerInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            let scannedCode = scannerInput.value.trim();
            scannerInput.value = ""; // Clear input after reading

            // Send scanned code to your JS function
            processScannedData(scannedCode);
        }
    });

    // Ensure input is always focused to capture scanner data
    document.addEventListener("click", () => scannerInput.focus());
});

function processScannedData(scannedCode) {
    console.log("Scanned Code:", scannedCode);

    // Example: Search for book with scanned code
    fetchBookByBarcode(scannedCode);
}

function fetchBookByBarcode(barcode) {
    fetch(`/searchBook?barcode=${barcode}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Book Found: ${data.bookTitle} (Shelf: ${data.shelfLocation})`);
            } else {
                alert("Book not found!");
            }
        })
        .catch(error => console.error("Error:", error));
}


  function issueBook(barcode) {
    fetch("/issueBook", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ barcode: barcode, userId: "12345" }) // Example User ID
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Book Issued: ${data.bookTitle}`);
        } else {
            alert("Error issuing book!");
        }
    })
    .catch(error => console.error("Error:", error));
}


  function highlightBook(bookTitle) {
    let books = document.querySelectorAll(".book-card h3");
    books.forEach((book) => {
        if (book.textContent.trim() === bookTitle.trim()) {
            book.parentElement.parentElement.style.border = "3px solid red"; // Highlight book
        }
    });
}


if (data.success) {
    highlightBook(data.bookTitle);
}
