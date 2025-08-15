<?php
// Δημόσιο endpoint - δεν απαιτεί login
require '../connect_database/conn_database.php';

// Παράμετροι GET
$start_date = $_GET['start_date'] ?? null;
$end_date   = $_GET['end_date'] ?? null;
$format     = strtolower($_GET['format'] ?? 'json'); // default json

// Βασικό query
$sql = "SELECT title, date, description FROM announcements WHERE 1=1";

// Προσθήκη φίλτρων ημερομηνίας
$params = [];
$types  = '';

if ($start_date) {
    $sql .= " AND date >= ?";
    $params[] = $start_date;
    $types   .= 's';
}
if ($end_date) {
    $sql .= " AND date <= ?";
    $params[] = $end_date;
    $types   .= 's';
}

$sql .= " ORDER BY date DESC";

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$announcements = [];
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

if ($format === 'xml') {
    header('Content-Type: application/xml; charset=utf-8');
    $xml = new SimpleXMLElement('<announcements/>');
    foreach ($announcements as $a) {
        $item = $xml->addChild('announcement');
        $item->addChild('title', htmlspecialchars($a['title']));
        $item->addChild('date', $a['date']);
        $item->addChild('description', htmlspecialchars($a['description']));
    }
    echo $xml->asXML();
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($announcements, JSON_UNESCAPED_UNICODE);
}
