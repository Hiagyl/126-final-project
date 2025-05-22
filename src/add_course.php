<?php
session_start();
require 'DBConnector.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to add a course.";
    exit();
}

// Get form values
$userID = $_SESSION['userID'];
$courseName = $_POST['courseName'];
$courseDesc = $_POST['courseDesc'];

// Check if course already exists for this user
$check = "SELECT * FROM courses WHERE course_name = '$courseName' AND owner_id = $userID";
$result = $conn->query($check);

if ($result && $result->num_rows > 0) {
    echo "Course already exists. Please choose another name.";
} else {
    // Insert course
    $sql = "INSERT INTO courses (owner_id, course_name, course_description) 
            VALUES ('$userID', '$courseName', '$courseDesc')";

    if ($conn->query($sql) === TRUE) {
        header("Location: myCourses.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>