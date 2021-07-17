<?php 
include './../creds.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
}
else{
  if(isset($_POST["remark"]) && isset($_POST['activityId'])){

    $activityId = $_POST['activityId'];
    $remark = $_POST['remark']; 
    $id = $_SESSION['id'];
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);

    
    $clubId = $user['clubId'];
    $remark_sql = "INSERT INTO `rejected_activities`(`activityId`,`clubId`,`remark`) VALUES('$activityId','$clubId','$remark') ";
    mysqli_query($con, $remark_sql);
  }
}

?>