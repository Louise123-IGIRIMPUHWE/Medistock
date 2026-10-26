<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$message = "";

if(isset($_POST['confirm'])){

    $id = $_POST['id'];

    // get request
    $r = $conn->query("SELECT * FROM requests WHERE id=$id")->fetch_assoc();

    if($r){

        // get current stock
        $med = $conn->query("SELECT quantity FROM medicines WHERE id = {$r['medicine_id']}")->fetch_assoc();
        $current_stock = $med['quantity'];

        // 🔥 CHECK STOCK
        if($r['quantity'] > $current_stock){
            $message = "Not enough stock!";
        } else {

            // reduce stock
            $conn->query("UPDATE medicines 
            SET quantity = quantity - {$r['quantity']} 
            WHERE id = {$r['medicine_id']}");

            // save history
            $conn->query("INSERT INTO stock_history(medicine_id, change_qty, type)
            VALUES({$r['medicine_id']}, {$r['quantity']}, 'REMOVE')");

            // update status
            $conn->query("UPDATE requests SET status='Approved' WHERE id=$id");

            if($conn->error){
                $message = "Error: " . $conn->error;
            } else {
                $message = "Request Approved Successfully!";
            }
        }

    } else {
        $message = "Request not found!";
    }
}

$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve Request</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            background: #f4f4f4;
        }
        .box {
            background: white;
            padding: 20px;
            display: inline-block;
            margin-top: 100px;
        }
        button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
        }
        .yes { background: green; color: white; }
        .no { background: red; color: white; }
    </style>
</head>
<body>

<div class="box">

<h2>Confirm Approval</h2>

<?php if($message != ""){ ?>
    <p><?= $message ?></p>
    <a href="manager.php"><button>Back</button></a>
<?php } else { ?>

<p>Are you sure you want to approve this request?</p>

<form method="POST">
    <input type="hidden" name="id" value="<?= $id ?>">
    <button name="confirm" class="yes">Yes, Approve</button>
</form>

<a href="manager.php">
    <button class="no">Cancel</button>
</a>

<?php } ?>

</div>

</body>
</html>