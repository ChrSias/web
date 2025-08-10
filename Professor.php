<?php
//session_set_cookie_params(0);
session_start();

require '../connect_database/conn_database.php'; 

 $role = $_POST['role'] ?? 'χρήστης';  

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
    $password = $_POST['password'] ?? '';

 if ($first_name === '' || $last_name === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Όλα τα πεδία είναι υποχρεωτικά.']);
        exit;
    }
       $stmt = $mysqli->prepare("SELECT * FROM users WHERE first_name=? AND last_name=?");
    $stmt->bind_param("ss",$first_name,$last_name);
    $stmt->execute();
    $result =$stmt->get_result();

    if ($user=$result->fetch_assoc()){
            $hashed_password=$user['password']; 

       if (password_verify($password, $hashed_password)) {  
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $first_name . ' ' . $last_name;
  
        
      
         echo json_encode(['success' => true, 'redirect' => '../connect_database/index.php']);
           
    exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Λανθασμένος κωδικός.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Δεν βρέθηκε χρήστης με αυτά τα στοιχεία.']);
        exit;
    }


}

?>

<!--<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
  <title>Login - ?= htmlspecialchars($role) ?></title>
<style>

/*πως θα φαίνεται το popup*/
    .popup_content {
      background-color: #fefefe;
      margin: 10% auto;
      padding: 30px;
      border: 1px solid #888;
      width: 300px;
      border-radius: 10px;
    }
    .close {
      position: relative;
      right: -7px;
      top:-13px;
      color: grey;
      float: right;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .form-group {
      margin-bottom: 15px;
    }
    
     label, input {
      display: block;
      width: 95%;
    }

  
    input {
      padding: 10px;
      margin-top: 5px;
    }
   /* Popup*/
    .submit-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      cursor: pointer;
      font-size: 16px;
      border-radius: 5px;
    }
    #errorMsg {
  color: red;
  margin-top: 10px;
  text-align: center;
}
   
</style>
 </head>
 
 <body>
Login Popup 
<div id="loginpopup" class="popup">
  <div class="popup_content">
                

    the x button to close the popup
    <span class="close" onclick="closepopup()">&times;</span>
    
    <form action="login.php" method="post">
      <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>
      </div>

      <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" required>
      </div>

      <button class="submit-btn" type="submit">Login</button>
    </form>
  </div>
</div>

<script>
  function openpopup() {
    document.getElementById("loginpopup").style.display = "block";
  }

  function closepopup() {
    document.getElementById("loginpopup").style.display = "none";
  }

  window.onclick = function(event) {
    const popup = document.getElementById("loginpopup");
    if (event.target == popup) {
      popup.style.display = "none";
    }
  };
</script>

</body>
</html>

<!--<div id="loginpopup" class="popup">
  <div class="popup_content">
    <span class="close" id="closeLoginPopup">&times;</span>

    <form id="loginForm">
      <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>
      </div>

      <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" required>
      </div>

      <button class="submit-btn" type="submit">Login</button>
      <div id="errorMsg"></div>
    </form>
  </div>
</div>

<script>
  // Όταν κάνω κλικ στο κουμπί κλεισίματος, κλείνει το popup
  document.getElementById('closeLoginPopup').addEventListener('click', () => {
    document.getElementById('loginpopup').remove();
  });

  // Κλείνει το popup  όταν κάνω κλικ έξω από αυτό
  window.addEventListener('click', e => {
    if (e.target.id === 'loginpopup') {
      document.getElementById('loginpopup').remove();
    }
  });

document.addEventListener('submit', function (e) {
  if (e.target.matches('#loginForm')) {
    // works even if form is added dynamically
  
    const formData = new FormData(e.target);


    fetch('../Login/login.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
 window.location.href = data.redirect || '../MainPage/index.php';      }
  else {
        document.getElementById('errorMsg').innerText = data.message || 'Login failed';;
      }
    })
    .catch(() => {
      document.getElementById('errorMsg').innerText = 'Server error. Please try again later.';
    });
  }
  });
</script>-->