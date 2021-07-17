<?php
include './../creds.php';
include './../upload-image.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
} else {
    $id = $_SESSION['id'];
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);

    $clubId = $user['clubId'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <title>Zone Reports | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
    <section class="members container">
        <?php if ($user['verified'] == 0) {
            echo '<h1 class="text-center">Your Profile isn\'t updated!</h1>';
        } ?>
        <?php
        if (
            strpos(strtolower($user['title']), 'zone chairperson') !== false
        ) {
            $power = true;
        } else {
            echo '<h2 class="text-center">You can\'t access this feature!</h2>';
            $power = false;
        }
        ?>
        <h2 class="text-center">Zone Reports <br><br></h2>
        <h4 class="text-center"><?= $user['regionName'] ?>, <?= $user['zoneName'] ?></h4>
        <?php if (!$power) : ?>
        <script>
        alert("You Can't use this feature!")
        window.location.href = "./home.php";
        </script>
        <?php endif ?>
        <?php
        $regionName = $user['regionName'];
        $zoneName = $user['zoneName'];
        $myZoneClubs = $con->query("SELECT DISTINCT u.`clubName`, u.`clubId`, c.* FROM `users` u, `clubs` c WHERE u.`regionName` = '$regionName' AND u.`zoneName` = '$zoneName' AND u.`clubId` = c.`clubId` ORDER BY u.`clubName`")->fetch_all(MYSQLI_ASSOC);
        ?>
        <h3 class="text-center">My Clubs</h3>
        <hr>
        <div class="row">
            <?php foreach ($myZoneClubs as $clubs) : ?>
            <?php
                $club_id = $clubs['clubId'];
                $activityCount = $con->query("SELECT COUNT(`activityId`) AS `activity` FROM `activities` WHERE `clubId` = '$club_id'")->fetch_assoc()['activity'];
                ?>
            <div class="col-md-4">
                <h5 class="text-center"><?= $clubs['clubName'] ?></h5>
                <strong>Club Id: <?= $clubs['clubId'] ?></strong>
                <p>
                    Stars Received for Admin Reporting: <?= $clubs['stars'] ?> <i class="fa fa-star"
                        style="color: gold"></i>
                    <br>
                    Stars Received for Activity Reporting: <?= $clubs['activityStar'] ?> <i class="fa fa-star"
                        style="color: orange"></i>
                    <br>
                    Admin reporting for this month:
                    <?= $clubs['month-' . $currentMonth] == 1 ? '<i class="fa fa-check" aria-hidden="true" style="color: teal"></i>' : '<i class="fa fa-times" aria-hidden="true" style="color: firebrick"></i>' ?>
                    <br>
                    Activities Conducted: <?= $activityCount ?>
                    <br>
                    <a href="../view-members.php?clubId=<?= $club_id ?>" target="_blank">View all members</a>
                </p>
                <hr>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php include './footer.php' ?>
</body>

</html>