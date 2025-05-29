<?php
session_start();
require 'DBConnector.php';

if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to delete a flashcard set.";
    exit();
}

$userID = $_SESSION['userID'];
$setID = $_POST['set_id'];

// Always remove from course_flashcard_sets
$conn->query("DELETE FROM course_flashcard_sets WHERE set_id = $setID");

// Check ownership
$result = $conn->query("SELECT * FROM flashcard_sets WHERE set_id = $setID AND owner_id = $userID");

if ($result->num_rows > 0) {
    // If the user is the owner, delete flashcards and mark set as deleted
    $conn->query("DELETE FROM flashcards WHERE set_id = $setID");
    $conn->query("UPDATE flashcard_sets SET is_deleted = 1 WHERE set_id = $setID");
}

header("Location: insideCourse.html");
exit();
?>