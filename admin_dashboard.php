<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
}

$total_meds = $conn->query("SELECT COUNT(*) as total FROM medicines")->fetch_assoc()['total'];
$total_stock = $conn->query("SELECT SUM(quantity) as total FROM medicines")->fetch_assoc()['total'];
$low_stock = $conn->query("SELECT COUNT(*) as total FROM medicines WHERE quantity < 50")->fetch_assoc()['total'];
$requests = $conn->query("SELECT COUNT(*) as total FROM requests")->fetch_assoc()['total'];

$history = $conn->query("
SELECT stock_history.*, medicines.name 
FROM stock_history
JOIN medicines ON stock_history.medicine_id = medicines.id
ORDER BY stock_history.created_at DESC LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f4f6f9;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            padding: 20px;
        }

        .cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin: 0;
        }

        .alert {
            background: #e74c3c;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
        }

        .actions {
            margin-top: 30px;
            text-align: center;
        }

        .actions a {
            display: inline-block;
            margin: 10px;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn1 { background: #3498db; }
        .btn2 { background: #27ae60; }
        .btn3 { background: #9b59b6; }
        .btn4 { background: #e67e22; }
        .btn5 { background: #e74c3c; }

        table {
            width: 100%;
            margin-top: 30px;
            background: white;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background: #333;
            color: white;
        }

        .add { color: green; }
        .remove { color: red; }
    </style>
</head>
<body>

<div class="header">
    <h1>👑 Admin Dashboard</h1>
    <p>Welcome, <?= $_SESSION['username'] ?? 'Admin'; ?></p>
</div>

<div class="container">

    <!-- STATISTICS -->
    <div class="cards">
        <div class="card">
            <h2><?= $total_meds ?></h2>
            <p>Total Medicines</p>
        </div>

        <div class="card">
            <h2><?= $total_stock ?></h2>
            <p>Total Stock Quantity</p>
        </div>

        <div class="card">
            <h2><?= $requests ?></h2>
            <p>Total Requests</p>
        </div>

        <div class="card">
            <h2><?= $low_stock ?></h2>
            <p>Low Stock Items</p>
        </div>
    </div>

    <!-- LOW STOCK ALERT -->
    <?php if($low_stock > 0){ ?>
        <div class="alert">
            🚨 Warning: Some medicines are low in stock!
        </div>
    <?php } ?>

    <!-- ACTION BUTTONS -->
    <div class="actions">
        <a href="admin.php" class="btn1">📦 Manage Stock</a>
        <a href="manager.php" class="btn2">📋 View Requests</a>
        <a href="stock_history.php" class="btn3">📊 Stock History</a>
        <a href="request.php" class="btn4">📝 Request Medicine</a>
        <a href="logout.php" class="btn5">🚪 Logout</a>
    </div>

    <!-- RECENT HISTORY -->
    <h3>📊 Recent Stock Activity</h3>

    <table border="1">
        <tr>
            <th>Medicine</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Date</th>
        </tr>

        <?php while($row = $history->fetch_assoc()){ ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['change_qty'] ?></td>
            <td class="<?= strtolower($row['type']) ?>">
                <?= $row['type'] ?>
            </td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>