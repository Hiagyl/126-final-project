<?php
session_start();
if (isset($_POST['course_id'])) {
    $_SESSION['current_course_id'] = $_POST['course_id'];
    echo "Course ID set successfully.";
} else {
    echo "Course ID not received.";
}
?>
