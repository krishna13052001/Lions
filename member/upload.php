<?php

include './../creds.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
}
else {
    $id = $_SESSION['id'];
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);
    $clubId = $user['clubId'];

    $activities = "SELECT * FROM `activities` WHERE `clubId`='$clubId'";
    $activities = mysqli_query($con, $activities);
    $activities  = mysqli_fetch_array($activities);
    $status = $activities['status'];
    $authorId = $activities['authorId'];
    $activityId = $activities['activityId'];
    

if(isset($_POST['submit']))
{
  $activityId = $_POST['activityId'];
  $file = $_FILES['file'];
  $fileName = $_FILES['file']['name'];
  $fileTmpName = $_FILES['file']['tmp_name'];
  $fileSize = $_FILES['file']['size'];
  $fileError = $_FILES['file']['error'];
  $fileType = $_FILES['file']['type'];
  $fileExt = explode('.', $fileName);
  $fileActualExt = strtolower(end($fileExt));

  $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'csv', 'xlsx');

  if(in_array($fileActualExt, $allowed))
  {
    if($fileError === 0){
      if($fileSize < 10000000){
        $filenameNew = uniqid('', true).".".$fileActualExt;
        $fileDestination = './bills/'.$filenameNew;
        move_uploaded_file($fileTmpName, $fileDestination);
        $uploadFileSql = "INSERT INTO `activities`(`bills`) VALUE('$filenameNew') WHERE `activityId`='$activityId'";
        $fileUploaded = mysqli_query($con, $uploadFileSql);
        // header("Location: pending-activities.php");
        echo $activityId;
      }
      else{
        echo "Your file is too large!";
      }
    }
    else{
      echo "Error while uploading your file!";
    }
  }
  else
  {
    echo "Cannot upload files of this type";
  }
}
}
?>