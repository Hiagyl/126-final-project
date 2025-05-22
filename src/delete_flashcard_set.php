<?php
session_start();
require 'DBConnector.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to delete a flashcard set.";
    exit();
}

// Get form values
$userID = $_SESSION['userID'];
$setID = $_POST['set_id'];

// Delete from course_flashcard_sets (no owner_id check here)
$sql1 = "DELETE FROM course_flashcard_sets WHERE set_id = $setID";
$conn->query($sql1);

// Delete from flashcard_sets with owner check
$sql2 = "DELETE FROM flashcard_sets WHERE set_id = $setID AND owner_id = $userID";

if ($conn->query($sql2) === TRUE) {
    header("Location: insideCourse.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>