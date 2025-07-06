<?php
$host = 'localhost';           
$db   = 'app_users';           
$user = 'root';                
$pass = '12345';                     
$charset = 'utf8mb4';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(
        ['error' => 'Database connection Failed',
        'details' => $mysqli->connect_error ]
);
    exit;
}

if (!$mysqli->set_charset($charset)){
    http_response_code(500);
    echo json_encode(['error' => 'Error while setting charset',
        'details' => $mysqli->error]
);
    exit;
}

echo json_encode(['success' => 'Database Connected!']);

?>
