<?php
require 'conn_database.php';

header('Content-Type: application/json');

$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$password= $_POST['password'] ?? '';
$email= $_POST['email'] ?? '';
$role= $_POST['role'] ?? '';

//Metatrepei to proto gramma thw metavlhthw se kefalaio kai ta ypoloipa peza
$role = trim($role);
$role = mb_strtolower($role, 'UTF-8');


$hashed_password = password_hash($password, PASSWORD_DEFAULT);

//Oles oi metafores ginontai oles mazi, allios akyronontai oles
$mysqli->begin_transaction();

try {
   
    $stmt = $mysqli->prepare("
        INSERT INTO users (first_name, last_name, email, password, role)
        VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) throw new Exception($mysqli->error);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $role);
    if (!$stmt->execute()) throw new Exception($stmt->error);
    $user_id = $stmt->insert_id; 
    $stmt->close();

    $role_normalized= mb_strtolower($role, 'UTF-8');

    switch ($role_normalized) {
        case 'φοιτητής': 
            $stmt2 = $mysqli->prepare("INSERT INTO students (student_id) VALUES (?)");
            break;
        case 'διδάσκων': 
            $stmt2 = $mysqli->prepare("INSERT INTO professors (professor_id) VALUES (?)");
            break;
        case 'γραμματεία': 
            $stmt2 = $mysqli->prepare("INSERT INTO secretariat (secretariat_id) VALUES (?)");
            break;
        default:
            throw new Exception('Invalid role specified.');
    }
    if (!$stmt2) throw new Exception($mysqli->error);
    $stmt2->bind_param("i", $user_id);
    if (!$stmt2->execute()) throw new Exception($stmt2->error);
    $stmt2->close();

    $mysqli->commit();
    echo json_encode(['success' => 'User added successfully!']);

} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(['error' => $e->getMessage()]);
}

$mysqli->close();
exit;
?>
