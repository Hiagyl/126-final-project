<?php
session_start();
require 'DBConnector.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$userID = $_SESSION['userID'];

$sql = "SELECT course_id, course_name, course_description FROM courses WHERE owner_id = $userID";
$result = $conn->query($sql);

$courses = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

echo json_encode($courses);
$conn->close();
?>