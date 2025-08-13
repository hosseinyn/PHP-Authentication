<?php
include "../database/database.php";
global $conn;

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["csrf_token"])) {
        echo "CSRF token is required";
    } else {
        $token = $_POST["csrf_token"];
    }

    if ($token != $_SESSION["csrf_token"]) {
        echo "CSRF token is invalid";
    } else {
        $username = $_SESSION['username'];

        $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        $stmt->execute();

        $stmt->close();

        session_destroy();
        header("location: ../index.php");

        $_SESSION["csrf_token"] = "";

        exit;
    }
} else {
    header("location: ../pages/dashboard.php");
    exit;
}
