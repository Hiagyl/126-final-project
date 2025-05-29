<?php
session_start();
require 'DBConnector.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to edit a flashcard set.";
    exit();
}

$setID = $_POST['set_id'];
$name = $_POST['setName'];
$description = $_POST['setDesc'];
$is_public = $_POST['visibility'];

// Prepare update statement
$stmt = $conn->prepare("UPDATE flashcard_sets 
                        SET name = ?, description = ?, is_public = ? 
                        WHERE set_id = ?");

$stmt->bind_param("ssii", $name, $description, $is_public, $setID);

if ($stmt->execute()) {
    header("Location: insideCourse.html");
    exit();
} else {
    echo "Error updating flashcard set: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>