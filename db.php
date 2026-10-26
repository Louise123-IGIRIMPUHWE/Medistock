<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = "localhost";
$user = "root";
$pass = "";
$db = "hospitalstock";

$conn = mysqli_connect($server, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
}
?>