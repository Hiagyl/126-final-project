<?php
session_start();
require 'DBConnector.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to edit a course.";
    exit();
}

// Get form values
$userID = $_SESSION['userID'];
$courseID = $_POST['course_id'];
$courseName = $_POST['courseName'];
$courseDesc = $_POST['courseDesc'];

// Update course
$sql = "UPDATE courses 
        SET course_name = '$courseName', course_description = '$courseDesc' 
        WHERE course_id = $courseID AND owner_id = $userID";

if ($conn->query($sql) === TRUE) {
    header("Location: myCourses.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>