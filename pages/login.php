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
    $password = $_POST['password'];

    if (strlen($username) > 7 || strlen($password) > 15) {
        $error = "Fields are more than 7 or 15 characters";
    } else {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            if (password_verify($password, $row["password"])) {
                session_regenerate_id(true);
                $_SESSION['username'] = $row["username"];
                $_SESSION['loggedIn'] = true;
                session_write_close();
                header("location: dashboard.php");
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/font.css" />
    <link rel="stylesheet" href="../styles/auth-form.css" />
    <link rel="icon" href="../assets/favicon.ico" />
    <title>PHP Login</title>
</head>
<body>

<form action="login.php" method="post" class="login-form">
    <h1>PHP Login Page</h1>

    <label for="username">Username : </label>
    <input type="text" id="username" name="username" placeholder="Enter username here..." minlength="4" maxlength="7" required />

    <label for="password">Password : </label>
    <input type="password" id="password" name="password" placeholder="Enter password here..." minlength="5" maxlength="15" />

    <?php
    if ($error != "") {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <p class="account-link">You don't have an account?  <a href="register.php">Register Now</a> </p>

    <button type="submit">Login</button>
</form>

</body>
</html>
