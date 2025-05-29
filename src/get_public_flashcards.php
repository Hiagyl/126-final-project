<?php
session_start();
header('Content-Type: application/json');

require 'DBConnector.php';  // assumes $conn is created here

if (!isset($_SESSION['userID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userID = $conn->real_escape_string($_SESSION['userID']);

$sql = "SELECT set_id, name, description, owner_id, date_created
        FROM flashcard_sets
        WHERE is_public = 1 AND is_deleted = 0 AND owner_id != '$userID' 
        ORDER BY date_created DESC";

$result = $conn->query($sql);

$sets = [];
while ($row = $result->fetch_assoc()) {
    $sets[] = $row;
}

echo json_encode($sets);
$conn->close();
