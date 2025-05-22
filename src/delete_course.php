<?php
session_start();
require 'DBConnector.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to delete a course.";
    exit();
}

// Get form values
$userID = $_SESSION['userID'];
$courseID = $_POST['course_id'];

// Delete course
$sql = "DELETE FROM courses WHERE course_id = $courseID AND owner_id = $userID";

if ($conn->query($sql) === TRUE) {
    header("Location: myCourses.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>