<?php
session_start();
require 'DBConnector.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

if (!isset($_SESSION['current_course_id'])) {
    echo json_encode(["error" => "No course selected."]);
    exit();
}

$courseID = $_SESSION['current_course_id'];

$sql = "SELECT course_name FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(["course_name" => $row['course_name']]);
} else {
    echo json_encode(["error" => "Course not found."]);
}

$stmt->close();
$conn->close();
?>