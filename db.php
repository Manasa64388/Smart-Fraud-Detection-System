<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "smart_fraud_db"; // Make sure this matches your DB name in XAMPP

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>