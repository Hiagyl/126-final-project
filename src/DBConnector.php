<?php
$servername = "sql12.freesqldatabase.com";
$username = "sql12778897";
$password = "EKzcwePnzU";
$dbname = "sql12778897";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>