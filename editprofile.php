<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Μη εξουσιοδοτημένη πρόσβαση']);
    exit;
}

$conn = new mysqli("localhost","username","password","database");
if ($conn->connect_error) die(json_encode(['error' => 'DB connection failed']));

$username = $_SESSION['username'];
list($first_name, $last_name) = explode(' ', $username, 2);

// Βρες το id του χρήστη από το όνομα
$stmt = $conn->prepare("SELECT id, address, email, mobile, phone FROM users WHERE first_name=? AND last_name=?");
$stmt->bind_param("ss", $first_name, $last_name);
$stmt->execute();
$stmt->bind_result($user_id, $address, $email, $mobile, $phone);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'] ?? '';
    $email   = $_POST['email'] ?? '';
    $mobile  = $_POST['mobile'] ?? '';
    $phone   = $_POST['phone'] ?? '';

    $stmt = $conn->prepare("UPDATE users SET address=?, email=?, mobile=?, phone=? WHERE id=?");
    $stmt->bind_param("ssssi", $address, $email, $mobile, $phone, $user_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true]);
    exit;
}

$conn->close();

// GET request: επιστρέφει τα τρέχοντα στοιχεία
echo json_encode([
    'address' => $address,
    'email' => $email,
    'mobile' => $mobile,
    'phone' => $phone
]);
