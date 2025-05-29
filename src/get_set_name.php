<?php
session_start();
require 'DBConnector.php';
header('Content-Type: application/json');

if (!isset($_SESSION['current_set_id'], $_SESSION['userID'])) {
    echo json_encode(["error" => "No flashcard set selected or user not logged in."]);
    exit();
}

$setID = intval($_SESSION['current_set_id']);

// Query to get the set name
$result = $conn->query("SELECT name FROM flashcard_sets WHERE set_id = $setID");

if (!$result || $result->num_rows === 0) {
    echo json_encode(["error" => "Set not found."]);
    exit();
}

$setName = $result->fetch_assoc()['name'];

echo json_encode(["set_name" => $setName]);

$conn->close();
