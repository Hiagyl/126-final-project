<?php
session_start();
require 'DBConnector.php';

if (!isset($_SESSION['userID'])) {
    exit("Not logged in");
}

$course_id = $_POST['course_id'] ?? 0;
$set_id = $_POST['set_id'] ?? 0;

if (!$course_id || !$set_id) {
    exit("Missing data");
}

// Check if the set is already added to the course
$check = $conn->query("SELECT * FROM course_flashcard_sets WHERE course_id = $course_id AND set_id = $set_id");

if ($check->num_rows > 0) {
    header("Location: viewCourse.html?alert=error&msg=This+flashcard+set+is+already+saved+in+your+course+folder&set_id=$set_id");


}

// Insert the set into the course
$conn->query("INSERT INTO course_flashcard_sets (course_id, set_id) VALUES ($course_id, $set_id)")
    or exit("Error: " . $conn->error);

header("Location: myCourses.html");
exit;
