<?php
session_start();
require 'DBConnector.php';

if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to add a flashcard set.";
    exit();
}

if (!isset($_SESSION['current_course_id'])) {
    echo "No course selected.";
    exit();
}

$userID = $_SESSION['userID'];
$courseID = $_SESSION['current_course_id'];

$setName = $_POST['setName'];
$setDescription = $_POST['setDesc'];
$is_public = $_POST['visibility'];

// Check for duplicate flashcard set name for this user and course
$checkDuplicate = $conn->prepare("
    SELECT fs.set_id 
    FROM flashcard_sets fs
    JOIN course_flashcard_sets cfs ON fs.set_id = cfs.set_id
    WHERE fs.owner_id = ? AND cfs.course_id = ? AND fs.name = ?
");
$checkDuplicate->bind_param("iis", $userID, $courseID, $setName);
$checkDuplicate->execute();
$result = $checkDuplicate->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $existingSetID = $row['set_id'];
    header("Location: insideCourse.html?alert=error&msg=Flashcard+set+already+exists.+Please+change+the+name.&course_id=$courseID");
    exit();
}
$checkDuplicate->close();

// Insert new flashcard set
$stmt1 = $conn->prepare("INSERT INTO flashcard_sets (owner_id, name, description, is_public) VALUES (?, ?, ?, ?)");
$stmt1->bind_param("isss", $userID, $setName, $setDescription, $is_public);

if ($stmt1->execute()) {
    $setID = $stmt1->insert_id;

    $stmt2 = $conn->prepare("INSERT INTO course_flashcard_sets (course_id, set_id) VALUES (?, ?)");
    $stmt2->bind_param("ii", $courseID, $setID);

    if ($stmt2->execute()) {
        header("Location: insideCourse.html?course_id=$courseID");
        exit();
    } else {
        echo "Error linking set to course: " . $stmt2->error;
    }
    $stmt2->close();
} else {
    echo "Error adding flashcard set: " . $stmt1->error;
}

$stmt1->close();
$conn->close();
?>