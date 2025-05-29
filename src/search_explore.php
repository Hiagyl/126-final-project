<?php
session_start(); // Make sure session is started
include 'DBConnector.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$current_user_id = $_SESSION['userID'];

$items_per_page = 9;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = $search ?
    "AND fs.name LIKE '%$search%'" : '';

$where_clause = "WHERE fs.is_public = 1 AND fs.owner_id != $current_user_id $search_condition";

// Get total count
$total_query = "SELECT COUNT(*) as count FROM flashcard_sets fs $where_clause";
$total_result = $conn->query($total_query);
$total_items = $total_result->fetch_assoc()['count'];
$total_pages = ceil($total_items / $items_per_page);

// Get paginated data
$sql = "SELECT fs.*, COALESCE(u.username, 'Unknown User') as owner_name 
        FROM flashcard_sets fs 
        LEFT JOIN users u ON fs.owner_id = u.user_id
        $where_clause 
        ORDER BY fs.date_created DESC 
        LIMIT $offset, $items_per_page";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => "Error executing query: " . $conn->error]);
    exit;
}

$sets = [];
while ($row = $result->fetch_assoc()) {
    $sets[] = $row;
}

echo json_encode([
    'sets' => $sets,
    'currentPage' => $page,
    'totalPages' => $total_pages,
    'totalItems' => $total_items
]);
?>
