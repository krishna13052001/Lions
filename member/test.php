<?php
include './../creds.php';
$con = new mysqli($host, $user, $pass, $dbname);
$result = $con->query("SELECT DISTINCT `clubId`, `clubName` FROM `users`");
$result = $result->fetch_all(MYSQLI_ASSOC);
foreach ($result as $items) {
    echo "INSERT INTO `clubs` (`clubId`, `clubName`) VALUES ('" . $items['clubId'] . "', '" . $items['clubName'] . "');";
}
$con->close();