<?php
// Database connection
$servername = "db";
$username = "app_user";
$password = "app_pass";
$dbname = "app_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get data from POST
$first_name = isset($_POST["fname"]) ? $_POST["fname"] : "";
$last_name = isset($_POST["lname"]) ? $_POST["lname"] : "";
$email = isset($_POST["email"]) ? $_POST["email"] : "";

// Only insert if required fields are present
if ($first_name && $last_name && $email) {
    $insertSql =
        "INSERT INTO Users (firstname, lastname, email) VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $insertSql);
    mysqli_stmt_bind_param($stmt, "sss", $first_name, $last_name, $email);

    if (mysqli_stmt_execute($stmt)) {
        echo "Welcome $first_name $last_name <br>";
        echo "Email: $email <br>";
        echo "<br>Data inserted successfully!";
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Please fill in all required fields (First Name, Last Name, Email).";
}

mysqli_close($conn);
?>
