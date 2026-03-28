<?php
// run this once to insert a predefined admin
include "includes/db.php";

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();

echo "Admin created successfully";
?>
