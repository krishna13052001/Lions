<?php
include './../creds.php';
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
    if (isset($_POST['change'])) {
        $cpass = $_POST['cpass'];
        $npass1 = $_POST['npass1'];
        $npass2 = $_POST['npass2'];

        $password = $user['password'];
        if (password_verify($cpass, $password) && $npass1 === $npass2) {
            $npass2 = password_hash($npass1, PASSWORD_BCRYPT);
            if (mysqli_query($con, "UPDATE `users` SET `password` = '$npass2' WHERE `id` = '$id'")) {
                echo "<script>alert(`Password Updated!`)</script>";
            }
        } else {
            echo "<script>alert(`Invalid password!`)</script>";
        }
    }
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<style>

</style>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <?php
    include './meta.php';
    ?>
    <title>
        <?php echo $member; ?> Home | 3234D2
    </title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="./../bootstrap-4.1.3-dist/css/bootstrap.min.css" />
    <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
    <script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
    <script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">

</head>


<?php include './header.php' ?>

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

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="../assets/img/user.png" class="user-img-radious-style"> <span
                                class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">
                                <?php echo $user['title'] . ' <br><em>"' . $user['firstName'] . ' ' . $user['middleName'] . ' ' . $user['lastName'] . '"</em>' ?>
                            </div>
                            <hr>

                            <a href="./member-login.php" class="dropdown-item has-icon"> <i class="far
										fa-user"></i>Update Profile</a>

                            <a href="./reset.php" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                                Change Password
                            </a>

                            <div class="dropdown-divider"></div>
                            <a href="./logout.php" class="dropdown-item has-icon text-danger"> <i
                                    class="fas fa-sign-out-alt"></i>
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

            <div class="main-content">
                <section class="section">
                    <?php if (
                strpos(strtolower($user['title']), 'president') !== false ||
                strpos(strtolower($user['title']), 'club president') !== false ||
                strpos(strtolower($user['title']), 'club secretary') !== false ||
                strpos(strtolower($user['title']), 'secretary') !== false ||
                strpos(strtolower($user['title']), 'district governor') !== false ||
                strpos(strtolower($user['title']), 'cabinet secretary') !== false ||
                strpos(strtolower($user['title']), 'cabinet treasurer') !== false ||
                strpos(strtolower($user['title']), 'club treasurer') !== false
            ) {
                $power = true;
            } else {
                $power = false;
            } ?>
                    <!-- <section class="members"> -->
                        <div class="container">
                            <h2 class="text-center">Welcome
                                <?php echo $user['title'] . ' ' . $user['firstName'] . ' ' . $user['middleName'] . ' ' . $user['lastName'] ?>
                            </h2>
                            <h4 class="text-center">Change Password</h4>
                            <form action="./reset.php" method="post" class="form-group">
                                <p>Current Password*</p>
                                <input type="password" name="cpass" id="cpass" required placeholder="Current Password*"
                                    class="form-control">
                                <p>Enter New Password*</p>
                                <input type="password" name="npass1" id="npass1" required placeholder="New Password*"
                                    class="form-control">
                                <br>
                                <p class="alert alert-danger" role="alert" id="err1">Password size must be greater than
                                    8</p>
                                <p>Confirm New Password*</p>
                                <input type="password" name="npass2" id="npass2" required
                                    placeholder="Confirm New Password*" class="form-control">
                                <br>
                                <p class="alert alert-danger" role="alert" id="err2">Passwords do not match!</p>
                                <br>
                                <input type="submit" value="Change Password" class="btn btn-danger" id="chng"
                                    name="change">
                            </form>
                            <br>
                            <h5 class="text-center">Note: We do not store your passwords directly and hence won't send
                                your passwords
                                via emails or messages.<br>
                                <br>
                            </h5>
                    <!-- </section> -->
                </section>
            </div>
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