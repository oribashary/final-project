<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dogId = isset($_POST['dogId']) ? $_POST['dogId'] : '';

    if (empty($dogId)) {
        echo 'Invalid dogId.';
        exit;
    }

    $sql = "DELETE FROM tbl_231_dogs WHERE id = '$dogId'";
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'Invalid request.';
}

$conn->close();
?>
