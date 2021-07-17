<?php
session_start();
    include './creds.php';

$con = mysqli_connect($host, $user, $pass, $dbname);


print_r($_POST);

if (isset($_POST['login'])) {
    $id = $_POST['id'];
    $id = strip_tags($id);
    $id = mysqli_real_escape_string($con, $id);

        print_r($_POST);
    //echo password_hash('lions member', PASSWORD_BCRYPT);

    $password = $_POST['password'];

    $sql = "SELECT * FROM `users` WHERE `id` = '$id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);

    if ($row == 1) {
        $row = mysqli_fetch_array($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $id;
            header("Location: ./member/home.php");
        }
    }
}
mysqli_close($con);


?>