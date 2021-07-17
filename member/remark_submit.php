<?php
include './../creds.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
}

$remark = mysqli_real_escape_string($con, $_POST['remark']);
$status = mysqli_real_escape_string($con, $_POST['status'])

// mysqli_query($con, "INSERT INTO `activities`(remark, status) VALUES('$remark', '$status')");

?>