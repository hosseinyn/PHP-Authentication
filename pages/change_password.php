<?php

include "../database/database.php";

session_start();

global $conn;

$error = "";

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("location: login.php");
    exit;
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION["csrf_token"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["change_password_tries"]) || !isset($_SESSION["change_password_tries_expires"]) || time() > $_SESSION["change_password_tries_expires"]) {
        $_SESSION["change_password__tries"] = 1;
        $_SESSION["change_password_tries_expires"] = time() + 100;
    } else {
        $_SESSION["change_password_tries"]++;
    }

    if ($_SESSION["change_password_tries"] > 3) {
        die("Too many attempts. Try again after " . ceil(($_SESSION["change_password_tries_expires"] - time()) / 60) . " minutes.");
    }

    $csrf_token = $_POST["csrf_token"];
    if ($_SESSION["csrf_token"] === $csrf_token) {
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_new_password"];
        $username = $_SESSION["username"];

        if ($new_password === $confirm_password) {
            $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                if (password_verify($current_password, $row["password"])) {
                    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                    $new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt->bind_param("ss", $new_password, $username);
                    $stmt->execute();

                    $stmt->close();

                    session_destroy();

                    header("location: ../pages/login.php");
                    exit;
                } else {
                    $error = "Wrong current password";
                }
            } else {
                $error = "Wrong account";
            }
        } else {
            $error = "Passwords do not match";
        }
    } else {
        $error = "CSRF token is invalid";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/font.css" />
    <link rel="stylesheet" href="../styles/change-password.css" />
    <link rel="icon" href="../assets/favicon.ico" />
    <title>PHP Change password</title>
</head>
<body>

<form action="change_password.php" method="post" class="change-password-form">
    <h1>Change Password</h1>

    <input type="hidden" value="<?php echo $csrf_token; ?>" name="csrf_token" />

    <label for="current_password">Current password : </label>
    <input type="password" id="current_password" name="current_password" placeholder="Enter current password here..." required oninvalid="this.setCustomValidity('Current password is required')"
           oninput="this.setCustomValidity('')"/>

    <label for="new_password">New password : </label>
    <input type="password" id="new_password" name="new_password" placeholder="Enter new password here..." required oninvalid="this.setCustomValidity('New password is required')"
           oninput="this.setCustomValidity('')" />

    <label for="confirm_new_password">Confirm new password : </label>
    <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="Repeat new password here..." required oninvalid="this.setCustomValidity('Confirm password is required')"
           oninput="this.setCustomValidity('')" />

    <p class="error" id="error"></p>

    <?php
    if ($error != "") {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <button type="submit">Change</button>
</form>

<script src="../scripts/changePassword.js"></script>
</body>
</html>

