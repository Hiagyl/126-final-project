<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

echo json_encode(["user_id" => $_SESSION['userID']]);
?>