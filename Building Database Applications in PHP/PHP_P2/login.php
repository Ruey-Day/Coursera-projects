<?php
session_start();
if (isset($_SESSION['name'])) {
    header("Location: view.php");
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['email']) || empty($_POST['pass'])) {
        $_SESSION['error'] = 'Email and password are required';
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Email must have an at-sign (@)';
    } else {
        $stored_hash = hash('md5', 'php123'); // Dummy hash for password php123
        $check = hash('md5', 'php123' . $_POST['pass']);
        
        if ($check == $stored_hash) {
            $_SESSION['name'] = $_POST['email'];
            error_log("Login success " . $_POST['email']);
            echo '<p><a href="autos.php">Add New</a></p>';
            header("Location: view.php");
            return;
        } else {
            $_SESSION['error'] = 'Incorrect password';
            error_log("Login fail " . $_POST['email'] . " $check");
            echo '<p><a href="autos.php">Add New</a></p>';
        }
    }
    header("Location: login.php");
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Ruey Day Automobile login</title>
</head>
<body>
    <h1>Login to Ruey Day Automobile login</h1>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n";
        unset($_SESSION['error']);
    }
    echo '<p><a href="autos.php">Add New</a></p>';
    ?>

    <form method="POST">
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" required><br>
        <label for="password">Password:</label>
        <input type="password" name="pass" id="password" required><br>
        <input type="submit" value="Log In">
    </form>
</body>
</html>