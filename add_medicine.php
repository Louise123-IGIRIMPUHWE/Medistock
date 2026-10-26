<?php
session_start();
include 'db.php';

// protect
if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['add'])){
    $name = trim($_POST['name']);
    $quantity = (int) $_POST['quantity'];

    if($name != "" && $quantity > 0){

        // check duplicate
        $check = $conn->query("SELECT * FROM medicines WHERE name='$name'");
        
        if($check->num_rows > 0){
            $message = "Medicine already exists!";
        } else {

            if($conn->query("INSERT INTO medicines (name, quantity) VALUES ('$name', $quantity)")){
                $message = "Medicine added successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
        }

    } else {
        $message = "Fill all fields correctly!";
    }
}

// get all medicines
$meds = $conn->query("SELECT * FROM medicines ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            background: #f4f4f4;
        }

        .box {
            background: white;
            padding: 25px;
            display: inline-block;
            margin-top: 50px;
            border-radius: 10px;
            width: 400px;
        }

        input {
            padding: 10px;
            margin: 10px;
            width: 90%;
        }

        button {
            padding: 10px 20px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        .msg {
            margin: 10px;
            font-weight: bold;
        }

        table {
            margin-top: 30px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background: #333;
            color: white;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>➕ Add Medicine</h2>

    <?php if($message != ""): ?>
        <p class="msg"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Medicine Name" required><br>
        <input type="number" name="quantity" placeholder="Quantity" required><br>
        <button name="add">Add Medicine</button>
    </form>

    <br>
    <a href="doctor_dashboard.php">Back</a>
</div>

<!-- LIST OF MEDICINES -->
<h2>📦 Available Medicines</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Quantity</th>
</tr>

<?php while($m = $meds->fetch_assoc()): ?>
<tr>
    <td><?= $m['id'] ?></td>
    <td><?= $m['name'] ?></td>
    <td><?= $m['quantity'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>