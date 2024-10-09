<?php
session_start();
// if (!isset($_SESSION['name'])) {
//     die('Not logged in');
// }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ruey Day Autos view</title>
</head>
<body>
<h1>Welcome <?= htmlentities($_SESSION['name']); ?></h1>
<?php
if (isset($_SESSION['success'])) {
    echo '<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n";
    unset($_SESSION['success']);
    echo '<p><a href="autos.php">Add New</a></p>';
}
?>
<h2>Automobiles</h2>
<table border="1">
<tr><th>Make</th><th>Year</th><th>Mileage</th></tr>
<?php
require_once "pdo.php"; // Ensure this file sets up the PDO $pdo

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>".htmlentities($row['make'])."</td>";
    echo "<td>".htmlentities($row['year'])."</td>";
    echo "<td>".htmlentities($row['mileage'])."</td></tr>\n";
}
?>
</table>
<p><a href="add.php">Add New</a></p>||<p><a href="index.php">Logout</a></p>

</body>
</html>
