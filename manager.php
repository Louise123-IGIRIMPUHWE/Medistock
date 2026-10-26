<?php
session_start(); // igomba kuba iya mbere

include 'db.php';


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'manager'){
    header("Location: login.php");
    exit();
}

// fetch requests
$result = $conn->query("
SELECT requests.id, users.username, medicines.name, requests.quantity, requests.status
FROM requests
JOIN users ON requests.user_id = users.id
JOIN medicines ON requests.medicine_id = medicines.id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Panel</title>
    <style>
        body { font-family: Arial; text-align:center; }
        table { margin:auto; background:white; }
        button { padding:5px 10px; }
        .approve { background:green; color:white; }
        .reject { background:red; color:white; }
    </style>
</head>
<body>

<h2>Manager - Requests</h2>

<table border="1">
<tr>
    <th>Doctor</th>
    <th>Medicine</th>
    <th>Quantity</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?= $row['username'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <?php if($row['status'] == 'Pending'){ ?>
            <a href="approve.php?id=<?= $row['id'] ?>">
                <button class="approve">Approve</button>
            </a>
            <a href="reject.php?id=<?= $row['id'] ?>">
                <button class="reject">Reject</button>
            </a>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>