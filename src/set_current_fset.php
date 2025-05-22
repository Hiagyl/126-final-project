<?php
session_start();

if (isset($_POST['set_id'])) {
    $_SESSION['current_set_id'] = $_POST['set_id'];
    echo "Set ID saved.";
} else {
    echo "Set ID not provided.";
}
?>