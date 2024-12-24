<?php
session_start();
require_once "pdo.php";

if ( ! isset($_SESSION['email']) ) {
    die("ACCESS DENIED");
}

if ( ! isset($_GET['autos_id']) ) {
    die("Missing autos_id");
}

if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) ) {
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 ) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    } elseif ( ! is_numeric($_POST['year']) || ! is_numeric($_POST['mileage']) ) {
        $_SESSION['error'] = "Year and mileage must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    } else {
        $stmt = $pdo->prepare('UPDATE autos SET make = :make, model = :model, year = :year, mileage = :mileage WHERE autos_id = :autos_id');
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage'],
            ':autos_id' => $_POST['autos_id'])
        );
        $_SESSION['success'] = "Record updated";
        header("Location: index.php");
        return;
    }
}

$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = "Bad value for autos_id";
    header("Location: index.php");
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Automobile</title>
</head>
<body>
<h1>Edit Auto</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.htmlentities($_SESSION['error'])."</p>\n";
    unset($_SESSION['error']);
}
?>

<form method="POST">
    <input type="hidden" name="autos_id" value="<?= $row['autos_id'] ?>">
    Make: <input type="text" name="make" value="<?= htmlentities($row['make']) ?>"><br/>
    Model: <input type="text" name="model" value="<?= htmlentities($row['model']) ?>"><br/>
    Year: <input type="text" name="year" value="<?= htmlentities($row['year']) ?>"><br/>
    Mileage: <input type="text" name="mileage" value="<?= htmlentities($row['mileage']) ?>"><br/>
    <input type="submit" value="Save">
    <a href="index.php">Cancel</a>
</form>
</body>
</html>