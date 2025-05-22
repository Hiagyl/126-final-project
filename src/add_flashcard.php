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

$sql = "INSERT INTO flashcards (set_id, question, answer) VALUES ($setID, '$question', '$answer')";

if ($conn->query($sql) === TRUE) {
    header("Location: coursePage.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>