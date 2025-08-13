<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["csrf_token"])) {
        echo "CSRF token is required";
    } else {
        $csrf_token = $_POST["csrf_token"];
    }

    if ($csrf_token != $_SESSION["csrf_token"]) {
        echo "CSRF token is invalid";
    } else {
        session_destroy();

        header("location: ../index.php");

        $_SESSION["csrf_token"] = "";

        exit;
    }
} else {
    header("location: ../pages/dashboard.php");
    exit;
}
