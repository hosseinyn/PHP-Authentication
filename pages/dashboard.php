<?php

session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/font.css" />
    <link rel="stylesheet" href="../styles/dashboard.css" />
    <link rel="icon" href="../assets/favicon.ico" />
    <title>PHP Dashboard</title>
</head>
<body>

<?php
if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true) {
    echo "<h1 style='margin-top: 50px;'>Welecome $_SESSION[username]. You're Admin ! </h1>";
} else {
    echo "<h1 style='margin-top: 50px;'>Welecome $_SESSION[username] ! </h1>";
}
?>

<p>What to do : </p>

<div class="options-button">
    <a href="../operations/delete_account.php"><button>Delete Account</button></a>
    <a href="../operations/logout.php"><button>Log out</button></a>
    <a href="change_password.php"><button>Change password</button></a>
</div>

</body>
</html>
