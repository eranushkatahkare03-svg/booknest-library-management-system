<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Connection
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "booknest";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get JSON Input
$raw_input = file_get_contents("php://input");
$data = json_decode($raw_input, true);

// Log input data for debugging
file_put_contents("debug_log.txt", "Raw Input: " . $raw_input . PHP_EOL, FILE_APPEND);

// Validate required fields
$book_name = $data["book_name"] ?? null;
$author = $data["author"] ?? null;
$copies = $data["copies"] ?? null;
$price = $data["price"] ?? null;
$img =  $data["img"] ?? null;
$cat=  $data["cat"] ?? null;
$publi= $data["publi"] ?? null;
$dis= $data["dis"] ?? null;
$loc= $data["loc"] ?? null;
if (!$book_name || !$author || !$copies ||!$img ||!$cat ||!$publi ||!$dis || !$price|| !$loc) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

// Prepare SQL statement
$query = "INSERT INTO booknest.book_details (book_name, author_name, no_copies,price,img,category,publication,DESCRIPTION,location) VALUES (?, ?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL prepare failed", "error" => $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param("ssidsssss", $book_name, $author, $copies,$price,$img,$cat,$publi,$dis,$loc);
$executeResult = $stmt->execute();

// Handle execution result
if ($executeResult) {
    echo json_encode(["success" => true, "message" => "Book added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add book", "error" => $stmt->error]);
}

// Close connection
$stmt->close();
$conn->close();
?>
