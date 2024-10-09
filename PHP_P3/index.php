<?php
session_start();
require_once "pdo.php";

// Redirect to login page if not logged in
// if (!isset($_SESSION['name'])) {
//     die("ACCESS DENIED");
// }

// Flash message handling
if (isset($_SESSION['success'])) {
    echo '<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
    unset($_SESSION['error']);
}

// Retrieve automobile records
$stmt = $pdo->query("SELECT autos_id, make, model, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ruey Day Automobile Database</title>
</head>
<body>
<h1>Welcome to the Automobiles Database</h1>
<?php
        echo '<p><a href="login.php">Please log in</a></p>';
?>
<?php if (empty($rows)) : ?>
    <p>No rows found</p>
<?php else : ?>
    <table border="1">
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Mileage</th>
            <th>Action</th>
        </tr>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?= htmlentities($row['make']) ?></td>
                <td><?= htmlentities($row['model']) ?></td>
                <td><?= htmlentities($row['year']) ?></td>
                <td><?= htmlentities($row['mileage']) ?></td>
                <td>
                    <a href="edit.php?autos_id=<?= $row['autos_id'] ?>">Edit</a> /
                    <a href="delete.php?autos_id=<?= $row['autos_id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<p><a href="add.php">Add New Entry</a></p>
<p><a href="logout.php">Logout</a></p>
</body>
</html>
