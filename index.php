<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/font.css" />
    <link rel="stylesheet" href="styles/index.css" />
    <title>PHP Authentication</title>
</head>
<body>

<div class="menu">
    <a href="https://github.com/hosseinyn/PHP-Authentication">GitHub</a>
    Created by PHP , MySQL
</div>

<h1 style="margin-top: 50px">PHP Authentication</h1>

<div style="text-align: center;"><img src="assets/icon.png" alt="icon" /></div>

<p>There are your options : </p>

<?php
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    echo <<<HTML
<div class="options-button">
    <a href="pages/login.php"><button>Login</button></a>
    <a href="pages/register.php"><button>Register</button></a>
</div>
HTML;
} else {
    echo <<<HTML
<div class="options-button">
    <a href="pages/dashboard.php"><button>Dashboard</button></a>
    <a href=""><button>Log out</button></a>
</div>
HTML;
}
?>

</body>
</html>