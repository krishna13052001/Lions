<?php
include_once "./creds.php";
$conn = mysqli_connect($host, $user, $pass, $dbname);
$slider = mysqli_query($conn, "SELECT * FROM `slider`");
?>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
<link rel="stylesheet" href="./links/css/bootstrap.min.css" />
<script src="./links/js/jquery-3.3.1.min.js"></script>
<script src="./links/js/bootstrap.min.js"></script>
<script src="./links/js/multislider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<link href="links/icofont/icofont.min.css" rel="stylesheet">
<link rel="stylesheet" href="links/style.css">



<!-- Navbar Start -->

<div id="loader">
    <style>
    .loading {
        position: fixed;
        display: flex;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: #000;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        flex-wrap: wrap;
        z-index: 99999;
        animation: load 1s linear forwards infinite;
    }

    @keyframes load {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.5);
        }

        100% {
            transform: scale(1);
        }
    }

    .loading img {
        display: block;
        margin: auto;
        height: 10rem;
        width: 10rem;
    }
    </style>
    <div class="loading">
        <img src="./img/logo.png" alt="">
    </div>
    <script>
    $(window).on('load', () => {
        $('#loader').remove()
    })
    </script>
</div>



<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
  <div class="container-flex d-flex align-items-center">

    <!-- <h1 class="logo mr-auto"><a href="index.html">BizLand<span>.</span></a></h1> -->
    <!-- Uncomment below if you prefer to use an image logo -->
    <a href="index.php" class="logo"><img src="./img/logo.png" class="img-logo" alt="Lions Club" /></a>
    <a href="index.php" id="logo2" class="logo mr-auto"><img src="./img/logo2.png" class="img-logo" alt="Lions Club"></a>
    <nav class="nav-menu d-none d-lg-block">
      <ul>
        <li class="active"><a href="./index.php" class="nav-link">Home</a></li>
        <li class="drop-down"><a>About</a>
          <ul>
            <li><a class="dropdown-item" href="./our-governer.php">Governor</a></li>
            <li><a class="dropdown-item" href="./dgteam.php">DG Team</a></li>
            <li><a class="dropdown-item" href="./about-district.php">About District 3234D2</a></li>
            <li>  <a class="dropdown-item" href="./organization-chart.php">Organization Chart</a></li>
          </ul>
        </li>
        <li><a class="nav-link" href="./event.php">Events</a></li>
        <li><a class="nav-link" href="./activities.php">Activities</a></li>
        <li><a class="nav-link" href="./news.php">News</a></li>
        <li class="drop-down"><a>Membership</a>
          <ul>
            <li><a class="dropdown-item" href="./member-directory.php?page=1">Member Directory</a></li>
            <li><a class="dropdown-item" href="./business-directory.php?page=1">Business Directory</a></li>
            <li><a href="./download.php" class="dropdown-item">Download Member Data</a></li>
          </ul>
        </li>
        <li class="drop-down"><a>Resources</a>
          <ul>
            <li><a href="./global.php" class="nav-link">Global Priorities</a></li>
            <li><a class="dropdown-item" href="./download-resources.php">Download Resources</a></li>
            <li><a class="dropdown-item" href="./privacy.php">Privacy Policies</a></li>
            <li><a class="dropdown-item" href="./terms.php">Terms &amp; Conditions</a></li>
          </ul>
        </li>
        <li><a class="nav-link" href="./gallery.php">Gallery</a></li>
        <li><a href="./contact.php" class="nav-link">Contact Us</a></li>

        <li><a href="./login.php" class="nav-link">Login</a></li>

      </ul>
    </nav><!-- .nav-menu -->

  </div>
</header><!-- End Header -->
