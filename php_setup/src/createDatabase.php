<?php
// createDatabase.php
// Very simple script for beginners to create a Users table.
// Usage:
//  - Place this file in your web server directory (same as other project files).
//  - Open it once in your browser (e.g. http://localhost/php_setup/src/createDatabase.php)
//  - Or run from command line: php createDatabase.php
//
// This script connects to the database and creates a Users table
// with three columns: firstname, lastname, email.
// Adjust the database connection settings below if needed.

// Database connection settings (change if your setup is different)
$servername = "db";
$username = "app_user";
$password = "app_pass";
$dbname = "app_db";

// Connect to MySQL
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . PHP_EOL);
}

// SQL to create the Users table
$sql = "
CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

// Run the query
if (mysqli_query($conn, $sql)) {
    echo "Table 'Users' is ready." . PHP_EOL;
} else {
    echo "Error creating table: " . mysqli_error($conn) . PHP_EOL;
}

// Close the connection
mysqli_close($conn);
?>
