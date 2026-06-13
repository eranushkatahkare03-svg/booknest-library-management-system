<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "1234"; // Update if needed
$database = "booknest";

// Database Connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Include PHPMailer files
require __DIR__ . '/../phpmailer/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../phpmailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../phpmailer/PHPMailer-master/src/SMTP.php';

// Get JSON Data
$data = json_decode(file_get_contents("php://input"), true);
$studentID = $data["student_id"] ?? null;
$bookName = $data["book_name"] ?? "A book"; // Default if not provided

if (!$studentID) {
    die(json_encode(["success" => false, "message" => "Student ID is required"]));
}

// Fetch Student Email
$emailQuery = "SELECT email FROM student_details WHERE library_number = ?";
$stmt = $conn->prepare($emailQuery);
if (!$stmt) {
    die(json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]));
}

$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $email = $row["email"];
} else {
    die(json_encode(["success" => false, "message" => "No student found with this ID"]));
}
$stmt->close();

// Update Student Activity (If Required)
$updateQuery = "UPDATE user_activity SET status = 'Notified' WHERE library_number = ?";
$stmt = $conn->prepare($updateQuery);
if (!$stmt) {
    die(json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]));
}
$stmt->bind_param("s", $studentID);
$stmt->execute();
$stmt->close();

// Send Email Notification
$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'booknest03@gmail.com';  // Your Gmail ID
    $mail->Password = 'kwvu mvvf vxxu jbaq';  // Your 16-character App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email Details
    $mail->setFrom('booknest03@gmail.com', 'BookNest Library');
    $mail->addAddress($email, 'Library User');
    $mail->Subject = 'Book Issued Successfully';
    $mail->Body = "Hello,\n\nYour book '{$bookName}' has been successfully issued.\n\nBest Regards,\nBookNest Team";

    // Send Email
    if ($mail->send()) {
        echo json_encode(["success" => true, "message" => "Email sent successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to send email"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: {$mail->ErrorInfo}"]);
}

$conn->close();
?>
