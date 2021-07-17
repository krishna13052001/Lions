<?php
include './../creds.php';
session_start();
$con = new mysqli($host, $user, $pass, $dbname);
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (!isset($_SESSION['super-id'])) {
    header("Location: ./super-login.php");
} else {
    $id = $_SESSION['super-id'];
    $super = $con->query("SELECT * FROM `super` WHERE `id` = '$id'");
    $super = $super->fetch_assoc();

    if (isset($_POST['add-report'])) {
        $name = $_POST['name'];
        $name = strip_tags($name);
        $name = $con->real_escape_string($name);

        $stars = $_POST['stars'];
        $stars = strip_tags($stars);
        $stars = $con->real_escape_string($stars);

        if ($_POST['expiry']) {
            $expiry = $_POST['expiry'];
            $expiry = strip_tags($expiry);
            $expiry = $con->real_escape_string($expiry);

            $con->query("INSERT INTO `reports`(`name`, `star`, `expiry`) VALUES ('$name', '$stars', '$expiry')");
        } else {
            $con->query("INSERT INTO `reports`(`name`, `star`) VALUES ('$name', '$stars')");
        }
    } else if (isset($_GET['delete-id'])) {
        $deleteId = $_GET['delete-id'];
        if ($con->query("DELETE FROM `reports` WHERE `id` = '$deleteId'")) {
            header("Location: ./add-report.php");
        }
    }
    $reports = $con->query("SELECT * FROM `reports`");
    $reports = $reports->fetch_all(MYSQLI_ASSOC);
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
    <section class="members">
        <div class="container">
            <h2 class="text-center">Welcome
                <?php echo $super['title'] . ' ' . $super['name'] ?>
            </h2>
            <h4 class="text-center">Edit Member</h4>
            <form action="./add-report.php" method="post" class="form-group">
                <p>Enter Report Name*</p>
                <input type="text" name="name" id="" class="form-control" required placeholder="Enter Report Name*">
                <br>
                <p>Enter Stars*</p>
                <input type="number" name="stars" id="" class="form-control" required placeholder="Enter Star Points*">
                <br>
                <p>Enter Expiry Date</p>
                <input type="datetime-local" name="expiry" id="" class="form-control" placeholder="Enter Expiry Date*">
                <br>
                <input type="submit" value="Create" class="btn btn-success" name="add-report">
            </form>
            <h4 class="text-center">All Reports</h4>
            <ul class="list-group">
                <?php foreach ($reports as $report) : ?>
                <li class="list-group-item">
                    <?= "Report: " . $report['name'] . "<br />" . "Stars: " . $report['star'] ?><br>
                    <a style="color: firebrick; text-decoration: none;"
                        href="./add-report.php?delete-id=<?= $report['id'] ?>">Delete</a>
                </li>
                <?php endforeach ?>
            </ul>
            <br>
        </div>
    </section>
    <?php include './footer.php' ?>
</body>

</html>