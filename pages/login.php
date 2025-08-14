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

    if (!isset($_SESSION["login_tries"]) || !isset($_SESSION["login_tries_expires"]) || time() > $_SESSION["login_tries_expires"]) {
        $_SESSION["login_tries"] = 1;
        $_SESSION["login_tries_expires"] = time() + 100;
    } else {
        $_SESSION["login_tries"]++;
    }

    if ($_SESSION["login_tries"] > 3) {
        die("Too many attempts. Try again after " . ceil(($_SESSION["login_tries_expires"] - time()) / 60) . " minutes.");
    }

    if (empty($_POST["csrf_token"])) {
        $error = "Csrf token is required";
        die("Csrf token is required");
    } else {
        $csrf_token = $_POST["csrf_token"];
    }
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($csrf_token !== $_SESSION["csrf_token"]) {
        $error = "Invalid CSRF token";
    } else {

if (strlen($username) > 7 || strlen($password) > 15) {
    $error = "Fields are more than 7 or 15 characters";
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_array(MYSQLI_ASSOC);

        if (password_verify($password, $row["password"])) {
            session_regenerate_id(true);
            $_SESSION['username'] = $row["username"];
            $_SESSION['loggedIn'] = true;
            if ($row["is_admin"] == 1) {
                $_SESSION['is_admin'] = true;
            }
            session_write_close();
            header("Location: dashboard.php");

            $_SESSION["csrf_token"] = "";
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }

    $stmt->close();
}}}

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

<form action="login.php" method="post" class="login-form" id="login-form">
    <h1>PHP Login Page</h1>

    <input type="hidden" value="<?php
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    echo $_SESSION["csrf_token"];
    ?>" name="csrf_token">

    <label for="username">Username : </label>
    <input type="text" id="username" name="username" placeholder="Enter username here..." minlength="4" maxlength="7" required oninvalid="this.setCustomValidity('Username is required')"
           oninput="this.setCustomValidity('')" onkeyup="validateUsername()" />

    <label for="password">Password : </label>
    <input type="password" id="password" name="password" placeholder="Enter password here..." minlength="5" maxlength="15" oninvalid="this.setCustomValidity('Password is required')"
           oninput="this.setCustomValidity('')"/>

    <p class='error' id="error"></p>

    <?php
    if ($error != "") {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <p class="account-link">You don't have an account?  <a href="register.php">Register Now</a> </p>

    <button type="submit">Login</button>
</form>

<script src="../scripts/validate.js"></script>
</body>
</html>