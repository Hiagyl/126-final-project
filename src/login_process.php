<?php
session_start();

require 'DBConnector.php';
// Get submitted data
$username = $_POST['username'];
$password = $_POST['password'];

// Basic query to check credentials
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Successful login
    $user = $result->fetch_assoc();
    $_SESSION['userID'] = $user['user_id'];
    $_SESSION['username'] = $user['username']; 
    $_SESSION['college'] = $user['college'];
    $_SESSION['year_level'] = $user['year_level'];
    $_SESSION['acad_org'] = $user['acad_org'];
    $_SESSION['current_league'] = $user['current_league'];
    $_SESSION['highest_league'] = $user['highest_league'];
    $_SESSION['streak'] = $user['streak'];
    $_SESSION['exp'] = $user['exp'];

    $conn->close();

    header("Location: homepage.html");
    exit();
} else {
    header("Location: login.php?error=1"); // Redirect with error
    exit();
}


?>