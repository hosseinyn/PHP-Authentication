<?php

// Database information
$host = "localhost";
$user = "root";
$pass = "";
$db = "php-login";

// Connect to mysql database
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    exit("Connection failed: " . mysqli_connect_error());
}