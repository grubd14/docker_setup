<?php
// Database connection info
$servername = "db";
$username = "app_user";
$password = "app_pass";
$dbname = "app_db";

// Connect to database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$first_name = $_POST["fname"];
$last_name = $_POST["lname"];
$email = $_POST["email"];

// Insert data
$sql = "INSERT INTO Users (firstname, lastname, email) VALUES ('$first_name', '$last_name', '$email')";
if (mysqli_query($conn, $sql)) {
    echo "User added: $first_name $last_name ($email)";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>