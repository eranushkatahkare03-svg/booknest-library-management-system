document.addEventListener("DOMContentLoaded", function () {
    fetch("profile.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                window.location.href = "login.html"; // Redirect to login if not logged in
                return;
            }
            console.log("Page loaded!"); // Debugging
            console.log(document.getElementById("membershipId")); // Check if the element exists
            console.log("Session Storage Value:", sessionStorage.getItem("library_number")); 
            console.log(data); // Debugging: Check what the server sends

            // Update user details
            document.getElementById("userName").textContent = data.user.STUD_NAME || "N/A"; // FIXED: Check for null
            document.getElementById("userEmail").textContent = data.user.EMAIL || "N/A";
            let membershipIdElement = document.getElementById("membershipId");
            if (membershipIdElement) {
                let libraryNumber = sessionStorage.getItem("library_number") || "N/A";
                console.log("Stored Library Number:", libraryNumber); // Debugging
                membershipIdElement.textContent = libraryNumber;
            } else {
                console.error("membershipId element not found!");
            }

            // Update assigned books
            const assignedBooksTable = document.getElementById("assignedBooks");
            assignedBooksTable.innerHTML = ""; // Clear previous content
            data.assignedBooks.forEach(book => {
                assignedBooksTable.innerHTML += `<tr><td>${book.book_id}</td></tr>`;
            });

            // Update returned books
            const returnedBooksTable = document.getElementById("returnedBooks");
            returnedBooksTable.innerHTML = ""; // Clear previous content
            data.returnedBooks.forEach(book => {
                returnedBooksTable.innerHTML += `<tr><td>${book.book_id}</td><td>${book.timestamp}</td></tr>`;
            });

            // Update points and fine
            document.getElementById("points").textContent = data.totalPoints;
            document.getElementById("fineAmount").textContent = `${data.totalFine}`;

            // Update level based on points
            const level = data.totalPoints < 50 ? "Low" : data.totalPoints < 150 ? "Mid" : "High";
            document.getElementById("level").textContent = level;
        })
        .catch(error => console.error("Error loading profile data:", error));
});


// Logout function
function logout() {
  fetch("logout.php")
      .then(() => {
          sessionStorage.removeItem("library_number"); // Clear stored library number
          window.location.href = "login.html"; // Redirect to login page
      })
      .catch(error => console.error("Logout error:", error));
}
