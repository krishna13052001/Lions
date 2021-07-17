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
    $clubId = $user['clubId'];

    $starsql = "SELECT * FROM `clubs` WHERE `clubId`='$clubId'";
    $starsql = mysqli_query($con, $starsql);
    $starsql = mysqli_fetch_array($starsql);

    $activityP = "SELECT * FROM `activities` WHERE `clubId`='$clubId' AND `status` = 0";
    $activityA = "SELECT * FROM `activities` WHERE `clubId`='$clubId' AND `status` = 1";
    $activityP = mysqli_query($con,$activityP);
    $activityA = mysqli_query($con, $activityA);

    if($activityP){
    $activities_pending = mysqli_num_rows($activityP);
    }
    else{
      $activities_pending=0;
    }

    if($activityA){
      $activities_approved = mysqli_num_rows($activityA);
      }
      else{
        $activities_approved=0;
      }


    $total_activity_points= $starsql['activityStar'];
    $total_admin_points=$starsql['stars'];


    

    if ($user['verified'] != 1) {
        header("Location: ./member-login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <?php
    include './meta.php';
    ?>
    <title>Treasurer Home | 3234D2</title> <!-- General CSS Files -->
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='../assets/img/favicon.ico' />
    <link rel="stylesheet" href="./../bootstrap-4.1.3-dist/css/bootstrap.min.css" />
    <!-- <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./style.css" /> -->

</head>


<?php //include './header.php' 
?>

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
                        <!-- <li>
                            <form class="form-inline mr-auto">
                                <div class="search-element">
                                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                                    <button class="btn" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </li> -->
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
            <?php include "./clubpresident-sidebar.php" ?>

             <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card card-primary">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Activities Pending</h5>
                          <h2 class="mb-3 font-18"><?= $activities_pending ?></h2>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card card-primary">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15"> Activities Approved</h5>
                          <h2 class="mb-3 font-18"><?= $activities_approved ?></h2>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                       
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card card-primary">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Total Admin Points</h5>
                          <h2 class="mb-3 font-18"><?= $total_admin_points ?></h2>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card card-primary">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Total Activity Point</h5>
                          <h2 class="mb-3 font-18"><?= $total_activity_points ?></h2>

                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
    <!-- 
      
    <section class="members">
        <h2 class="text-center" style="text-transform: capitalize !important;">Welcome
            <?php echo $user['title'] . ' <br><em>"' . $user['firstName'] . ' ' . $user['middleName'] . ' ' . $user['lastName'] . '"</em>' ?>
        </h2>
        <div class="col-lg-12 tiles">
            <div class="row">
                <div class="col-md-3 tile">
                    <a href="./activity-reporting.php">
                        <h2 class="text-center">
                            <i class="fa fa-trophy" aria-hidden="true"></i><br>
                            Activity Reporting
                        </h2>
                    </a>
                </div>
                <div class="col-md-3 tile">
                    <a href="./activity-reporting.php">
                        <h2 class="text-center">
                            <i class="fa fa-trophy" aria-hidden="true"></i><br>
                            Approve Activities
                        </h2>
                    </a>
                </div>
                <div class="col-md-3 tile">
                    <a href="./member-login.php">
                        <h2 class="text-center">
                            <i class="fa fa-pencil" aria-hidden="true"></i><br>
                            Update Profile
                        </h2>
                    </a>
                </div>
                <div class="col-md-3 tile">
                    <a href="./reset.php">
                        <h2 class="text-center">
                            <i class="fa fa-unlock-alt" aria-hidden="true"></i><br>
                            Change Password
                        </h2>
                    </a>
                </div>-->
                <!-- <div class="col-md-3 tile">
                    <a href="./admin-reporting.php?month=<?= $currentMonth ?>">
                        <h2 class="text-center">
                            <i class="fa fa-users" aria-hidden="true"></i><br>
                            Admin Reporting
                        </h2>
                    </a>
                </div> -->
                <!--
                <?php if (strpos(strtolower($user['title']), 'zone chairperson') !== false) : ?>
                <div class="col-md-3 tile">
                    <a href="./zone-reports.php">
                        <h2 class="text-center">
                            <i class="fa fa-globe" aria-hidden="true"></i><br>
                            My Zone
                        </h2>
                    </a>
                </div>
                <?php endif; ?>
                <?php if (strpos(strtolower($user['title']), 'region chairperson') !== false) : ?>
                <div class="col-md-3 tile">
                    <a href="./region-reports.php">
                        <h2 class="text-center">
                            <i class="fa fa-globe" aria-hidden="true"></i><br>
                            My Region
                        </h2>
                    </a>
                </div>
                <?php endif; ?>
                <div class="col-md-3 tile">
                    <a href="./logout.php">
                        <h2 class="text-center">
                            <i class="fa fa-sign-out" aria-hidden="true"></i><br>
                            Logout
                        </h2>
                    </a>
                </div>
            </div>
        </div>
    </section> -->
    <!-- <?php include './footer.php' ?> -->


</body>

</html>