<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dogId = $_POST['dogId'];

    $updateSql = "UPDATE tbl_231_dogs SET hugs = hugs + 1 WHERE id = '$dogId'";
    $conn->query($updateSql);

    $selectSql = "SELECT hugs FROM tbl_231_dogs WHERE id = '$dogId'";
    $result = $conn->query($selectSql);
    $row = $result->fetch_assoc();
    $hugsCount = $row['hugs'];

    echo $hugsCount;
}
?>
