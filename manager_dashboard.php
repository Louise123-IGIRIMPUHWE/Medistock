<?php
session_start();
include 'db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'manager'){
    header("Location: login.php");
}

// statistics
$total_requests = $conn->query("SELECT COUNT(*) as total FROM requests")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) as total FROM requests WHERE status='Pending'")->fetch_assoc()['total'];
$approved = $conn->query("SELECT COUNT(*) as total FROM requests WHERE status='Approved'")->fetch_assoc()['total'];
$rejected = $conn->query("SELECT COUNT(*) as total FROM requests WHERE status='Rejected'")->fetch_assoc()['total'];

// latest requests
$requests = $conn->query("
SELECT requests.*, users.username, medicines.name 
FROM requests
JOIN users ON requests.user_id = users.id
JOIN medicines ON requests.medicine_id = medicines.id
ORDER BY requests.id DESC LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f4f6f9;
        }

        .header {
            background: #34495e;
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

        .btn1 { background: #27ae60; }
        .btn2 { background: #e67e22; }
        .btn3 { background: #9b59b6; }
        .btn4 { background: #e74c3c; }

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

        .pending { color: orange; font-weight: bold; }
        .approved { color: green; font-weight: bold; }
        .rejected { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h1>👨‍💼 Manager Dashboard</h1>
    <p>Welcome, <?= $_SESSION['username'] ?? 'Manager'; ?></p>
</div>

<div class="container">

    <!-- STATISTICS -->
    <div class="cards">
        <div class="card">
            <h2><?= $total_requests ?></h2>
            <p>Total Requests</p>
        </div>

        <div class="card">
            <h2><?= $pending ?></h2>
            <p>Pending</p>
        </div>

        <div class="card">
            <h2><?= $approved ?></h2>
            <p>Approved</p>
        </div>

        <div class="card">
            <h2><?= $rejected ?></h2>
            <p>Rejected</p>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="actions">
        <a href="manager.php" class="btn1">📋 Manage Requests</a>
        <a href="stock_history.php" class="btn2">📊 Stock History</a>
        <a href="dashboard.php" class="btn3">🏠 Main Dashboard</a>
        <a href="logout.php" class="btn4">🚪 Logout</a>
    </div>

    <!-- RECENT REQUESTS -->
    <h3>📌 Recent Requests</h3>

    <table border="1">
        <tr>
            <th>Doctor</th>
            <th>Medicine</th>
            <th>Quantity</th>
            <th>Status</th>
        </tr>

        <?php while($row = $requests->fetch_assoc()){ ?>
        <tr>
            <td><?= $row['username'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td class="<?= strtolower($row['status']) ?>">
                <?= $row['status'] ?>
            </td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>