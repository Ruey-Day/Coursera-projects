<?php
session_start();
require_once "pdo.php";

$autos_id = $_GET['autos_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['delete'] == 'Delete') {
        $stmt = $pdo->prepare("DELETE FROM autos WHERE autos_id = :id");
        $stmt->execute([':id' => $autos_id]);
        
        $_SESSION['success'] = "Record deleted";
        header("Location: index.php");
        return;
    } else {
        header("Location: index.php");
        return;
    }
}

// Fetch the current record for confirmation
$stmt = $pdo->prepare("SELECT make, model FROM autos WHERE autos_id = :id");
$stmt->execute([':id' => $autos_id]);
$auto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auto) {
    $_SESSION['error'] = "No such automobile found";
    header("Location: index.php");
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ruey's Delete Automobile</title>
</head>
<body>
<h1>Delete Automobile</h1>

<p>Are you sure you want to delete the following automobile?</p>
<p><strong>Make:</strong> <?= htmlentities($auto['make']) ?></p>
<p><strong>Model:</strong> <?= htmlentities($auto['model']) ?></p>

<form method="post">
    <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
    <input type="submit" name="delete" value="Delete">
    <input type="submit" name="delete" value="Cancel">
</form>

</body>
</html>