<?php
session_start();

// if (!isset($_SESSION['name'])) {
//     header("Location: login.php"); // Redirect to your login page
//     exit();
// }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
        if (strlen($_POST['make']) < 1 ) {
            $_SESSION['error'] = 'Make is required';
            header("Location: autos.php");
            return;
        } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
            $_SESSION['error'] = 'Year and mileage must be numeric';
            header("Location: autos.php");
            return;
        } else {
            require_once "pdo.php";
            $sql = "INSERT INTO autos (make, year, mileage) 
                      VALUES (:make, :year, :mileage)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':make' => $_POST['make'],
                ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage']
            ));
            $_SESSION['success'] = 'Record inserted';
            header('Location: view.php');
            return;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ruey Day - Autos Database</title>
</head>
<body>
<h1>Add New</h1>
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
    unset($_SESSION['error']);
}
?>
<form method="post">
    <label for="make">Make</label>
    <input type="text" name="make" id="make"><br/>

    <label for="year">Year</label>
    <input type="text" name="year" id="year"><br/>
    
    <label for="mileage">Mileage</label>
    <input type="text" name="mileage" id="mileage"><br/>

    <input type="submit" value="Add"> <!-- Submit button -->
    <a href="view.php">Cancel</a>
</form>

</body>
</html>