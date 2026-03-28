<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "streetgraphs"; // change this to your DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
