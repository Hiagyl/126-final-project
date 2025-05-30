<?php
session_start();
require 'DBConnector.php';

// Get form values
$username = $_POST['username'];
$password = $_POST['password'];

// Check if username already exists
$check = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
    header("Location: signup.php?alert=error&msg=User+already+exists.+Please+change+the+username.");
} else {
    // Insert user into database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Store the new user's ID in session
        $_SESSION['user_id'] = $conn->insert_id;

        // Redirect to profile setup
        header("Location: profile_setup.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>