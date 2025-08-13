<?php
include "../database/database.php";
global $conn;

session_start();

$username = $_SESSION['username'];

$stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
$stmt->bind_param("s", $username);

$stmt->execute();

$stmt->close();

session_destroy();
header("location: ../index.php");
exit;
