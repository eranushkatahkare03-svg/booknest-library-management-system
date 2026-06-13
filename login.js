document.getElementById("login-form").addEventListener("submit", function (event) {
    event.preventDefault();

    const libraryNumber = document.getElementById("username").value;
    const password = document.getElementById("password").value; // Ensure password is included

    fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ library_number: libraryNumber, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            console.log("Login successful, storing library number...");
            sessionStorage.setItem("library_number", libraryNumber);
            window.location.href = "profile.html"; // Redirect after login
        } else {
            document.getElementById("errorMessage").textContent = data.message;
        }
    })
    .catch(error => console.error("Error:", error));
});
