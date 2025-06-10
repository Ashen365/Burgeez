<?php
// Run this once to insert admin (example only)
include('../includes/db.php');

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->execute([$username, $password]);

echo "Admin added!";
?>
