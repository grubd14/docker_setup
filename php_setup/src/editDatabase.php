
<?php
// Example: Edit values in database

// Database connection (replace with your actual credentials)

$servername = "db";
$username = "app_user";
$password = "app_pass";
$dbname = "app_db";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Example data to update
$id = 7; // The ID of the row you want to update
$new_value = "Sushil";

// Prepare and bind
$stmt = $conn->prepare("UPDATE Users SET firstname = ? WHERE id = ?");
$stmt->bind_param("si", $new_value, $id);

// Execute the update
if ($stmt->execute()) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>