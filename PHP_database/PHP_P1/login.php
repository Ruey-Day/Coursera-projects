<form method="POST">
    <label for="email">Email:</label>
    <input type="text" name="email" id="email"><br>
    <label for="password">Password:</label>
    <input type="password" name="pass" id="password"><br>
    <input type="submit" value="Log In">
</form>
<?php
session_start();
if (isset($_POST['email']) && isset($_POST['pass'])) {
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = 'Email and password are required';
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email must have an at-sign (@)';
    } else {
        $stored_hash = 'e99a18c428cb38d5f260853678922e03'; // Password: php123
        $check = hash('md5', 'php123' . $_POST['pass']);
        if ($check == $stored_hash) {
            error_log("Login success " . $_POST['email']);
            header("Location: autos.php?name=" . urlencode($_POST['email']));
            return;
        } else {
            error_log("Login fail " . $_POST['email'] . " $check");
            $_SESSION['error'] = 'Incorrect password';
        }
    }
    header("Location: login.php");
    return;
}
?>