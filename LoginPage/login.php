<?php
session_start();

require '../connect_database/conn_database.php'; 

 //$role = $_POST['role'] ?? 'χρήστης';  

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$password = $_POST['password'] ?? '';

 if ($first_name === '' || $last_name === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Όλα τα πεδία είναι υποχρεωτικά.']);
        exit;
    }
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE first_name=? AND last_name=?");    /*proetoimazei  to query(select) me placeholders poy tha eksetasei(firstname kai lastname) alla den pairnei pragmatikes times */ 
    $stmt->bind_param("ss",$first_name,$last_name);/*pairnei tis pragmatikes times pou toy dinontai*/
    $stmt->execute();/*ekteleitai to query me tis times pou exoun oristei sto bind_param*/
    $result =$stmt->get_result();/*pairnei to apotelesma apo to query*/

    if ($user=$result->fetch_assoc()){
            $hashed_password=$user['password']; 

       if (password_verify($password, $hashed_password)) {  
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $first_name . ' ' . $last_name;
  
        error_log("User role: " . $user['role']);

     switch ($user['role']) {
        case 'διδάσκων':
            $redirect='../ProfessorPage/Professor.html';
            break;
        case 'γραμματεία':
            $redirect='../SecretariatPage/Secretariat.html';
            break;
        default:
            $redirect='../StudentPage/student.html';  // Default redirect
            break;
    }

    echo json_encode(['success'=>true,'redirect'=>$redirect]);
    exit;
}  else {
            echo json_encode(['success' => false, 'message' => 'Λανθασμένος κωδικός.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Δεν βρέθηκε χρήστης με αυτά τα στοιχεία.']);
        exit;
    }


}

?>

