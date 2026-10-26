<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if(isset($_POST['yes'])){
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            background: #f4f6f9;
        }

        .box {
            background: white;
            padding: 30px;
            display: inline-block;
            margin-top: 100px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            color: white;
            border-radius: 5px;
        }

        .yes { background: #e74c3c; }
        .no { background: #2ecc71; }
    </style>
</head>
<body>

<div class="box">
    <h2>🚪 Logout</h2>
    <p>Are you sure you want to logout?</p>

    <form method="POST">
        <button name="yes" class="yes">Yes, Logout</button>
    </form>

    <a href="dashboard.php">
        <button class="no">Cancel</button>
    </a>
</div>

</body>
</html>