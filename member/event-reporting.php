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
    $member = $user['title'];
    $myEvents = $con->query("SELECT DISTINCT e.*, i.`img` FROM `events` e INNER JOIN `images` i ON e.`eventId` = i.`categoryId` AND i.`category` = 'events' WHERE e.`clubId` = '$user[clubId]' GROUP BY e.`eventId`")->fetch_all(MYSQLI_ASSOC);
    if (isset($_POST['add-event'])) {

        $status = 'error';
        $message = '';
        $error = array();
        $response = array();

        $eventTitle = $_POST['eventTitle'];
        $eventTitle = strip_tags($eventTitle);
        $eventTitle = mysqli_real_escape_string($con, $eventTitle);

        //Check if amount is present, present => paid, else free
        if (isset($_POST['amount'])) {
            $amount = $_POST['amount'];
            $amount = strip_tags($amount);
            $amount = mysqli_real_escape_string($con, $amount);
        } else {
            $amount = 0;
        }

        $chiefGuest = $_POST['chiefGuest'];
        $chiefGuest = strip_tags($chiefGuest);
        $chiefGuest = mysqli_real_escape_string($con, $chiefGuest);

        if (isset($_POST['date']) && $_POST['date'] != null) {
            $date = $_POST['date'];
            $date = strip_tags($date);
            $date = mysqli_real_escape_string($con, $date);
        } else {
            $date = date('Y-m-d');
        }

        if (isset($_POST['venue']) && $_POST['venue'] != null) {
            $venue = $_POST['venue'];
            $venue = strip_tags($venue);
            $venue = mysqli_real_escape_string($con, $venue);
        } else {
            $venue = 'online';
        }

        $description = $_POST['description'];
        $description = strip_tags($description);
        $description = mysqli_real_escape_string($con, $description);

        $eventType = $_POST['eventType'];
        $eventType = strip_tags($eventType);
        $eventType = mysqli_real_escape_string($con, $eventType);

        if ($eventType == 'free') {
            $amount = 0;
        }

        $authorId = $id;

        $clubId = $user['clubId'];

        $addEventSql = "INSERT INTO `events`(`eventTitle`, `amount`, `chiefGuest`, `date`, `description`, `eventType`, `authorId`, `clubId`, `district`) VALUES ('$eventTitle', '$amount', '$chiefGuest', '$date', '$description', '$eventType', '$authorId', '$clubId', '$venue')";
        $getEventIdSql = "SELECT `eventId` FROM `events` WHERE `eventTitle` = '$eventTitle' AND `date` = '$date' AND `authorId` = '$authorId' AND `clubId` = '$clubId' AND `eventType` = '$eventType' ";

        if (mysqli_query($con, $addEventSql)) {
            $eventId = mysqli_query($con, $getEventIdSql);
            $eventId = mysqli_fetch_array($eventId);
            $eventId = $eventId['eventId'];

            foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
                $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
                $imageUploadPath = './../' . $uploadPath . $file_name;

                $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                if (in_array(strtolower($ext), $allowTypes)) {
                    $file_tmp = $_FILES["image"]["tmp_name"][$key];
                    $compressedImage = compressImage($file_tmp, $imageUploadPath, 50);
                    $addImageSql = "INSERT INTO `images`(`img`, `category`, `categoryId`) VALUES ('$compressedImage', 'events', '$eventId')";
                    if (mysqli_query($con, $addImageSql)) {
                        $status = 'success';
                    }
                } else {
                    array_push($error, "$file_name, ");
                }
            }
        }

        $response += array('status' => $status);
        $response += array('imgError' => $error);
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
    <title>Event Reporting | 3234D2</title>
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='../assets/img/favicon.ico' />
    <link rel="stylesheet" href="./../bootstrap-4.1.3-dist/css/bootstrap.min.css" />
    <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
  <script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
  <script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./style.css" />
</head>

<body>
     
<div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
                        <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i>
                            </a></li>
                        
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">

                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="../assets/img/user.png" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title"><?php echo $user['title'] . ' <br><em>"' . $user['firstName'] . ' ' . $user['middleName'] . ' ' . $user['lastName'] . '"</em>' ?></div>
                            <hr>

                            <a href="./member-login.php" class="dropdown-item has-icon"> <i class="far
										fa-user"></i>Update Profile</a>

                            <a href="./reset.php" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                                Change Password
                            </a>

                            <div class="dropdown-divider"></div>
                            <a href="./logout.php" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- side bar -->
            <?php 
                $msg = "";
                if($member === "lion member") { 
                $msg = "Lion Member";
                include './member-sidebar.php';
                } elseif($member === "Club Treasurer") {
                $msg ="Club Treasurer";
                include './clubtreasurer-sidebar.php';
                } elseif($member === "Club Secretary") {
                $msg ="Club Secretary";
                include './clubsecretary-sidebar.php';
                } else {
                $msg ="Club President";
                include './clubpresident-sidebar.php';
                }
            ?>




    <?php include './header.php' ?>
    <div class="main-content">
        <section class="section">
        <?php if ($user['verified'] == 0) {
            echo '<h1 class="text-center">Your Profile isn\'t updated!</h1>';
        } ?>
        <?php
        if (
            strpos(strtolower($user['title']), 'president') !== false ||
            strpos(strtolower($user['title']), 'club president') !== false ||
            strpos(strtolower($user['title']), 'club secretary') !== false ||
            strpos(strtolower($user['title']), 'secretary') !== false ||
            strpos(strtolower($user['title']), 'district governor') !== false ||
            strpos(strtolower($user['title']), 'cabinet secretary') !== false ||
            strpos(strtolower($user['title']), 'cabinet treasurer') !== false ||
            strpos(strtolower($user['title']), 'club treasurer') !== false
        ) {
            echo '<h2 class="text-center">You can only update Free Events!</h2>';
            $power = true;
        } else {
            echo '<h2 class="text-center">You can\'t access this feature!</h2>';
            $power = false;
        }
        ?>
        <h2 class="text-center">Event Creation</h2>
        <form action="./event-reporting.php" method="post" class="form-group" enctype="multipart/form-data" id="form">
            <?php
            if ($power) : ?>
                <input type="text" name="eventTitle" id="" class="form-control" required placeholder="Enter Event Title*">
                <br>
                <select name="eventType" required class="form-control">
                    <option value="" disabled selected>Select Event Type</option>
                    <option value="free">Free</option>
                </select><br>
                <input type="text" name="venue" id="" class="form-control" placeholder="Enter Venue*" required><br>
                <input type="number" name="amount" id="" class="form-control" placeholder="Enter Amount*" value="0" hidden>
                <input type="text" name="chiefGuest" id="" class="form-control" placeholder="Enter Chief Guest Name" value=""><br>
                <textarea name="description" id="" required placeholder="Enter Event Description*" class="form-control"></textarea><br>
                <input type="file" name="image[]" id="" multiple accept="image/*" required class="form-control"><br>
                <input type="submit" value="Create" name="add-event" class="btn btn-success">
            <?php endif ?>
            <?php if (!$power) : ?>
                <script>
                    alert("You Can't use this feature!")
                    window.location.href = "./home.php";
                </script>
            <?php endif ?>
        </form>
        <script>
            $("input[type='submit']").click(function(e) {
                var $fileUpload = $("input[type='file']");
                if (parseInt($fileUpload.get(0).files.length) > 3) {
                    alert("You can only upload a maximum of 3 files");
                    e.preventDefault();
                    // window.location.href = "./activity-reporting.php";
                }
            });

            $('#form').on('submit', (e) => {
                let r = confirm("Are you sure you want to submit?");
                if (!r) {
                    e.preventDefault()
                }
            })
        </script>
        <p class="text-center"><b>Disclaimer: Any false/irrelevant/unparliamentary data/information fed will lead to
                strict actions being taken against the respective clubs/members, that may lead to BAN and/or REMOVAl of
                the
                involved clubs/members. </b><br><br></p>
    
    <hr>
    <div class="container">
        <div class="row">
            <?php foreach($myEvents as $event) : ?>
            <div class="col-md-4 col-6">
                <div class="card" style="width: 100%; margin: 0.5rem auto">
                    <img class="card-img-top" src="<?= $event['img'] ?>" alt="Card image" style="width:100%; height: 10rem;">
                    <div class="card-body">
                      <h4 class="card-title"><?= $event['eventTitle'] ?></h4>
                      <p class="card-text">Date: <?= $event['date'] ?>, Venue: <?= $event['district'] ?></p>
                      <a href="./view-bookings.php?event=<?= $event['eventId'] ?>" class="btn btn-primary stretched-link">View Bookings</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    </section>
    <!-- <?php include './footer.php' ?> -->
    </div>
    </div>
    <script src="../assets/js/app.min.js"></script>
    <!-- JS Libraies -->
    <script src="../assets/bundles/apexcharts/apexcharts.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="../assets/js/page/index.js"></script>
    <!-- Template JS File -->
    <script src="../assets/js/scripts.js"></script>
    <!-- Custom JS File -->
    <script src="../assets/js/custom.js"></script>
</body>

</html>