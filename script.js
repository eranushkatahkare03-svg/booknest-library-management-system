// Switching between Login and Signup forms
document.getElementById("signup-link").addEventListener("click", function (e) {
  e.preventDefault();
  document.getElementById("login-box").classList.add("hidden");
  document.getElementById("signup-box").classList.remove("hidden");
});

document.getElementById("login-link").addEventListener("click", function (e) {
  e.preventDefault();
  document.getElementById("signup-box").classList.add("hidden");
  document.getElementById("login-box").classList.remove("hidden");
});

//handel registration 
document.getElementById("registerBtn").addEventListener("click", function (e) {
  e.preventDefault(); // Prevent default form submission

  // Get form values
  const data = {
      lib_no:document.getElementById("lib_no").value,
      enrollment:document.getElementById("enrollment").value,
      name:document.getElementById("name").value,
      password:document.getElementById("p1").value,
      email:document.getElementById("email").value,
      phone:document.getElementById("phone").value
  };

  console.log("Data being sent:", data);
  console.log("JSON Payload:", JSON.stringify(data));

  fetch("http://localhost/BookNest/r.php", {
      method: "POST", 
      headers: {
          "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(result => {
      console.log("Server Response:", result);
      alert(result.message); // Show response message
     
  })
  .catch(error => console.error("Error:", error));
});

//handel Login
document.getElementById("loginButton").addEventListener("click", function (e) {
  lib=document.getElementById("username").value;
  pass=document.getElementById("password").value;
  e.preventDefault(); // Prevent default form submission
const data = {
  lib_no:document.getElementById("username").value,
  password:document.getElementById("password").value,
  
};

if(lib=='bvit'&& pass=='lib_staff')
{
  window.location.href='studdata.html';
}
else{

console.log("Data being sent:", data);
console.log("JSON Payload:", JSON.stringify(data));

fetch("http://localhost/BookNest/login.php", {
  method: "POST", // Must match PHP handling
  headers: {
      "Content-Type": "application/json"
  },
  body: JSON.stringify(data)
})
.then(response => response.json())
.then(result => {
  console.log("Server Response:", result);
  alert(result.message); 
 
  if (result.status == "success") {
    window.location.href="main.php"; 

  }
})
.catch(error => console.error("Error:", error));
}
});


