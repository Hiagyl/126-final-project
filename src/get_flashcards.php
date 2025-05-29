<?php
session_start();
require 'DBConnector.php';
header('Content-Type: application/json');

if (!isset($_SESSION['current_set_id'], $_SESSION['userID'])) {
    echo json_encode(["error" => "No flashcard set selected or user not logged in."]);
    exit();
}

$setID = intval($_SESSION['current_set_id']);
$userID = intval($_SESSION['userID']);

// Get set owner
$ownerRes = $conn->query("SELECT owner_id FROM flashcard_sets WHERE set_id = $setID");
if (!$ownerRes || $ownerRes->num_rows === 0) {
    echo json_encode(["error" => "Set not found."]);
    exit();
}

$ownerID = intval($ownerRes->fetch_assoc()['owner_id']);
$isOwner = ($userID === $ownerID);

// Get flashcards
$cards = [];
$res = $conn->query("SELECT flashcard_id, question, answer FROM flashcards WHERE set_id = $setID");

if ($res) {
    while ($row = $res->fetch_assoc()) {
        $cards[] = $row;  // no need to add is_owner here anymore
    }
    // Return an object with both flashcards and ownership info
    echo json_encode([
        "is_owner" => $isOwner,
        "flashcards" => $cards
    ]);
} else {
    echo json_encode(["error" => "Failed to fetch flashcards."]);
}

$conn->close();
