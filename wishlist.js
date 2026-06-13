 function displayWishlist() {
document.innerHtml = `
        <div class="wishlist-book-card">
          <img src="${book.image}" alt="${book.title}" class="wishlist-book-image""> 
          <div class="wishlist-book-info">
            <h3>${book.title}</h3>
            <p><strong></strong> ${book.location}</p>
            <button class="remove-btn" onclick="removeFromWishlist(${index})">Remove</button>
          </div>
        </div>
      `;
   
}
document.addEventListener("DOMContentLoaded", displayWishlist);

function removeFromWishlist(index) {
  console.log(`🗑 Removing book at index: ${index}`);

  let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

  if (index < 0 || index >= wishlist.length) {
    console.error("🚨 Invalid index! Book not found.");
    return;
  }

  // Remove book from array
  wishlist.splice(index, 1);

  // Update localStorage
  localStorage.setItem("wishlist", JSON.stringify(wishlist));

  console.log("✅ Book removed. Updated Wishlist:", wishlist);

  // Re-display the wishlist to reflect changes
  displayWishlist();
} 

  