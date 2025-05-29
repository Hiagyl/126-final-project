<?php

require 'DBConnector.php';
header('Content-Type: application/json');

// Ensure timezone is correct for date functions
date_default_timezone_set('Asia/Manila');

// Determine leaderboard type
$type = $_GET['type'] ?? 'weekly';

if ($type === 'weekly') {
    // Fetch total EXP earned this week per user
    $sql = "
        SELECT u.username, weekly.total_exp
        FROM (
            SELECT user_id, SUM(exp_earned) AS total_exp
            FROM exp_log
            WHERE YEARWEEK(time_stamp, 1) = YEARWEEK(CURDATE(), 1)
            GROUP BY user_id
        ) AS weekly
        JOIN users u ON weekly.user_id = u.user_id
        ORDER BY weekly.total_exp DESC
        LIMIT 6
    ";
} else {
    // All-time leaderboard
    $sql = "
        SELECT username, exp AS total_exp
        FROM users
        ORDER BY exp DESC
        LIMIT 6
    ";
}

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit;
}

$leaderboard = [];
while ($row = $result->fetch_assoc()) {
    $leaderboard[] = $row;
}

echo json_encode($leaderboard);
?>