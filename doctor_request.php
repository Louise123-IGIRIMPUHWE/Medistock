<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor'){
    header("Location: login.php");
    exit();
}

$meds = $conn->query("SELECT * FROM medicines");

$message = "";

if(isset($_POST['send'])){
    $medicine_id = $_POST['medicine_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    $conn->query("INSERT INTO requests (user_id, medicine_id, quantity, status)
    VALUES ($user_id, $medicine_id, $quantity, 'Pending')");

    $message = "Request sent successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Medicine</title>
</head>
<body>

<h2>Request Medicine</h2>

<?php if($message != ""): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<form method="POST">
    <select name="medicine_id">
        <?php while($m = $meds->fetch_assoc()): ?>
            <option value="<?= $m['id'] ?>">
                <?= $m['name'] ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <input type="number" name="quantity" placeholder="Quantity" required><br><br>

    <button name="send">Send Request</button>
</form>

<br>
<a href="doctor_dashboard.php">Back</a>

</body>
</html>