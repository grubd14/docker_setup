<?php
// Very simple beginner-friendly form handler
// Receives POST from index.html and inserts firstname, lastname, email into Users table

// Database settings - change these if your environment is different
$host = 'db';
$user = 'app_user';
$pass = 'app_pass';
$db   = 'app_db';

// Connect to MySQL
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    // Simple error message for beginners
    die('Database connection failed: ' . mysqli_connect_error());
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values and trim whitespace
    $first = isset($_POST['fname']) ? trim($_POST['fname']) : '';
    $last  = isset($_POST['lname']) ? trim($_POST['lname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    // Basic validation
    if ($first === '' || $last === '' || $email === '') {
        $message = 'Please fill in First name, Last name, and Email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } else {
        // Escape values (simple protection)
        $first_e = mysqli_real_escape_string($conn, $first);
        $last_e  = mysqli_real_escape_string($conn, $last);
        $email_e = mysqli_real_escape_string($conn, $email);

        // Simple INSERT query
        $sql = "INSERT INTO Users (firstname, lastname, email) VALUES ('$first_e', '$last_e', '$email_e')";
        if (mysqli_query($conn, $sql)) {
            $message = 'User added: ' . htmlspecialchars($first) . ' ' . htmlspecialchars($last);
        } else {
            $message = 'Insert error: '