<?php
session_start();
include 'db.php';

$message = "";

if(isset($_POST['login'])){
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $result->fetch_assoc();

    if($user){
        // niba ukoresha hashed password
        if(password_verify($password, $user['password']) || $password == $user['password']){

            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // redirect based on role
            if($user['role'] == 'admin'){
                header("Location: admin_dashboard.php");
            } elseif($user['role'] == 'manager'){
                header("Location: manager_dashboard.php");
            } else {
                header("Location: doctor_dashboard.php");
            }

        } else {
            $message = "Wrong password!";
        }
    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            text-align: center;
        }

        .box {
            background: white;
            padding: 30px;
            display: inline-block;
            margin-top: 100px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input {
            padding: 10px;
            width: 250px;
            margin: 10px;
        }

        button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>🔐 Login</h2>

    <?php if($message != ""){ ?>
        <p class="error"><?= $message ?></p>
    <?php } ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button name="login">Login</button>
    </form>
</div>

</body>
</html>