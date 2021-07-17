<?php
include './../creds.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
}
else {
    $id = $_SESSION['id'];
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);

    
    $clubId = $user['clubId'];

    
    $reportedActivities = $con->query("SELECT DISTINCT a.*, t.`placeholder` FROM `activities` a INNER JOIN `activitytype` t ON (a.`activityType` = t.`type` AND a.`activitySubType` = t.`sub-type` AND a.`activityCategory` = t.`category`) WHERE `clubId` = '$clubId' AND `status`= 0 ORDER BY `activityId` DESC")->fetch_all(MYSQLI_ASSOC);
    

    if ($user['verified'] != 1) {
        header("Location: ./member-login.php");
    }
}
if(isset($_GET['type']) &&$_GET['type'] != ''){
    $type = mysqli_escape_string($con, $_GET['type']);
 
    if($type == "status"){
       $operation = mysqli_escape_string($con, $_GET['operation']);
       $id = mysqli_escape_string($con, $_GET['id']);
 
       if($operation == 'approve')
       {
          $status = '1';
       }
      
          $update_status_sql = "UPDATE `activities` SET `status` = '$status' WHERE `activityId` = '$id'";
       mysqli_query($con, $update_status_sql); 
       header("Refresh:0; url=./pending-activities.php");
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./style.css" /> -->

</head>


<?php //include './header.php' 
?>

<body>

<!-- The Modal -->
<div class="modal" id="remarks">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Remarks</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <input type="text" class="form-control" placeholder="Enter the reason for rejection" name="remark" id="remark" required>
          <input type="number" class="form-control" placeholder="Activity ID" name="activityId" id="activityId" value="" required>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" name="submit-rejection" id="submit-rejection" >SUBMIT</button>
        </div>
        
      </div>
    </div>
  </div>


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
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                    <?php 
                    if(strpos(strtolower($user['title']),'club secretary') !== false ){
                        echo
                        "<a href='./home-secretary.php'> <img alt='image' src='../img/logo.png' class='header-logo' /> <span class='logo-name'></span>
                        </a>";
                    }else{
                        echo
                        "<a href='./home-treasurer.php'> <img alt='image' src='../img/logo.png' class='header-logo' /> <span class='logo-name'></span>
                        </a>";
                    }
                    ?>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Main</li>
                        <li class="dropdown active">
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
                                <li><a class="nav-link" href="./approved-activities.php">Approved Activities</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-user"></i><span>Admin Reporting</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="./admin-reportings.php">Reporting</a></li>
                                
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i class="far fa-newspaper"></i><span>News</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="./news-reporting.php">News Reporting</a></li>
                                <li><a class="nav-link" href="./news-reporting.php#verifynews">Verify News</a></li>
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

   

            <div class="main-content">
                <section class="section">

                


                <div class="card">
                  <div class="card-header">
                    <h4>Pending Activities</h4>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-striped table-md" id="pending-table">
                        <tr>
                        <th>Activity ID</th>
                        <th>Title</th>
                                      <th>Officers</th>
                                      <th>City</th>
                                      <th>Amount</th>
                                      <th>Lion Hours</th>
                                      
                                      <th>Type</th>
                                     
                                     
                                      <th>Date</th>
                                      <th>Actions</th>
                        </tr>

                        <?php foreach ($reportedActivities as $row) : ?>
                        <tr>
                        <td><?= $row['activityId'] ?></td>
                        <td><?= $row['activityTitle'] ?></td>
                                          <td><?= $row['cabinetOfficers'] ?></td>
                                          <td><?= $row['city'] ?></td>
                                          <td><?= $row['amount'] ?></td>
                                          <td><?= $row['lionHours'] ?></td>
                                         
                                          <td><?= $row['activityType'] ?></td>
                                          
                                          
                                          <td><?= $row['date'] ?></td>
                          
                        <td >
                          <!-- <?php
                        echo "
                        <form action='upload.php' method='POST' enctype='multipart/form-data'>
                        <input type='file' name='file'>
                        <input type='hidden' id='activitytId' name='activityId' value=".$row['activityId'].">
                        <button class='btn btn-primary' type='submit' name='submit'>Upload Bill</button>
                        </form>";
                          ?> -->
                          
                        <?php
                         echo "<a class='btn btn-success' id='approve' href='?type=status&operation=approve&id=".$row['activityId']."'>Approve</a>&nbsp;";
                          ?>

<?php
                         echo "<a class='btn btn-primary' id='update-activity' href='./manage-reporting.php?activityId=".$row['activityId']."'>Edit</a>&nbsp;";

                         echo "<button type='button' id='reject' name='reject' class='btn btn-danger' data-toggle='modal' data-target='#remarks'>
                         Reject
                       </button>";
                          ?>


                        </td>

                        </tr>
                        <?php endforeach; ?>
                        
                        
                        
                      </table>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    <nav class="d-inline-block">
                      <ul class="pagination mb-0">
                        <li class="page-item disabled">
                          <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1 <span
                              class="sr-only">(current)</span></a></li>
                        <li class="page-item">
                          <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                          <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                      </ul>
                    </nav>
                  </div>
                </div>
              </section>
            </div>


            <script>
              $(document).ready(function(){
                $('#submit-rejection').click(function(){
                  var remark = $('#remark').val();
                  var activityId = $('#activityId').val();
                  if(remark != ''){
                    $.ajax({
                      url: "submit-rejection.php",
                      method: "POST",
                      data: {remark:remark, activityId:activityId},
                      success: function(data){
                        $('#remark').val('');
                        $('#activityId').val('');
                        $('#reject').text('Rejected')
                        $('#reject').addClass('btn btn-warning');

                        alert("activity rejected!");

                      }
                    })
                  } 
                });
              });
            
      
            
            
            </script>
      

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