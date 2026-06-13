<?php
$host = "127.0.0.1:3306";
$user = "yourusername";
$password = "yourpass";  // Empty password
$dbname = "booknest";

$conn =  mysqli_connect($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully!";
?>