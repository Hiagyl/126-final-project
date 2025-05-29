<?php
session_start();
require 'DBConnector.php';

if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to delete a course.";
    exit();
}

$userID = $_SESSION['userID'];
$courseID = intval($_POST['course_id']); // Sanitize

// Begin transaction
$conn->begin_transaction();

try {
    // First delete related flashcard sets
    $conn->query("DELETE FROM course_flashcard_sets WHERE course_id = $courseID");

    // Then delete the course
    $conn->query("DELETE FROM courses WHERE course_id = $courseID AND owner_id = $userID");

    $conn->commit();
    header("Location: myCourses.html");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>