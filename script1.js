/* function loadbooks()
  {
    fetch("books.json")  // Replace with "data/books.json" if it's in a folder
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json(); // Convert response to JSON
    })
    .then(data => {
      console.log("📚 Books loaded:", data); // Check if data is fetched
      displayBooks(data); // Call function to show books
    })
    .catch(error => console.error("❌ Error fetching books:", error));

  } */










