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
$check = $conn->prepare("SELECT * FROM courses WHERE course_name = ? AND owner_id = ?");
$check->bind_param("si", $courseName, $userID);
$check->execute();
$result = $check->get_result();

if ($result && $result->num_rows > 0) {
    header("Location: myCourses.html?alert=error&msg=Course+already+exists.+Please+change+the+name.");
    exit();
} else {
    // Insert course
    $stmt = $conn->prepare("INSERT INTO courses (owner_id, course_name, course_description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userID, $courseName, $courseDesc);

    if ($stmt->execute()) {
        header("Location: myCourses.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$check->close();
$conn->close();
?>