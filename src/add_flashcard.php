<?php
session_start();
require 'DBConnector.php';

if (!isset($_SESSION['current_set_id'])) {
    echo "No flashcard set selected.";
    exit();
}

$setID = $_SESSION['current_set_id'];
$question = $_POST['flashcardQuestion'];
$answer = $_POST['flashcardAnswer'];

// Use prepared statement to handle special characters like apostrophes
$stmt = $conn->prepare("INSERT INTO flashcards (set_id, question, answer) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $setID, $question, $answer);

if ($stmt->execute()) {
    header("Location: coursePage.html?set_id=$setID");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>