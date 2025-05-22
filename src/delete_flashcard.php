<?php
session_start();
require 'DBConnector.php';

$flashcardID = $_POST['flashcard_id'];

$sql = "DELETE FROM flashcards WHERE flashcard_id = $flashcardID";

if ($conn->query($sql) === TRUE) {
    header("Location: coursePage.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>