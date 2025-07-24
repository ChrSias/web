<?php
require 'conn_database.php';

header('Content-Type: application/json');  

$first_name = $_POST['first_name'] ?? '';
$last_name  = $_POST['last_name'] ?? '';
$password   = $_POST['password'] ?? '';
$email      = $_POST['email'] ?? '';

$name = trim($first_name . ' ' . $last_name);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(['success' => 'User added!']);
} else {
    echo json_encode(['error' => $stmt->error]);
}

$stmt->close();
$mysqli->close();
exit;
