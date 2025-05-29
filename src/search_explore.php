<?php
session_start();
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

// Prepare search safely
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = trim($search);

// Get total count with prepared statement
if ($search !== '') {
    $search_like = "%$search%";
    $total_stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM flashcard_sets fs 
        WHERE fs.is_public = 1 AND fs.owner_id != ? AND fs.name LIKE ?
    ");
    $total_stmt->bind_param("is", $current_user_id, $search_like);
} else {
    $total_stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM flashcard_sets fs 
        WHERE fs.is_public = 1 AND fs.owner_id != ?
    ");
    $total_stmt->bind_param("i", $current_user_id);
}

$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_items = $total_result->fetch_assoc()['count'];
$total_pages = ceil($total_items / $items_per_page);
$total_stmt->close();

// Fetch paginated data with prepared statement
if ($search !== '') {
    $search_like = "%$search%";
    $data_stmt = $conn->prepare("
        SELECT fs.*, COALESCE(u.username, 'Unknown User') as owner_name
        FROM flashcard_sets fs
        LEFT JOIN users u ON fs.owner_id = u.user_id
        WHERE fs.is_public = 1 AND fs.owner_id != ? AND fs.name LIKE ?
        ORDER BY fs.date_created DESC
        LIMIT ?, ?
    ");
    $data_stmt->bind_param("isii", $current_user_id, $search_like, $offset, $items_per_page);
} else {
    $data_stmt = $conn->prepare("
        SELECT fs.*, COALESCE(u.username, 'Unknown User') as owner_name
        FROM flashcard_sets fs
        LEFT JOIN users u ON fs.owner_id = u.user_id
        WHERE fs.is_public = 1 AND fs.owner_id != ?
        ORDER BY fs.date_created DESC
        LIMIT ?, ?
    ");
    $data_stmt->bind_param("iii", $current_user_id, $offset, $items_per_page);
}

$data_stmt->execute();
$result = $data_stmt->get_result();

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

$data_stmt->close();
$conn->close();
?>