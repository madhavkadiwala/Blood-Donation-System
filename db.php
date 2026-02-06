<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "blood_donation_system";

// in include line insert bellow code
$conn = new mysqli("localhost", "root", "", "blood_donation_system");

//$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
