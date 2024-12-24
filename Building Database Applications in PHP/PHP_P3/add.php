<?php
session_start();
require_once "pdo.php";

// Redirect to login page if not logged in
// if (!isset($_SESSION['name'])) {
//     die("ACCESS DENIED");
// }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $mileage = $_POST['mileage'];

    // Validate input
    if (empty($make) || empty($model) || empty($year) || empty($mileage)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }

    if (!is_numeric($year) || !is_numeric($mileage)) {
        $_SESSION['error'] = "Year and mileage must be integers";
        header("Location: add.php");
        return;
    }

    // Insert record into database
    $stmt = $pdo->prepare("INSERT INTO autos (make, model, year, mileage) VALUES (:make, :model, :year, :mileage)");
    $stmt->execute([
        ':make' => $make,
        ':model' => $model,
        ':year' => $year,
        ':mileage' => $mileage
    ]);

    $_SESSION['success'] = "Record added";
    header("Location: index.php");
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ruey's Add Automobile</title>
</head>
<body>
<h1>Adding Automobiles for <?= htmlentities($_SESSION['name']) ?></h1>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
    unset($_SESSION['error']);
}
?>

<form method="post">
    <p>Make: <input type="text" name="make"></p>
    <p>Model: <input type="text" name="model"></p>
    <p>Year: <input type="text" name="year"></p>
    <p>Mileage: <input type="text" name="mileage"></p>
    <input type="submit" value="Add">
    <a href="index.php">Cancel</a>
</form>
</body>
</html>