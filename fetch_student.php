<?php
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php';

// Fetch student data while preventing duplicate values
$query = "
   SELECT DISTINCT
    s.library_number, 
    s.ENROLLMENT_NO, 
    s.STUD_NAME, 
    s.EMAIL, 
    s.MOBILE_NO, 
    COALESCE(u.Fine, 0) AS fine_amount, 
    COALESCE(b.PRICE, 0) AS book_price, 
    s.ASSIGNED_BOOK, 
    s.Barcode 
FROM student_details s
LEFT JOIN user_activity u ON s.library_number = u.user_id
LEFT JOIN book_details b ON s.Barcode = b.Barcode
GROUP BY s.library_number, s.ENROLLMENT_NO, s.STUD_NAME, s.EMAIL, s.MOBILE_NO, s.ASSIGNED_BOOK, s.Barcode, u.Fine, b.PRICE;
";

$result = $conn->query($query);

$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        unset($row["book_price"]);
        $students[] = $row;
    }
}

// Output JSON response
echo json_encode(array_values($students), JSON_PRETTY_PRINT);
exit;
?>
