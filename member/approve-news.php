<?php
include './../creds.php';
include './../upload-image.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = $con->real_escape_string(strip_tags($id));

        $con->query("UPDATE `news` SET `verified` = '1' WHERE `newsId` = '$id'");
        header("Location: ./news-reporting.php");
    } else if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $id = $con->real_escape_string(strip_tags($id));

        $con->query("DELETE FROM `news` WHERE `newsId` = '$id'");

        $imgSql = "SELECT * FROM `images` WHERE `category` = 'news' AND `categoryId` = '$id'";
        $images = mysqli_query($con, $imgSql);
        while ($path = mysqli_fetch_array($images)) {
            unlink($path['img']);
        }
        $imgSql = "DELETE FROM `images` WHERE `category` = 'news' AND `categoryId` = '$id'";
        mysqli_query($con, $imgSql);
        header("Location: ./news-reporting.php");
    }
}