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
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
$stmt->bind_param("si", $username, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // echo "Username already taken. Please choose another.";
    exit();
}
$stmt->close();

// Update user profile
$stmt = $conn->prepare("UPDATE users SET username=?, college=?, year_level=?, acad_org=? WHERE user_id=?");
$stmt->bind_param("ssssi", $username, $college, $year_level, $acad_org, $user_id);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $_SESSION['college'] = $college;
    $_SESSION['year_level'] = $year_level;
    $_SESSION['acad_org'] = $acad_org;

    header("Location: profile.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>