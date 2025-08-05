<?php
session_start();
require '../connect_database/conn_database.php'; 

$role = $_GET['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
    $password = $_POST['password'] ?? '';


       $stmt = $mysqli->prepare("SELECT * FROM users WHERE first_name = ? AND last_name = ?");
    $stmt->bind_param("ss", $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($user = $result->fetch_assoc()) {
            $hashed_password = $user['password']; 

       if (password_verify($password, $hashed_password)) {  
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = $role;
            $_SESSION['username'] = $first_name . ' ' . $last_name;

            header("Location: ../connect_database/index.php");
            exit;
        } else {
            $error = "Λανθασμένος κωδικός.";
        }
    } else {
        $error = "Δεν βρέθηκε χρήστης με αυτά τα στοιχεία.";
    }


}

?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Login - <?= htmlspecialchars($role) ?></title>
</head>
<body>
    <h2>Σύνδεση ως <?= ucfirst($role) ?></h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
  <label>First Name: <input type="text" name="first_name" required></label><br>
  <label>Last Name: <input type="text" name="last_name" required></label><br>
  <label>Password: <input type="password" name="password" required></label><br>
  <button type="submit">Σύνδεση</button>
</form>

</body>
</html>
