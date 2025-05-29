<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'DBConnector.php';

$user_id = $_SESSION['user_id'];
$college = $_POST['college'];
$year_level = $_POST['year_level'];
$acad_org = $_POST['acad_org'];

// Use prepared statements to safely update user data
$stmt = $conn->prepare("UPDATE users SET college = ?, year_level = ?, acad_org = ? WHERE user_id = ?");
$stmt->bind_param("sssi", $college, $year_level, $acad_org, $user_id);

if ($stmt->execute()) {
    echo "rahhh";
    header("Location: login.php");
    exit(); // Always exit after header redirect
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>