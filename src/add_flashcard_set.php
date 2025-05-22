<?php
session_start();
require 'DBConnector.php';

if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to add a flashcard set.";
    exit();
}

if (!isset($_SESSION['current_course_id'])) {
    echo "No course selected.";
    exit();
}

$userID = $_SESSION['userID'];
$courseID = $_SESSION['current_course_id'];

$setName = $_POST['setName'];
$setDescription = $_POST['setDesc'];
$is_public = $_POST['visibility'];

// Insert into flashcard_sets
$sql1 = "INSERT INTO flashcard_sets (owner_id, name, description, is_public) 
         VALUES ('$userID', '$setName', '$setDescription', '$is_public')";

if ($conn->query($sql1) === TRUE) {
    $setID = $conn->insert_id; // Get ID of newly inserted set

    // Link to course
    $sql2 = "INSERT INTO course_flashcard_sets (course_id, set_id) 
             VALUES ('$courseID', '$setID')";

    if ($conn->query($sql2) === TRUE) {
        header("Location: insideCourse.html?course_id=$courseID");
        exit();
    } else {
        echo "Error linking set to course: " . $conn->error;
    }
} else {
    echo "Error adding flashcard set: " . $conn->error;
}

$conn->close();
?>