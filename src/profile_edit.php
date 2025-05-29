<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

require 'DBConnector.php';

$user_id = $_SESSION['userID'];
$username = $_POST['editProfileName'];
$college = $_POST['editCollege'];
$year_level = $_POST['editYearLevel'];
$acad_org = $_POST['editAcadOrg'];

// Check if username exists for a different user
$result = $conn->query("SELECT user_id FROM users WHERE username = '$username' AND user_id != $user_id");

if ($result->num_rows > 0) {
    // echo "Username already taken. Please choose another.";
    exit();
}

// Update user profile
$sql = "UPDATE users SET username='$username', college='$college', year_level='$year_level', acad_org='$acad_org' WHERE user_id=$user_id";

if ($conn->query($sql) === TRUE) {
    $_SESSION['username'] = $username;
    $_SESSION['college'] = $college;
    $_SESSION['year_level'] = $year_level;
    $_SESSION['acad_org'] = $acad_org;

    header("Location: profile.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>