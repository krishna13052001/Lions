<?php @session_start();
include './../creds.php';

$con = new mysqli($host, $user, $pass, $dbname);
if (!isset($_SESSION['super-id'])) {
    header("Location: ./super-login.php");
} else {
    $id = $_SESSION['super-id'];
    $super = $con->query("SELECT * FROM `super` WHERE `id` = '$id'");
    $super = $super->fetch_assoc();
    
    $title = $super['title'];
    $name = $super['name'];
    
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <title>Super Home | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" integrity="sha512-PgQMlq+nqFLV4ylk1gwUOgm6CtIIXkKwaIHp/PAIWHzig/lKZSEGKEysh0TCVbHJXCLN7WetD8TFecIky75ZfQ==" crossorigin="anonymous" />
    <style>

  .header-factsheet{
    width:100%;
    height: 100%;
    padding: 20px;
    text-align: center;
    margin-top: 100px;
  }

  .card{
    transition: 0.4s ease;
  }

  .card-body{
    padding: 30px 0px!important;
    text-decoration: none!important;
  }

  .card:hover{
    text-decoration: none!important;;
    box-shadow: 0 0 20px 0 rgba(0,0,0,0.3);
  }

  .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 0 20px 0 rgba(0,0,0,0.3);
  }

  .card:hover .card-header, .card:hover{
    color: #3ccdbb;

  }

  .card-body h2{
    font-size: 17px;
    color: WHITE;
      margin:20px 0 15px 0;
      font-weight: bold;
      line-height: 1.1;
  }

  .card-body p{
    font-size: 15px;
  }

   .rotate-icon{
    -webkit-transition: 0.3s ease-out;

  }

  .rotate-link:hover .rotate-icon{
    -webkit-transform: rotate(360deg);
  }

  a{
      text-decoration: none!important;
  }
   i{
      color: white;
  }
    .animated {
    text-decoration: none;
    background: linear-gradient(to right, #CCC 50%, #FF5858 50%, #f5e503c7);
    background-size: 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    transition: 0.5s ease-out;
   }

   .animated:hover {
    background-position: -100%;
   }

    </style>


    <section class="header-factsheet">
      <div class="section-title">
        <h1 class="animated">MEMBERS</h1>
        <h3>Members</h3>
      </div>
        <div class="container py-5">
          <div class="row">

            <div class="col-lg-4 col-md-4 col-12">
              <div class="card text-center" style="background-color: red;">
                  <a href="./add-admin.php">
                <div class="card-body rotate-link">
                  <i class="rotate-icon fa-3x fas fa-user-alt"></i>
                  <h2>ADMIN</h2>
                </div></a>
              </div>
            </div>

            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: teal;">
                <a href="./update-slider.php">
                <div class="card-body rotate-link">
                  <i class="rotate-icon fa-3x far fa-file-image"></i>
                  <h2>SLIDER</h2>
                </div>
                </a>
              </div>
            </div>

            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: tomato;">
                <a href="./add-club.php">
                <div class="card-body rotate-link">
                    <i class="rotate-icon fa-3x fas fa-flag"></i>
                  <h2>CLUBS</h2>
                </div></a>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: turquoise;">
                <a href="./add-member.php">
                <div class="card-body rotate-link">
                  <i class="rotate-icon fa-3x fas fa-users"></i>
                  <h2>MEMBERS</h2>
                </div>
                </a>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: violet;">
                <a href="./reset.php">
                <div class="card-body rotate-link">
                  <i class="rotate-icon fa-3x fas fa-unlock-alt"></i>
                  <h2>CHANGE PASSWORD</h2>
                </div>
                </a>
              </div>
            </div>

            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: coral;">
                <a href="./contact.php">
                <div class="card-body rotate-link">
                  <i class="rotate-icon fa-3x fas fa-phone-square"></i>
                  <h2>CONTACT REQUEST</h2>
                </div>
                </a>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: #1687a7;">
                <a href="./logout.php">
                <div class="card-body rotate-link">
                  <i class="rotate-icon fa-3x fas fa-sign-out-alt"></i>
                  <h2>LOGOUT</h2>
                </div>
                </a>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: #9dad7f;">
                <a href="./add-gallery.php">
                <div class="card-body rotate-link">
                    <i class="rotate-icon fa-3x fas fa-camera-retro"></i>
                  <h2>GALLERY</h2>
                </div>
                </a>
              </div>
            </div>

            <div class="col-lg-4 col-md-4 col-12 ">
              <div class="card text-center" style="background-color: crimson;">
                <a href="./general-update.php">
                <div class="card-body rotate-link">
                  <i class="rotate-icon fa-3x fas fa-copy"></i>
                  <h2>GENERAL</h2>
                </div>
                </a>
              </div>
            </div>
          </div>

        </div>
        </div>
      </section>
    <?php include './footer.php' ?>
</body>

</html>