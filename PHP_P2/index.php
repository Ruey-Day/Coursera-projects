<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ruey Day - Autos Database</title>
</head>
<body>
    <h1>Welcome to Ruey's Automobile Tracker</h1>
    
    <?php
    // Check if the user is already logged in
    if (isset($_SESSION['name'])) {
        // If logged in, show a link to autos.php
        echo '<p><a href="autos.php">Proceed to the Automobile Database</a></p>';
    } else {
        // If not logged in, show a link to login.php
        echo '<p><a href="login.php">Please Log In</a></p>';
    }
    ?>
    
    <p>Attempting to go to <a href="autos.php">autos.php</a> without logging in will fail with an error message.</p>
</body>
</html>