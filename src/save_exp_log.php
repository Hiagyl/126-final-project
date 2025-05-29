<?php
session_start();
require 'DBConnector.php';

// Fetch JSON input
$data = json_decode(file_get_contents("php://input"), true);
$source_id = $data['source_id'] ?? '';
$exp_earned = $data['exp_earned'] ?? 0;
$user_id = $_SESSION['userID'] ?? 0;

if (!$user_id || !$source_id) {
    echo json_encode(["success" => false, "error" => "Missing user or source ID."]);
    exit;
}

// Log EXP gain
$conn->query("INSERT INTO exp_log (source_id, user_id, exp_earned) 
              VALUES ('$source_id', '$user_id', '$exp_earned')");

// Get user's current EXP and highest league
$user_result = $conn->query("SELECT exp, highest_league FROM users WHERE user_id = $user_id");
if (!$user_result || $user_result->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "User not found"]);
    exit;
}
$user = $user_result->fetch_assoc();
$new_exp = (int) $user['exp'] + (int) $exp_earned;

// Determine new current league
$league_sql = "SELECT league_id, league_name, max_trophy FROM leagues 
               WHERE $new_exp >= min_trophy AND $new_exp <= max_trophy 
               LIMIT 1";
$league_result = $conn->query($league_sql);

$league_id = "NULL";
$league_name = "Unranked";
$league_max = 0;

if ($league_result && $league_result->num_rows > 0) {
    $league_row = $league_result->fetch_assoc();
    $league_id = $league_row['league_id'];
    $league_name = $league_row['league_name'];
    $league_max = $league_row['max_trophy'];
}

// Get highest league trophy threshold
$highest_league_id = $user['highest_league'];
$highest_league_max = 0;
if ($highest_league_id !== null) {
    $hl_result = $conn->query("SELECT max_trophy FROM leagues WHERE league_id = $highest_league_id");
    if ($hl_result && $hl_result->num_rows > 0) {
        $highest_league_max = (int) $hl_result->fetch_assoc()['max_trophy'];
    }
}

// If the new league is higher, update highest_league
if ($league_max > $highest_league_max) {
    $highest_league_id = $league_id;
}

// Update user's exp, current_league, and highest_league
$conn->query("UPDATE users 
              SET exp = $new_exp, 
                  current_league = $league_id, 
                  highest_league = $highest_league_id 
              WHERE user_id = $user_id");

// Update session variables
$_SESSION['exp'] = $new_exp;
$_SESSION['current_league'] = $league_id;
$_SESSION['highest_league'] = $highest_league_id;

echo json_encode([
    "success" => true,
    "new_exp" => $new_exp,
    "new_rank" => $league_name
]);
?>