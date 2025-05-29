<?php
// Include your DB connection
require_once 'DBConnector.php'; // Update this if your DB file has a different name

header('Content-Type: application/json');

if (!isset($_GET['set_id']) || empty($_GET['set_id'])) {
    echo json_encode(['error' => 'Missing set_id']);
    exit;
}

$set_id = intval($_GET['set_id']);

$sql = "SELECT name FROM flashcard_sets WHERE set_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $set_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['set_name' => $row['name']]);
} else {
    echo json_encode(['error' => 'Set not found']);
}

$stmt->close();
$conn->close();
?>