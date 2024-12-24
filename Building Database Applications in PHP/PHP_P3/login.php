<?php
session_start();
require_once "pdo.php";

if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['email']); // Logout current user

    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    } elseif ($_POST['pass'] !== "php123") {
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    } else {
        $_SESSION['email'] = $_POST['email'];
        header("Location: index.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Ruey's Login Page</title>
</head>
<body>
<h1>Please Log In</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.htmlentities($_SESSION['error'])."</p>\n";
    unset($_SESSION['error']);
}
?>

<form method="POST">
    User Name <input type="text" name="email"><br/>
    Password <input type="password" name="pass"><br/>
    <input type="submit" value="Log In">
    <a href="index.php">Cancel</a>
</form>
</body>
</html>