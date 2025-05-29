<?php
session_start();
require 'DBConnector.php';

$flashcardID = $_POST['flashcard_id'];
$question = $_POST['flashcardQuestion'];
$answer = $_POST['flashcardAnswer'];
$setID = $_SESSION['current_set_id'];

// Prepare update statement
$stmt = $conn->prepare("UPDATE flashcards SET question = ?, answer = ? WHERE flashcard_id = ?");
$stmt->bind_param("ssi", $question, $answer, $flashcardID);

if ($stmt->execute()) {
    // Assuming you want to redirect with course_id — 
    // You may need to set $setID earlier or get it from DB/session
    header("Location: coursePage.html?set_id=$setID");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>