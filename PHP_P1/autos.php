<?php
session_start();
require_once "pdo.php"; // Include the PDO connection

if (!isset($_GET['name'])) {
    die("Name parameter missing");
}

if (isset($_POST['logout'])) {
    header('Location: index.php');
    return;
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1) {
        $_SESSION['error'] = 'Make is required';
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'Mileage and year must be numeric';
    } else {
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = 'Record inserted';
    }
    header("Location: autos.php?name=" . urlencode($_GET['name']));
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Automobile Tracker</title>
</head>
<body>
<h1>Tracking Autos for <?= htmlentities($_GET['name']) ?></h1>
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red;">' . htmlentities($_SESSION['error']) . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color:green;">' . htmlentities($_SESSION['success']) . "</p>\n";
    unset($_SESSION['success']);
}
?>
<form method="POST">
    <p>Make: <input type="text" name="make"></p>
    <p>Year: <input type="text" name="year"></p>
    <p>Mileage: <input type="text" name="mileage"></p>
    <input type="submit" value="Add">
    <input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li>" . htmlentities($row['make']) . " " . htmlentities($row['year']) . " / " . htmlentities($row['mileage']) . "</li>";
}
?>
</ul>
</body>
</html>