<?php
session_start();
session_destroy();
require 'connection.php';
global $conn;
$conn->close();
header("Location: index.php");
exit;
?>
