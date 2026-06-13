<?php
session_start(); // Start session

if (isset($_SESSION["library_number"])) {
    echo "Session is active! Library Number: " . $_SESSION["library_number"];
} else {
    echo "No active session found.";
}
?>
