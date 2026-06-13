<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "1234"; // Update if needed
$database = "booknest";
$conn = new mysqli($servername, $username, $password, $database);

// Include PHPMailer files
require __DIR__ . '/../phpmailer/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../phpmailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../phpmailer/PHPMailer-master/src/SMTP.php';

$data = json_decode(file_get_contents("php://input"), true);
$studentID = $data["student_id"] ?? null;


$emailQuery = "SELECT email FROM student_details WHERE library_number = ?";
$stmt = $conn->prepare($emailQuery);

if (!$stmt) {
    die(json_encode(["success" => false, "message" => "SQL Error: " . $conn->error]));
}

// Bind parameters (use "s" if library_number is a string)
$stmt->bind_param("s", $studentID);

// Execute the query
$stmt->execute();

// Get result
$result = $stmt->get_result();

// Fetch email
if ($row = $result->fetch_assoc()) {
    $email = $row["email"];
    echo json_encode(["success" => true, "email" => $email]);
} else {
    echo json_encode(["success" => false, "message" => "No student found"]);
}

// Close the statement


$stmt->bind_param("i",$studentID);
$studentUpdated = $stmt->execute();


$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'booknest03@gmail.com';  // Your Gmail ID
    $mail->Password = 'kwvu mvvf vxxu jbaq';    // Your 16-character App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email Details
    $mail->setFrom('booknest03@gmail.com', 'BookNest');
    $mail->addAddress($email, 'library User ');  // Change this
    $mail->Subject = 'Test Email from BookNest';
    $mail->Body = 'Hello, book Issued sucessfully!!';

    // Send Email
    if ($mail->send()) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
} catch (Exception $e) {
    echo "❌ Error: {$mail->ErrorInfo}";
}
?>
