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

    if (isset($_GET['id'])) {
        $clubId = $_GET['id'];
        $club = $con->query("SELECT * FROM `clubs` WHERE `clubId` = '$clubId'");
        $club = $club->fetch_assoc();

        // $total_amount_spent = $con->query("SELECT SUM(a.`amount`) AS `a-amount`, SUM(e.`amount`) AS `e-amount` FROM `activities` a, `events` e WHERE a.`clubId` = '$clubId' OR e.`clubId` = '$clubId'")->fetch_assoc();
         $total_amount_spent = $con->query("SELECT SUM(a.`amount`) AS `a-amount` FROM `activities` a WHERE a.`clubId` = '$clubId'")->fetch_assoc();
         $total_amount_spent = (int)($total_amount_spent['a-amount']);
        //  $total_amount_spent = (int)($total_amount_spent['a-amount']) + (int)($total_amount_spent['e-amount']);
       


        if (!isset($_GET['month'])) {
            header("Location: ./club-performance.php?id=" . $clubId . "&month=" . $currentMonth);
        } else {
            $month = $_GET['month'];
        }

        $activities = $con->query("SELECT DISTINCT a.*, t.`placeholder` FROM `activities` a INNER JOIN `activitytype` t ON (a.`activityType` = t.`type` AND a.`activitySubType` = t.`sub-type` AND a.`activityCategory` = t.`category`) WHERE `clubId` = '$clubId' ORDER BY `activityId` DESC ");
        $events = $con->query("SELECT * FROM `events` WHERE `clubId` = '$clubId' ORDER BY `eventId` DESC ");
        $news = $con->query("SELECT * FROM `news` WHERE `clubId` = '$clubId' ORDER BY `newsId` DESC ");
        $members = $con->query("SELECT * FROM `users` WHERE `clubId` = '$clubId'");
        $submittedReports = $con->query("SELECT * FROM `updated-reports`, `reports` WHERE `updated-reports`.`clubId` = '$clubId' AND `updated-reports`.`month` = '$month' AND `updated-reports`.`reportId` = `reports`.`id` AND `reports`.`month` = '$month'");
        $imgProof = $con->query("SELECT * FROM `admin-images` WHERE `clubId` = '$clubId' AND `month` = '$month'");

        if (isset($_GET['category'], $_GET['cid'], $_GET['delete'])) {
            $status = 'error';
            $response = array();
            $category = $_GET['category'];
            $id = $_GET['cid'];

            if ($category == 'news') {
                $sql = "DELETE FROM `news` WHERE `newsId` = '$id'";
            } else if ($category == 'activities') {
                $my_activity = $con->query("SELECT * FROM `activities` WHERE `activityId` = '$id'")->fetch_assoc();
                $prevStar = $club['activityStar'];

                $my_activityType = $my_activity['activityType'];
                $my_activitySubType = $my_activity['activitySubType'];
                $my_activityCategory = $my_activity['activityCategory'];
                $my_peopleServed = $my_activity['peopleServed'];

                $activity_type = $con->query("SELECT * FROM `activitytype` WHERE `type` = '$my_activityType' AND `sub-type` = '$my_activitySubType' AND `category` = '$my_activityCategory'")->fetch_assoc();
                
                
                $stars = (int)(($my_peopleServed / (int)$activity_type['beneficiaries']) * (int)$activity_type['star']);

                $stars = (int)($prevStar) - (int)($stars);

                $con->query("UPDATE `clubs` SET `activityStar`='$stars' WHERE `clubId` = '$clubId'");

                $sql = "DELETE FROM `activities` WHERE `activityId` = '$id'";
            } else if ($category == 'events') {
                $sql = "DELETE FROM `events` WHERE `eventId` = '$id'";
            }
            if (mysqli_query($con, $sql)) {
                $imgSql = "SELECT * FROM `images` WHERE `category` = '$category' AND `categoryId` = '$id'";
                $images = mysqli_query($con, $imgSql);
                while ($path = mysqli_fetch_array($images)) {
                    unlink($path['img']);
                }
                $imgSql = "DELETE FROM `images` WHERE `category` = '$category' AND `categoryId` = '$id'";
                if (mysqli_query($con, $imgSql)) {
                    header("Location: ./club-performance.php?id=" . $clubId);
                }
                $status = 'success';
            }
            $response += array('status' => $status);
        }
    }
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
    <title>Club Performance | 3234D2</title>
</head>

<body>
    <?php include "./header.php" ?>
    <section class="members">
        <h2 class="text-center">Welcome
            <?php echo $super['title'] . ' ' . $super['name'] ?>
        </h2>
        <h4 class="text-center">
            Performance of <?= $club['clubName'] ?>
            <br>
            Reports: <?= $club['stars'] . " " ?> <i class="fa fa-star" style="color:goldenrod"></i><br>
            Activities: <?= $club['activityStar'] . " " ?> <i class="fa fa-star" style="color:orange"></i><br>
            Total Amount Spent: <span style="color: dodgerblue;"><i class="fa fa-inr"
                    aria-hidden="true"></i><?= $total_amount_spent ?></span>
        </h4>
        <style>
        .col-md-5 {
            max-height: 30rem;
            overflow: auto;
            padding: 1rem;
            background: whitesmoke;
            margin: 1rem auto;
        }
        </style>
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-5">
                        <h4>Basic Information</h4>
                        <div class="container">
                            <strong>Name: </strong><em><?= $club['clubName'] ?></em><br>
                            <strong>Id: </strong><em><?= $club['clubId'] ?></em><br>
                            <strong>Last Update: </strong><em><?= $club['last-updated'] ?></em><br>
                            <strong>Report Submitted?
                            </strong><em><?= $club['month-' . $month] == 1 ? 'True' : 'False' ?></em><br>
                            <strong>Activities Reported: </strong><em><?= $activities->num_rows ?></em><br>
                            <strong>Events Reported: </strong><em><?= $events->num_rows ?></em><br>
                            <strong>News Reported: </strong><em><?= $news->num_rows ?></em><br>
                            <strong>Total Members: </strong><em><?= $members->num_rows ?></em><br>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <h4>Admin Reporting</h4>
                        <select name="month" id="month" class="form-control">
                            <option value="" disabled selected>Select Month</option>
                            <option value="7">July 2020</option>
                            <option value="8">August 2020</option>
                            <option value="9">September 2020</option>
                            <option value="10">October 2020</option>
                            <option value="11">November 2020</option>
                            <option value="12">December 2020</option>
                            <option value="1">January 2021</option>
                            <option value="2">February 2021</option>
                            <option value="3">March 2021</option>
                            <option value="4">April 2021</option>
                            <option value="5">May 2021</option>
                            <option value="6">June 2021</option>
                        </select>
                        <br>
                        <ul class="list-group">
                            <?php while ($row = mysqli_fetch_assoc($submittedReports)) : ?>
                            <li class="list-group-item">
                                <strong>Report For: <?= $row['title'] ?></strong><br>
                                <strong>Awarded Stars: <?= (int)$row['stars'] * (int)$row['multiplier'] ?></strong><br>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                        <ul class="list-group">
                            <?php foreach ($imgProof as $img) : ?>
                            <li class="list-group-item">
                                <a href="/<?= $img['img'] ?>" target="_blank">
                                    <img src="/<?= $img['img'] ?>" style="height: 4rem; width: 4rem;" />
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <script>
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var currentMonth = url.searchParams.get("month");
                    var currentId = url.searchParams.get('id');

                    const month = document.querySelector("#month");
                    month.value = currentMonth;
                    month.addEventListener('change', () => {
                        window.location.href = `./club-performance.php?id=${currentId}&month=${month.value}`
                    })
                    </script>
                    <div class="col-md-5">
                        <h4>Activity Reporting</h4>
                        <ul class="list-group">
                            <?php while ($row = mysqli_fetch_assoc($activities)) : ?>
                            <?php
                                $my_activityType = $row['activityType'];
                                $my_activitySubType = $row['activitySubType'];
                                $my_activityCategory = $row['activityCategory'];
                                $my_peopleServed = $row['peopleServed'];
                                $activity_type = $con->query("SELECT * FROM `activitytype` WHERE `type` = '$my_activityType' AND `sub-type` = '$my_activitySubType' AND `category` = '$my_activityCategory'")->fetch_assoc();
                                $stars = (int)(($my_peopleServed / (int)$activity_type['beneficiaries']) * (int)$activity_type['star']);
                                $stars = $stars >= 1 ? $stars : 1;
                                ?>
                            <li class="list-group-item">
                                <strong>Awarded: </strong> <?= $stars ?> <i class="fa fa-star" style="color:orange"></i>
                                <br>
                                <strong>Amount: <?= $row['amount'] ?></strong><br>
                                <strong>Title: <?= $row['activityTitle'] ?></strong><br>
                                <strong>City: <?= $row['city'] ?></strong><br>
                                <strong>Date: <?= $row['date'] ?></strong><br>
                                <strong>Officers: <?= $row['cabinetOfficers'] ?></strong><br>
                                <strong>Lion Hours: <?= $row['lionHours'] ?></strong><br>
                                <strong>Media Coverage: <?= $row['mediaCoverage'] ?></strong><br>
                                <strong>No. of <?= $row['placeholder'] ?>: <?= $row['peopleServed'] ?></strong><br>
                                <strong>Type: <?= $row['activityType'] ?></strong><br>
                                <strong>Sub Type: <?= $row['activitySubType'] ?></strong><br>
                                <strong>Category: <?= $row['activityCategory'] ?></strong><br>
                                <strong>Place: <?= $row['place'] ?></strong><br>
                                <strong><a
                                        href="<?= $actual_link ?>&category=activities&cid=<?= $row['activityId'] ?>&delete=true"
                                        style="color: firebrick; text-decoration:none">Delete</a></strong>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <div class="col-md-5">
                        <h4>Event Reporting</h4>
                        <ul class="list-group">
                            <?php while ($row = mysqli_fetch_assoc($events)) : ?>
                            <li class="list-group-item">
                                <strong>Amount: <?= $row['amount'] ?></strong><br>
                                <strong>Title: <?= $row['eventTitle'] ?></strong><br>
                                <strong>City: <?= $row['district'] ?></strong><br>
                                <strong>Date: <?= $row['date'] ?></strong><br>
                                <strong>Officers: <?= $row['chiefGuest'] ?></strong><br>
                                <strong>Type: <?= $row['eventType'] ?></strong><br>
                                <strong><a
                                        href="<?= $actual_link ?>&category=events&cid=<?= $row['eventId'] ?>&delete=true"
                                        style="color: firebrick; text-decoration:none">Delete</a></strong>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <div class="col-md-5">
                        <h4>News Reporting</h4>
                        <ul class="list-group">
                            <?php while ($row = mysqli_fetch_assoc($news)) : ?>
                            <li class="list-group-item">
                                <strong>Title: <?= $row['newsTitle'] ?></strong><br>
                                <strong>City: <?= $row['newsPaperLink'] ?></strong><br>
                                <strong>Date: <?= $row['date'] ?></strong><br>
                                <strong><a
                                        href="<?= $actual_link ?>&category=news&cid=<?= $row['newsId'] ?>&delete=true"
                                        style="color: firebrick; text-decoration:none">Delete</a></strong>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <?php include "./footer.php" ?>
</body>
<?php $con->close(); ?>

</html>