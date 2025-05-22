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

// Basic query joining course_flashcard_sets and flashcard_sets
$sql = "
    SELECT fs.set_id, fs.name, fs.description, fs.is_public 
    FROM course_flashcard_sets cfs
    JOIN flashcard_sets fs ON cfs.set_id = fs.set_id
    WHERE cfs.course_id = $courseID
";

$result = $conn->query($sql);

$sets = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sets[] = $row;
    }
}

echo json_encode($sets);

$conn->close();
?>