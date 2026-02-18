<?php
$first_name = $_GET['fname'];
$last_name  = $_GET['lname'];
$email      = $_GET['email'];
$address    = $_GET['address'];

$gender  = isset($_GET['gender']) ? $_GET['gender'] : "Not Selected";
$faculty = isset($_GET['faculty']) ? $_GET['faculty'] : "Not Selected";
$hobby = isset($_GET['hobby']) ? $_GET['hobby'] : "Not Selected";

echo "Welcome $first_name $last_name <br>";
echo "Email: $email <br>";
echo "Address: $address <br>";
echo "Gender: $gender <br>";
echo "Faculty: $faculty";
echo "Hobby: $hobby <br>";
?>