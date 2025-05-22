<?php
session_start();
require 'DBConnector.php';

$flashcardID = $_POST['flashcard_id'];
$question = $_POST['flashcardQuestion'];
$answer = $_POST['flashcardAnswer'];

$sql = "UPDATE flashcards 
        SET question = '$question', answer = '$answer' 
        WHERE flashcard_id = $flashcardID";

if ($conn->query($sql) === TRUE) {
    header("Location: coursePage.html?course_id=$setID");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>