<?php
require '../connect_database/conn_database.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'list') {
    $result = $mysqli->query("SELECT * FROM theses ORDER BY id DESC");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);
    exit;
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  //  $topic_id       = $_POST['topic_id'] ?? null;
    $student_id     = $_POST['student_id'] ?? null;
    $supervisor_id  = $_POST['supervisor_id'] ?? null;
    $committee_id   = $_POST['committee_id'] ?? null;
    $status         = $_POST['status'] ?? null;
    $grade          = $_POST['grade'] ?? null;
    $start_date     = $_POST['start_date'] ?? null;
    $completion_date= $_POST['completion_date'] ?? null;

    if (!$topic_id || !$student_id || !$supervisor_id || !$committee_id || !$status || !$start_date) {
        echo json_encode(['success' => false, 'message' => 'Συμπληρώστε όλα τα απαιτούμενα πεδία.']);
        exit;
    }

    $stmt = $mysqli->prepare("INSERT INTO theses (topic_id, student_id, supervisor_id, committee_id, status, grade, start_date, completion_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iiisdss",
     //   $topic_id,
        $student_id,
        $supervisor_id,
        $committee_id,
        $status,
        $grade,
        $start_date,
        $completion_date
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Το θέμα καταχωρήθηκε με επιτυχία.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Σφάλμα κατά την καταχώρηση: ' . $mysqli->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Μη έγκυρη ενέργεια.']);
