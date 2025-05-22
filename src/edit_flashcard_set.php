<?php
session_start();
require 'DBConnector.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to edit a flashcard set.";
    exit();
}

// $userID = $_SESSION['userID'];
$setID = $_POST['set_id'];
$name = $_POST['setName'];
$description = $_POST['setDesc'];
$is_public = $_POST['visibility'];

// Simple update query (no strict validation)
$sql = "UPDATE flashcard_sets 
        SET name = '$name', description = '$description', is_public = $is_public 
        WHERE set_id = $setID";

if ($conn->query($sql) === TRUE) {
    header("Location: insideCourse.html");  // Redirect wherever you want
    exit();
} else {
    echo "Error updating flashcard set: " . $conn->error;
}

$conn->close();
?>