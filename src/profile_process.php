<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    // Redirect if user is not logged in
    header("Location: login.php");
    exit();
}

// Database connection
require 'DBConnector.php';

// Get form data
$user_id = $_SESSION['user_id'];
$college = $_POST['college'];
$year_level = $_POST['year_level'];
$acad_org = $_POST['acad_org'];

// Update user's profile
$sql = "UPDATE users SET college='$college', year_level='$year_level', acad_org='$acad_org' WHERE user_id=$user_id";

if ($conn->query($sql) === TRUE) {
    echo "rahhh";
    header("Location: login.php");
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>