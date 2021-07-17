<?php
include './../creds.php';
include './../upload-image.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
} 
 if(isset($_GET['activityId']) && $_GET['activityId'] != '') 
 {
    $id = $_SESSION['id'];
    $actid = $_GET['activityId'];

    $qry = mysqli_query($con,"SELECT * FROM activities WHERE `activityId`='$actid'");
    $activityData = mysqli_fetch_array($qry);



    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);

    $types = $con->query("SELECT DISTINCT `type` FROM `activitytype`");
    $types = $types->fetch_all(MYSQLI_ASSOC);

    $clubId = $user['clubId'];

    // $reportedActivities = $con->query("SELECT DISTINCT a.*, t.`placeholder` FROM `activities` a INNER JOIN `activitytype` t ON (a.`activityType` = t.`type` AND a.`activitySubType` = t.`sub-type` AND a.`activityCategory` = t.`category`) WHERE `clubId` = '$clubId' ORDER BY `activityId` DESC")->fetch_all(MYSQLI_ASSOC); 
    
}

if (isset($_POST['update-activity'])) {
  if(isset($_GET['activityId']) && $_GET['activityId'] != '') 
 { 
  $actId = $_GET['activityId'];
 }
  $status = 'error';
  $message = '';
  $error = array();
  $response = array();

  $amount = $_POST['amount'];
  $amount = strip_tags($amount);
  $amount = mysqli_real_escape_string($con, $amount);

  $activityTitle = $_POST['activityTitle'];
  $activityTitle = strip_tags($activityTitle);
  $activityTitle = mysqli_real_escape_string($con, $activityTitle);

  $city = $_POST['city'];
  $city = strip_tags($city);
  $city = mysqli_real_escape_string($con, $city);

  if (isset($_POST['date']) && $_POST['date'] != null) {
      $date = $_POST['date'];
      $date = strip_tags($date);
      $date = mysqli_real_escape_string($con, $date);
  } else {
      $date = date('Y-m-d');
      $date = mysqli_real_escape_string($con, $date);
  }

  $cabinetOfficers = $_POST['cabinetOfficers'];
  $cabinetOfficers = strip_tags($cabinetOfficers);
  $cabinetOfficers = mysqli_real_escape_string($con, $cabinetOfficers);

  $description = $_POST['description'];
  $description = strip_tags($description);
  $description = mysqli_real_escape_string($con, $description);

  $lionHours = $_POST['lionHours'];
  $lionHours = strip_tags($lionHours);
  $lionHours = mysqli_real_escape_string($con, $lionHours);

  $mediaCoverage = $_POST['mediaCoverage'];
  $mediaCoverage = strip_tags($mediaCoverage);
  $mediaCoverage = mysqli_real_escape_string($con, $mediaCoverage);

  $peopleServed = $_POST['peopleServed'];
  $peopleServed = strip_tags($peopleServed);
  $peopleServed = mysqli_real_escape_string($con, $peopleServed);

  $activityType = $_POST['activityType'];
  $activityType = strip_tags($activityType);
  $activityType = mysqli_real_escape_string($con, $activityType);

  $activitySubType = $_POST['activitySubType'];
  $activitySubType = strip_tags($activitySubType);
  $activitySubType = mysqli_real_escape_string($con, $activitySubType);

  $activityCategory = $_POST['activityCategory'];
  $activityCategory = strip_tags($activityCategory);
  $activityCategory = mysqli_real_escape_string($con, $activityCategory);

  $place = $_POST['place'];
  $place = strip_tags($place);
  $place = mysqli_real_escape_string($con, $place);

  $authorId = $id;

  $clubId = $user['clubId'];

  $updateActivitySql = "UPDATE `activities` SET `amount` = '$amount', `activityTitle`='$activityTitle', `city`='$city', `date`='$date', `cabinetOfficers`='$cabinetOfficers', `description`='$description', `lionHours` ='$lionHours', `mediaCoverage`='$mediaCoverage', `peopleServed`='$peopleServed', `activityType`='$activityType', `place`='$place', `activitySubType`='$activitySubType', `activityCategory`='$activityCategory' WHERE `activityId`= '$actId' ";

  $getActivitySql = "SELECT `activityId` FROM `activities` WHERE `activityTitle` = '$activityTitle' AND `date` = '$date' AND `authorId` = '$authorId' AND `clubId` = '$clubId'";

  if (mysqli_query($con, $updateActivitySql)) {
      $activityId = mysqli_query($con, $getActivitySql);
      $activityId = mysqli_fetch_array($activityId);
      $activityId = $activityId['activityId'];

      $points = $con->query("SELECT * FROM `activitytype` WHERE `type` = '$activityType' AND `sub-type` = '$activitySubType' AND `category` = '$activityCategory'");
      $points = $points->fetch_array();
      $stars = (int)(($peopleServed / $points['beneficiaries']) * $points['star']);
      $myClub = $con->query("SELECT * FROM `clubs` WHERE `clubId` = '$clubId'");
      $myClub = $myClub->fetch_assoc();
      $myClubPoints = $myClub['activityStar'];
      if ($stars <= 1) {
          $stars = 1;
      }
      $stars = $myClubPoints + $stars;
      $con->query("UPDATE `clubs` SET `activityStar`='$stars' WHERE `clubId` = '$clubId'");

      foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
          $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
          $imageUploadPath = './../' . $uploadPath . $file_name;

          $ext = pathinfo($file_name, PATHINFO_EXTENSION);

          if (in_array(strtolower($ext), $allowTypes)) {
              $file_tmp = $_FILES["image"]["tmp_name"][$key];
              $compressedImage = compressImage($file_tmp, $imageUploadPath, 50);
              $addImageSql = "INSERT INTO `images`(`img`, `category`, `categoryId`) VALUES ('$compressedImage', 'activities', '$activityId')";
              if (mysqli_query($con, $addImageSql)) {
                  $status = 'success';
              }
          } else {
              array_push($error, "$file_name, ");
          }
      }
      header('location:activity-reporting.php');
    die();
  }
 
  $response += array('status' => $status);
  $response += array('imgError' => $error);
  // echo json_encode($response);
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
    <title>Activity Reporting | 3234D2</title>

    <link rel="stylesheet" href="../assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="../assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='../assets/img/favicon.ico' />
    <link rel="stylesheet" href="./../bootstrap-4.1.3-dist/css/bootstrap.min.css" />


    <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
    <script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
    <script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>

    <!-- <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./style.css" /> -->

</head>


<?php //include './header.php' 
?>

<body class="light light-sidebar theme-light">
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
                        <li>
                            <form class="form-inline mr-auto">
                                <div class="search-element">
                                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                                    <button class="btn" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </li>
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
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html"> <img alt="image" src="../img/logo.png" class="header-logo" /> <span class="logo-name"></span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Main</li>
                        <li class="dropdown active ">
                            <a href="./home-treasurer.php" class="nav-link"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i class="fas fa-tasks"></i></i><span>Activity Reporting</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link " href="./activity-reporting.php">Report Activity</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-check-square"></i><span>Approve Activities</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="./pending-activities.php">Pending Activities</a></li>
                                <li><a class="nav-link" href="./approved-activities">Approved Activities</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-user"></i><span>Admin Reporting</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="email-inbox.html">Inbox</a></li>
                                <li><a class="nav-link" href="email-compose.html">Compose</a></li>
                                <li><a class="nav-link" href="email-read.html">read</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-newspaper"></i><span>News</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="./news-reporting.php">News Reporting</a></li>
                                <li><a class="nav-link" href="./news-reporting.php#verify news">Verify News</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-calendar-alt"></i><span>Events</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="./event-reporting.php">Event Creation</a></li>
                                
                            </ul>
                        </li>

                    </ul>
                </aside>
            </div>

            <!-- Main Content -->
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
                        $power = true;
                    } else {
                        echo '<h2 class="text-center">You can\'t access this feature!</h2>';
                     
                        $power = false;
                    }
                    ?>

                    <?php if (!$power) : ?>
                        <script>
                            alert("You Can't use this feature!")
                            window.location.href = "./home-secretary.php";
                        </script>
                       <!-- <?php  echo $power; ?> -->
                    <?php endif ?>

                    <!-- form -->
                    <div class="card-body">
                        <h2>Activity Reporting</h2>
                        <hr>
                        <form method="post" class="form-group" enctype="multipart/form-data" id="form">
                            <div style="display: flex; justify-content:space-around; ">
                                <div style="flex: 0.5; padding:12px;">
                                    <div class="form-group">
                                        <input type="text" name="activityTitle" value="<?php echo $activityData['activityTitle']; ?>" id="" placeholder="Enter Activity Title*" class="form-control" required>
                                    </div>
                                    <div class="form-group">

                                        <input type="text" name="cabinetOfficers" value="<?php echo $activityData['cabinetOfficers']; ?>" id="" placeholder="Enter Cabinet Officer Name" class="form-control">
                                    </div>
                                    <div class="form-group">

                                        <textarea class="form-control" name="description" id="" placeholder="Enter Description*" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input value="<?php echo $activityData['amount']; ?>" type="number" id="" name="amount" placeholder="Enter Amount*" class="form-control" required>
                                    </div>
                                    <div class="form-group">

                                        <input type="number"  value="<?php echo $activityData['lionHours'];?>" name="lionHours" id="" placeholder="Enter Lion Hours*" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <select name="mediaCoverage" id="" class="form-control" required>
                                            <option value="<?php echo $activityData['lionHours'];?>" disabled selected>Media Coverage</option>
                                            <option value="true">Yes</option>
                                            <option value="false">No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <span>Image*</span>
                                        <input type="file"  name="image[]" id="" multiple accept="image/*" required class="form-control">
                                    </div>
                                </div>
                                <div style="flex: 0.5; padding:12px">

                                    <div class="form-group">
                                        <select id="activityType" name="activityType" class="form-control" required>
                                            <option value="<?php echo $activityData['activityType'];?>" disabled selected>Select Activity Type</option>
                                            <?php foreach ($types as $type) : ?>
                                                <option value="<?= $type['type'] ?>"><?= $type['type'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="activitySubType" id="activitySubType" class="form-control" required>
                                            <option value="<?php echo $activityData['activitySubType'];?>" disabled selected>Select Activity Sub Type
                                            </option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <select name="activityCategory" id="activityCategory" class="form-control" required>
                                            <option  value="<?php echo $activityData['activitySubType'];?>" disabled selected>Select Activity Category
                                            </option>


                                        </select>
                                    </div>

                                    <div class="form-group">

                                        <input type="text" value="<?php echo $activityData['place']; ?>" name="place" id="" placeholder="Enter Place*" class="form-control">
                                    </div>

                                    <div class="form-group">

                                        <input type="text"  value="<?php echo $activityData['city'];?>" name="city" id="" placeholder="Enter City" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <input  value="<?php echo $activityData['date'];?>" type="date" name="date" id="" placeholder="Enter Date*" class="form-control" required>

                                    </div>

                                    <div class="form-group">
                                        <input type="number"  value="<?php echo $activityData['peopleServed'];?>" name="peopleServed" id="placeholder" class="form-control" placeholder="Please fill the aforementioned fileds first*" min="0" required>
                                       

                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            if ($power) {
                                echo '<div class=" text-left" style="background: white;"> 
                        <input type="submit" class="btn btn-success" value="Create" name="update-activity" />
                        </div>';
                            }
                            ?>
                         
                        </form>
                        <hr>
                        <script src="./../url.js"></script>
                        <script>
                            $("input[type='submit']").click(function(e) {
                                var $fileUpload = $("input[type='file']");
                                if (parseInt($fileUpload.get(0).files.length) > 3) {
                                    alert("You can only upload a maximum of 3 files");
                                    e.preventDefault();
                                    // window.location.href = "./activity-reporting.php";
                                }
                            });


                            const activityType = document.querySelector('#activityType');
                            const activitySubType = document.querySelector('#activitySubType');
                            const activityCategory = document.querySelector('#activityCategory');
                            const placeholder = document.querySelector('#placeholder');

                            activityType.addEventListener('change', async () => {
                                await fetch(base + `?get-subtype=${activityType.value}`).then(async (res) => {
                                    await res.json().then(res => {
                                        activitySubType.innerHTML = `
                    <option value="" disabled selected>Select Activity Sub Type</option>
                    `;
                                        res.forEach(type => {
                                            activitySubType.innerHTML += `
                        <option value="${type['sub-type']}">${type['sub-type']}</option>
                        `;
                                        })
                                    })
                                })
                            });

                            activitySubType.addEventListener('change', async () => {
                                await fetch(base + `?get-category=${activitySubType.value}`).then(async (res) => {
                                    await res.json().then(res => {
                                        activityCategory.innerHTML = `
                    <option value="" disabled selected>Select Activity Category</option>
                    `;
                                        res.forEach(type => {
                                            activityCategory.innerHTML += `
                        <option value="${type['category']}">${type['category']}</option>
                        `;
                                        })
                                    })
                                })
                            });

                            activityCategory.addEventListener('change', async () => {
                                await fetch(base + `?get-placeholder=${activityCategory.value}`).then(async (res) => {
                                    await res.json().then(res => {
                                        placeholder.placeholder = `Enter Number of ${res.placeholder}`
                                    })
                                })
                            });

                            $('#form').on('submit', (e) => {
                                let r = confirm("Are you sure you want to submit?");
                                if (!r) {
                                    e.preventDefault()
                                }
                            });
                        </script>

<p class="text-center"><b>Disclaimer: Any false/irrelevant/unparliamentary data/information fed will lead to
                                strict actions being taken against the respective clubs/members, that may lead to BAN and/or REMOVAl of
                                the
                                involved clubs/members. </b><br><br></p>
                        <br>
                        <br>
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

    <script src="../assets/bundles/datatables/datatables.min.js"></script>
    <script src="../assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/bundles/datatables/export-tables/dataTables.buttons.min.js"></script>
    <script src="../assets/bundles/datatables/export-tables/buttons.flash.min.js"></script>
    <script src="../assets/bundles/datatables/export-tables/jszip.min.js"></script>
    <script src="../assets/bundles/datatables/export-tables/pdfmake.min.js"></script>
    <script src="../assets/bundles/datatables/export-tables/vfs_fonts.js"></script>
    <script src="../assets/bundles/datatables/export-tables/buttons.print.min.js"></script>
    <script src="../assets/js/page/datatables.js"></script>

</body>

</html>