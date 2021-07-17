<?php
include './../creds.php';
include './../upload-image.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'], $_GET['event'])) {
    header("Location: ./../login.php");
} else {
    $id = $_SESSION['id'];
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);
    $eId = $con->real_escape_string($_GET['event']);
    if (
        !(strpos(strtolower($user['title']), 'president') !== false ||
        strpos(strtolower($user['title']), 'club president') !== false ||
        strpos(strtolower($user['title']), 'club secretary') !== false ||
        strpos(strtolower($user['title']), 'secretary') !== false ||
        strpos(strtolower($user['title']), 'district governor') !== false ||
        strpos(strtolower($user['title']), 'cabinet secretary') !== false ||
        strpos(strtolower($user['title']), 'cabinet treasurer') !== false ||
        strpos(strtolower($user['title']), 'club treasurer') !== false)
    ) {
        header("Location: ./event-reporting.php");
    }
    
    $bookings = $con->query("SELECT * FROM `booked-tickets` WHERE `eventId` = '$eId'");
    $count = $bookings->num_rows;
    $bookings = $bookings->fetch_all(MYSQLI_ASSOC);
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <style>
        table {
          font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }
        
        table td, table th {
          border: 1px solid #ddd;
          padding: 8px;
        }
        
        table tr:nth-child(even){background-color: #f2f2f2;}
        
        table tr:hover {background-color: #ddd;}
        
        table th {
          padding-top: 12px;
          padding-bottom: 12px;
          text-align: left;
          background-color: #4CAF50;
          color: white;
        }
    </style>
    <title>Event Reporting | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
    <section class="members container">
        <?php if ($user['verified'] == 0) {
            echo '<h1 class="text-center">Your Profile isn\'t updated!</h1>';
        } ?>
    <h2 class="text-center">View Event Bookings</h2>
    <p class="text-center">Total: <?= $count ?></p>
    <table>
        <tr>
            <th>Booking Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Club Name</th>
        </tr>
        <?php foreach($bookings as $book) : ?>
            <tr>
                <td><?= $book['id'] ?></td>
                <td><?= ucfirst($book['firstName'].$book['lastName']) ?></td>
                <td><a href="mailto:<?= $book['email'] ?>"><?= $book['email'] ?></a></td>
                <td><a href="tel:<?= $book['phone'] ?>"><?= $book['phone'] ?></a></td>
                <td><?= $book['city'] ?></td>
                <td><?= $book['clubName'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    </section>
</body>

</html>