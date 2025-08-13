<?php

session_start();

if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    header("location: dashboard.php");
    exit;
}

global $conn;
include __DIR__ . "/../database/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $entred_password = $_POST['password'];
    $password = password_hash($_POST['password'] , PASSWORD_DEFAULT);
    $confirm_password = $_POST['confirm_password'];

    if ($entred_password == $username) {
        $error = "Password can't be same with username";
    } else {

    if (strlen($username) > 7 || strlen($entred_password) > 15) {
        $error = "Fields are more than 7 or 15 characters";
    } else {
        if ($entred_password != $confirm_password) {
            $error = "Passwords do not match";
        } else {
            $check_user_query = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn , $check_user_query);
            if (mysqli_num_rows($result) > 0) {
                $error = "Username already exists";
            } else {
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            if ($conn->query($sql) === true) {
                header("location: login.php");
            } else {
                $error = "Error: " . $sql . "<br>" . $conn->error;
            } }
    } } }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/font.css" />
    <link rel="stylesheet" href="../styles/auth-form.css" />
    <link rel="icon" href="../assets/favicon.ico" />
    <title>PHP Register</title>
</head>
<body>

<form action="register.php" method="post" class="login-form register-form">
    <h1>PHP Register Page</h1>

    <label for="username">Username : </label>
    <input type="text" id="username" name="username" placeholder="Enter username here..." minlength="4" maxlength="7" required oninvalid="this.setCustomValidity('Username is required')"
           oninput="this.setCustomValidity('')" onkeyup="validateUsername()" />

    <label for="password">Password : </label>
    <input type="password" id="password" name="password" placeholder="Enter password here..." minlength="5" maxlength="15" required oninvalid="this.setCustomValidity('Password is required')"
           oninput="this.setCustomValidity('')" />

    <label for="password">Confirm Password : </label>
    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password here..." minlength="5" maxlength="15" required oninvalid="this.setCustomValidity('Confirm password is required')"
           oninput="this.setCustomValidity('')" />

    <p class='error' id="error"></p>

    <?php
    if ($error != "") {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <p class="account-link">Do you have an account? <a href="login.php">Login Now</a> </p>

    <button type="submit">Register</button>
</form>

<script src="../scripts/validate.js"></script>
</body>
</html>