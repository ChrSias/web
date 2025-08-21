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
    $title   = $_POST['title'] ?? null;
    $summary = $_POST['summary'] ?? null;

    $pdfPath = null;
    if (!empty($_FILES['pdf_file']['name'])) {
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES['pdf_file']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetFile)) {
            $pdfPath = "uploads/" . $filename;
        }
    }

    if (!$title || !$summary) {
        echo json_encode(['success' => false, 'message' => 'Συμπληρώστε όλα τα απαιτούμενα πεδία.']);
        exit;
    }

    $stmt = $mysqli->prepare("INSERT INTO theses (title, summary, pdf_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $summary, $pdfPath);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Το θέμα καταχωρήθηκε με επιτυχία.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Σφάλμα: ' . $mysqli->error]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Μη έγκυρη ενέργεια.']);
