<?php
session_start();

if ($_SESSION['loggedIn'] !== true) {
    header("Location: index.php");
    exit;
}

require 'connection.php';

if ($_SESSION['role'] === 'admin' && isset($_POST['gardenId'])) {
    $gardenId = $_POST['gardenId'];

    $sql = "DELETE FROM tbl_231_gardens WHERE id = '$gardenId'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
