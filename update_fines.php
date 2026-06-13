user_activity ua
        JOIN student_details s ON ua.user_id = s.library_number
        WHERE ua.action = 'issued'";

$result = $conn->query($sql);

$now = new DateTime();

while ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    $book_id = $row['book_id'];
    $fine = $row['fine'] ?? 0;

    $issue_date = new DateTime($row['issue_date']);
    $due_date = clone $issue_date;
    $due_date->modify('+7 minutes'); // ← For testing, use minutes instead of days

    if ($now > $due_date) {
        // Calculate how many minutes overdue
        $interval = $due_date->diff($now);
        $overdue_minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

        // Calculate total fine based on overdue time
        $new_fine = round($FINE_PER_MIN * $overdue_minutes, 2);

        // Update only if changed
        if ($new_fine != $fine) {
            $update_sql = "UPDATE user_activity SET fine = ? WHERE user_id = ? AND book_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("dss", $new_fine, $user_id, $book_id);
            $stmt->execute();
            $stmt->close();

            error_log("Fine updated for User: $user_id, Book: $book_id, New Fine: $new_fine");
        }
    }
}

$conn->close();
?>

