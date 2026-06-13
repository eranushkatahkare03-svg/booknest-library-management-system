<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require __DIR__ . '/../phpmailer/PHPMailer-master/src/Exception.php';
require __DIR__ . '/../phpmailer/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../phpmailer/PHPMailer-master/src/SMTP.php';


function sendReminderEmail($email, $book) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'booknest03@gmail.com';
        $mail->Password = 'kwvu mvvf vxxu jbaq'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('booknest03@gmail.com', 'BookNest Library');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Reminder: Book Return Due";
        $mail->Body = "Dear User,<br><br>You have borrowed <b>$book</b>. Please return it on time.<br><br>Regards,<br>BookNest Library";

        if ($mail->send()) {
            file_put_contents("email_log.txt", "[" . date("Y-m-d H:i:s") . "] Email sent to: $email, Book: $book\n", FILE_APPEND);
            return true;
        } else {
            file_put_contents("email_log.txt", "[" . date("Y-m-d H:i:s") . "] Failed to send email to: $email\n", FILE_APPEND);
            return false;
        }
    } catch (Exception $e) {
        file_put_contents("email_log.txt", "[" . date("Y-m-d H:i:s") . "] Error sending email to: $email - " . $mail->ErrorInfo . "\n", FILE_APPEND);
        return false;
    }
}

?>