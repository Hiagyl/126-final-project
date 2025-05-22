<?php
session_start();
require 'DBConnector.php';

header('Content-Type: application/json');

if (!isset($_SESSION['current_set_id'])) {
    echo json_encode(["error" => "No flashcard set selected."]);
    exit();
}

$setID = $_SESSION['current_set_id'];

$sql = "SELECT flashcard_id, question, answer FROM flashcards WHERE set_id = $setID";
$result = $conn->query($sql);

$cards = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }
}

echo json_encode($cards);
$conn->close();
?>
