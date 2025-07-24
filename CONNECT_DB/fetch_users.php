<?php
require 'conn_database.php';

$sql = "SELECT * FROM users ORDER BY user_id DESC LIMIT 5";
$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    echo '<ul>';
    while ($row = $result->fetch_assoc()) {
        echo '<li>' . htmlspecialchars($row['first_name']) . ' '.htmlspecialchars ($row['last_name']) .' - '. htmlspecialchars($row['email']) . ' - ' .
            '<strong>' . htmlspecialchars($row['role']) . '</strong>'. '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>No users found.</p>';
}
?>
