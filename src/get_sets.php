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

// Revised SQL: Include fs.owner_id in the SELECT clause
$sql = "
    SELECT fs.set_id, fs.name, fs.description, fs.is_public, fs.owner_id, u.username AS owner_name
    FROM course_flashcard_sets cfs
    JOIN flashcard_sets fs ON cfs.set_id = fs.set_id
    JOIN users u ON fs.owner_id = u.user_id
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