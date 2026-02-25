<?php

$servername = "db";
$username = "app_user";
$password = "app_pass";
$dbname = "app_db"; //
// to create other databases use the root user

$conn = mysqli_connect($servername, $username, $password, $dbname);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// create table
$createTableSql = "CREATE TABLE IF NOT EXISTS Users (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(30)
)";

if (mysqli_query($conn, $createTableSql)) {
    echo "Table created successfully.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

// insert data
$insertSql = "INSERT INTO Users (firstname, lastname, email) VALUES
('Ram', 'Rai', 'ram.rai@mail.com'),
('Sita', 'Sharma', 'sita.sharma@mail.com'),
('Hari', 'Bahadur', 'hari.bahadur@mail.com'),
('Gita', 'Karki', 'gita.karki@mail.com'),
('Anil', 'Thapa', 'anil.thapa@mail.com'),
('Sunita', 'Gurung', 'sunita.gurung@mail.com'),
('Bikash', 'Lama', 'bikash.lama@mail.com'),
('Manish', 'Shrestha', 'manish.shrestha@mail.com'),
('Rina', 'Maharjan', 'rina.maharjan@mail.com'),
('Prakash', 'Bista', 'prakash.bista@mail.com')";

if (mysqli_query($conn, $insertSql)) {
    echo "Data inserted successfully.<br>";
} else {
    echo "Error inserting data: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>
