<?php
session_start();
require 'DBConnector.php';

if (!isset($_SESSION['userID']))
    exit("Not logged in");

$course_id = $_POST['course_id'] ?? 0;
$set_id = $_POST['set_id'] ?? 0;

if (!$course_id || !$set_id)
    exit("Missing data");

$conn->query("INSERT INTO course_flashcard_sets (course_id, set_id) VALUES ($course_id, $set_id)")
    or exit("Error: " . $conn->error);

header("Location: myCourses.html");
