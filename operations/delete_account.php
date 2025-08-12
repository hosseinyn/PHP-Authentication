<?php
include "../database/database.php";
global $conn;

session_start();

$username = $_SESSION['username'];
$sql = "DELETE FROM users WHERE username='$username'";

$result = mysqli_query($conn, $sql);

session_destroy();
header("location: ../index.php");
exit;
