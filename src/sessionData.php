<?php
session_start();
error_log("Session: " . print_r($_SESSION, true));

require 'DBConnector.php';
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}



function getLeagueName($conn, $id)
{
    $result = $conn->query("SELECT league_name FROM leagues WHERE league_id = $id");
    $row = $result->fetch_assoc();
    return $row['league_name'] ?? 'Unknown';
}

echo json_encode([
    'username' => $_SESSION['username'],
    'college' => $_SESSION['college'],
    'year_level' => $_SESSION['year_level'],
    'acad_org' => $_SESSION['acad_org'],
    'current_league' => getLeagueName($conn, $_SESSION['current_league']),
    'highest_league' => getLeagueName($conn, $_SESSION['highest_league']),
    'streak' => $_SESSION['streak'],
    'exp' => $_SESSION['exp'],
]);

$conn->close();

?>