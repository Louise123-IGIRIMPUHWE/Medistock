<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$message = "";

if(isset($_POST['update'])){
    $id = $_POST['id'];
    $qty = $_POST['quantity'];

    // update stock
    $conn->query("UPDATE medicines SET quantity = quantity + $qty WHERE id = $id");

    // insert history
    $conn->query("INSERT INTO stock_history(medicine_id, change_qty, type)
    VALUES('$id', '$qty', 'ADD')");

    if($conn->error){
        $message = "Error: " . $conn->error;
    } else {
        $message = "Stock updated successfully";
    }
}

// fetch medicines
$meds = $conn->query("SELECT * FROM medicines");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Stock Management</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            background: #f4f4f4;
        }
        table {
            margin: auto;
            background: white;
            padding: 10px;
        }
        input {
            padding: 8px;
        }
        button {
            padding: 8px 15px;
            background: green;
            color: white;
            border: none;
        }
    </style>
</head>
<body>

<h2>Admin - Update Medicine Stock</h2>

<?php if($message != "") echo "<p>$message</p>"; ?>

<table border="1">
<tr>
    <th>Medicine</th>
    <th>Current Quantity</th>
    <th>Add Quantity</th>
    <th>Action</th>
</tr>

<?php while($m = $meds->fetch_assoc())
<tr>
<form method="POST">
    <td><?= $m['name'] ?></td>
    <td><?= $m['quantity'] ?></td>
    <td>
        <input type="number" name="quantity" required>
        <input type="hidden" name="id" value="<?= $m['id'] ?>">
    </td>
    <td>
        <button name="update">Add Stock</button>
    </td>
</form>
</tr>
?>

</table>

</body>
</html>