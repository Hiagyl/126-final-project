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

// Prepare update statement with placeholders
$stmt = $conn->prepare("UPDATE courses 
                        SET course_name = ?, course_description = ? 
                        WHERE course_id = ? AND owner_id = ?");

// Bind parameters: s = string, i = integer
$stmt->bind_param("ssii", $courseName, $courseDesc, $courseID, $userID);

// Execute and check result
if ($stmt->execute()) {
    header("Location: myCourses.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>