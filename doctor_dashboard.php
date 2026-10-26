<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// protect page
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor'){
    header("Location: login.php");
    exit();
}

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// stats
$total_requests = $conn->query("SELECT COUNT(*) as total FROM requests WHERE user_id=$user_id")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) as total FROM requests WHERE user_id=$user_id AND status='Pending'")->fetch_assoc()['total'];
$approved = $conn->query("SELECT COUNT(*) as total FROM requests WHERE user_id=$user_id AND status='Approved'")->fetch_assoc()['total'];
$rejected = $conn->query("SELECT COUNT(*) as total FROM requests WHERE user_id=$user_id AND status='Rejected'")->fetch_assoc()['total'];

// recent requests
$requests = $conn->query("
SELECT requests.*, medicines.name 
FROM requests
JOIN medicines ON requests.medicine_id = medicines.id
WHERE requests.user_id = $user_id
ORDER BY requests.id DESC LIMIT 5
");

// notifications
$notifications = $conn->query("
SELECT requests.*, medicines.name 
FROM requests
JOIN medicines ON requests.medicine_id = medicines.id
WHERE requests.user_id = $user_id 
AND (requests.status='Approved' OR requests.status='Rejected')
ORDER BY requests.id DESC LIMIT 3
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <style>
        body { margin:0; font-family:Arial; background:#f4f6f9; }

        .header {
            background:#16a085;
            color:white;
            padding:15px;
            text-align:center;
        }

        .container { padding:20px; }

        .cards {
            display:flex;
            gap:20px;
            flex-wrap:wrap;
        }

        .card {
            flex:1;
            min-width:200px;
            background:white;
            padding:20px;
            border-radius:10px;
            text-align:center;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        .actions {
            margin-top:30px;
            text-align:center;
        }

        .actions a {
            display:inline-block;
            margin:10px;
            padding:12px 20px;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }

        .btn1 { background:#3498db; }
        .btn2 { background:#9b59b6; }
        .btn3 { background:#2ecc71; }
        .btn4 { background:#e74c3c; }

        table {
            width:100%;
            margin-top:30px;
            background:white;
            border-collapse:collapse;
        }

        th, td {
            padding:10px;
            text-align:center;
        }

        th { background:#333; color:white; }

        .pending { color:orange; font-weight:bold; }
        .approved { color:green; font-weight:bold; }
        .rejected { color:red; font-weight:bold; }

        .notify-box {
            background:white;
            padding:12px;
            margin:10px 0;
            border-left:5px solid;
            box-shadow:0 0 5px rgba(0,0,0,0.1);
        }

        .empty {
            text-align:center;
            margin-top:20px;
            color:#555;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>👨‍⚕️ Doctor Dashboard</h1>
    <p>Welcome, <?= $_SESSION['username'] ?? 'Doctor'; ?></p>
</div>

<div class="container">

    <!-- STATS -->
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
        <a href="doctor_request.php" class="btn1">📝 Request Medicine</a>
        <a href="stock_history.php" class="btn2">📊 Stock History</a>
        <a href="dashboard.php" class="btn3">🏠 Main Dashboard</a>
        <a href="logout.php" class="btn4">🚪 Logout</a>
    </div>

    <!-- NOTIFICATIONS -->
    <h3>🔔 Notifications</h3>

    <?php if($notifications->num_rows > 0): ?>
        <?php while($n = $notifications->fetch_assoc()): ?>
            
            <div class="notify-box" style="border-color: <?= ($n['status']=='Approved') ? 'green' : 'red' ?>;">
                Your request for <b><?= $n['name'] ?></b> 
                (<?= $n['quantity'] ?>) 
                was 
                <b><?= $n['status'] ?></b>
            </div>

        <?php endwhile; ?>
    <?php else: ?>
        <p class="empty">No notifications yet</p>
    <?php endif; ?>

    <!-- RECENT REQUESTS -->
    <h3>📌 My Recent Requests</h3>

    <?php if($requests->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Medicine</th>
                <th>Quantity</th>
                <th>Status</th>
            </tr>

            <?php while($row = $requests->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td class="<?= strtolower($row['status']) ?>">
                    <?= $row['status'] ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="empty">No requests yet</p>
    <?php endif; ?>

</div>

</body>
</html>