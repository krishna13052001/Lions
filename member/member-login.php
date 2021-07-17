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
    if (isset($_POST['update-profile'])) {
        $regionName = $_POST['regionName'];
        $regionName = strip_tags($regionName);
        $regionName = mysqli_real_escape_string($con, $regionName);

        $zoneName = $_POST['zoneName'];
        $zoneName = strip_tags($zoneName);
        $zoneName = mysqli_real_escape_string($con, $zoneName);

        $firstName = $_POST['firstName'];
        $firstName = strip_tags($firstName);
        $firstName = mysqli_real_escape_string($con, $firstName);

        if (isset($_POST['middleName'])) {
            $middleName = $_POST['middleName'];
            $middleName = strip_tags($middleName);
            $middleName = mysqli_real_escape_string($con, $middleName);
        } else {
            $middleName = "";
        }

        $lastName = $_POST['lastName'];
        $lastName = strip_tags($lastName);
        $lastName = mysqli_real_escape_string($con, $lastName);

        $address1 = $_POST['address1'];
        $address1 = strip_tags($address1);
        $address1 = mysqli_real_escape_string($con, $address1);

        $address2 = $_POST['address2'];
        $address2 = strip_tags($address2);
        $address2 = mysqli_real_escape_string($con, $address2);

        $city = $_POST['city'];
        $city = strip_tags($city);
        $city = mysqli_real_escape_string($con, $city);

        $state = $_POST['state'];
        $state = strip_tags($state);
        $state = mysqli_real_escape_string($con, $state);

        $postalCode = $_POST['postalCode'];
        $postalCode = strip_tags($postalCode);
        $postalCode = mysqli_real_escape_string($con, $postalCode);

        $email = $_POST['email'];
        $email = strip_tags($email);
        $email = mysqli_real_escape_string($con, $email);

        $phone = $_POST['phone'];
        $phone = strip_tags($phone);
        $phone = mysqli_real_escape_string($con, $phone);

        if (isset($_POST['spouseName'])) {
            $spouseName = $_POST['spouseName'];
            $spouseName = strip_tags($spouseName);
            $spouseName = mysqli_real_escape_string($con, $spouseName);
        } else {
            $spouseName = "";
        }

        $dob = $_POST['dob'];
        $dob = strip_tags($dob);
        $dob = mysqli_real_escape_string($con, $dob);

        $gender = $_POST['gender'];
        $gender = strip_tags($gender);
        $gender = mysqli_real_escape_string($con, $gender);

        $occupation = $_POST['occupation'];
        $occupation = strip_tags($occupation);
        $occupation = mysqli_real_escape_string($con, $occupation);

        $sql = "UPDATE `users` SET `regionName`='$regionName',`zoneName`='$zoneName',`firstName`='$firstName',`middleName`='$middleName',`lastName`='$lastName',`address1`='$address1',`address2`='$address2',`city`='$city',`state`='$state',`postalCode`='$postalCode',`email`='$email',`phone`='$phone',`spouseName`='$spouseName',`dob`='$dob',`gender`='$gender',`occupation`='$occupation',`verified`='1' WHERE `id` = '$id'";
        if(mysqli_query($con, $sql)){
            echo "<script>alert(`Profile Successfully Updated!`); window.location.href = `./home.php`;</script>";
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
  <title><?php echo $member; ?> update profile | 3234D2</title> 
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
        
      <?php ?>

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
            <h4 class="text-center">Update Profile</h4>
            <form action="./member-login.php" method="POST" class="form-group">

                <p>Enter Region Name*</p>
                <input type="text" name="regionName" id="" class="form-control" required
                    placeholder="Enter Region Name*" value="<?php echo $user['regionName'] ?>" readonly>

                <p>Enter Zone Name*</p>
                <input type="text" name="zoneName" id="" class="form-control" placeholder="Enter Zone Name*" required
                    value="<?php echo $user['zoneName'] ?>" readonly>

                <p>Enter Name*</p>
                <input type="text" name="firstName" id="" class="form-control" placeholder="Enter Name*" required
                    value="<?php echo $user['firstName'] ?>">


                <p>Enter Middle Name</p>
                <input type="text" name="middleName" id="" class="form-control" placeholder="Enter Middle Name"
                    value="<?php $user['middleName'] ?>">

                <p>Enter Last Name*</p>
                <input type="text" name="lastName" id="" class="form-control" placeholder="Enter Last Name*" required
                    value="<?php echo $user['lastName'] ?>">

                <p>Enter Address Line 1*</p>
                <input type="text" name="address1" id="" class="form-control" placeholder="Enter Address Line 1*"
                    required value="<?php echo $user['address1'] ?>">

                <p>Enter Address Line 2*</p>
                <input type="text" name="address2" id="" class="form-control" placeholder="Enter Address Line 2*"
                    required value="<?php echo $user['address2'] ?>">

                <p>Enter City*</p>
                <input type="text" name="city" id="" class="form-control" placeholder="Enter City*" required
                    value="<?php echo $user['city'] ?>">

                <p>Enter State*</p>
                <input type="text" name="state" id="" class="form-control" placeholder="Enter State*" required
                    value="Maharashtra" readonly>

                <p>Enter Postal Code*</p>
                <input type="number" name="postalCode" id="" class="form-control" placeholder="Enter Postal Code*"
                    required value="<?php echo $user['postalCode'] ?>">

                <p>Enter Email* (Required for resetting password!)</p>
                <input type="email" name="email" id="" class="form-control" placeholder="Enter Email*" required
                    value="<?php echo $user['email'] ?>">

                <p>Enter Phone Number*</p>
                <input type="text" name="phone" id="" class="form-control" placeholder="Enter Phone*" required
                    value="<?php echo $user['phone'] ?>" pattern="[6-9]{1}[0-9]{9}">

                <p>Enter Spouse Name</p>
                <input type="text" name="spouseName" id="" class="form-control" placeholder="Enter Spouse Name"
                    value="<?php echo $user['spouseName'] ?>">

                <p>Enter D.O.B.*</p>
                <input type="date" name="dob" id="" class="form-control" placeholder="Enter DOB*" required
                    value="<?php echo $user['dob'] ?>">

                <p>Enter Gender*</p>
                <select name="gender" id="" class="form-control" required>
                    <option value="" disabled>Select Gender</option>
                    <option value="Female" <?php if ($user['gender'] == 'Female') {
                                                echo 'selected';
                                            } ?>>Female</option>
                    <option value="Male" <?php if ($user['gender'] == 'Male') {
                                                echo 'selected';
                                            } ?>>Male</option>
                    <option value="Other" <?php if ($user['gender'] == 'Other') {
                                                echo 'selected';
                                            } ?>>Other</option>
                </select>

                <p>Enter Occupation*</p>
                <input type="text" name="occupation" id="" class="form-control" placeholder="Enter Occupation*" required
                    value="<?php echo $user['occupation'] ?>">

                <br>
                <input type="submit" value="Submit" name="update-profile" class="btn btn-success">
            </form>
        </div>
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