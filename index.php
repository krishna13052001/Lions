<?php
include './creds.php';
$con = mysqli_connect($host, $user, $pass, $dbname);

$topClubs = "SELECT * FROM `clubs` ORDER BY `stars` DESC, `last-updated` ASC LIMIT 10";
$topClubs = mysqli_query($con, $topClubs);


$topClubsActivity = "SELECT * FROM `clubs` ORDER BY `activityStar` DESC, `last-updated` ASC LIMIT 10";
$topClubsActivity = mysqli_query($con, $topClubsActivity);

$news = $con->query("SELECT i.`img` FROM `news` a INNER JOIN `images` i ON a.`newsId` = i.`categoryId` AND i.`category` = 'news' AND a.`verified` = '1' ORDER BY a.`newsId` LIMIT 4")->fetch_all(MYSQLI_ASSOC);

$events = $con->query("SELECT i.`img` FROM `events` a INNER JOIN `images` i ON a.`eventId` = i.`categoryId` AND i.`category` = 'events' ORDER BY a.`eventId` LIMIT 8")->fetch_all(MYSQLI_ASSOC);

$activities = $con->query("SELECT i.`img` FROM `activities` a INNER JOIN `images` i ON a.`activityId` = i.`categoryId` AND i.`category` = 'activities' ORDER BY a.`activityId` LIMIT 4")->fetch_all(MYSQLI_ASSOC);

$activityNum = $con->query("SELECT count(*) as `count` FROM `activities`")->fetch_assoc()['count'];

$clubsNum = "SELECT COUNT(`clubId`) AS `count` FROM `clubs`";
$clubsNum = mysqli_query($con, $clubsNum);
$clubsNum = mysqli_fetch_array($clubsNum);
$clubsNum = $clubsNum['count'];

$amountSql1 = "SELECT `amount` FROM `events` WHERE `eventType` = 'paid'";
$amountSql1 = mysqli_query($con, $amountSql1);
$amnt = 0;
while ($row = mysqli_fetch_array($amountSql1)) {
    $amnt += (int) $row['amount'];
}
$amountSql2 = "SELECT `amount` FROM `activities`";
$amountSql2 = mysqli_query($con, $amountSql2);
while ($row = mysqli_fetch_array($amountSql2)) {
    $amnt += (int) $row['amount'];
}

$b = 0;
$benificiary = "SELECT `peopleServed` FROM `activities`";
$benificiary = mysqli_query($con, $benificiary);
while ($row = mysqli_fetch_array($benificiary)) {
    $b += (int) $row['peopleServed'] ;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php
    include './meta.php';
    ?>
  <title>Welcome to Lions Club District 3234D2</title>



</head>
<!-- Start WOWSlider.com HEAD section -->
<link rel="stylesheet" type="text/css" href="engine1/style.css" />
<script type="text/javascript" src="engine1/jquery.js"></script>
<!-- End WOWSlider.com HEAD section -->
<body>

  <?php include './includes/header.php' ?>


  <!-- Start WOWSlider.com BODY section -->
<div id="wowslider-container1">
<div class="ws_images"><ul>
		<li><img src="data1/images/lions_international_baner_1_resize.jpg" alt="" title="" id="wows1_0"/></li>
		<li><img src="data1/images/shaniwarwada_baner_2_center.jpg" alt="" title="" id="wows1_1"/></li>
		<li><img src="data1/images/saibaba_baner_3_center.jpg" alt="" title="" id="wows1_2"/></li>
		<li><img src="data1/images/godavari_baner_4_center.jpg" alt="" title="" id="wows1_3"/></li>
	</ul></div>
<div class="ws_shadow"></div>
</div>	
<script type="text/javascript" src="engine1/wowslider.js"></script>
<script type="text/javascript" src="engine1/script.js"></script>
<!-- End WOWSlider.com BODY section -->
  <!-- About -->
  <section class="about-small  container">
    <div class="container animate_dist" style="padding: 40px;text-align:center;">
      <h1 class="animated" >About District 3234-D2</h1>
    </div>
    <div class="col-lg-12">
      <div class="row">
        <div class="col-md-3">
          <img src="./img/logo2.png" alt="">
        </div>
        <div class="col-md-9" style="padding-top: 40px;">
          <p>WELCOME TO OUR WEBSITE OF LIONS CLUB INTERNATIONAL’s DISTRICT 3234-D2. and all members of our
            District, 3234-D2.
            SOW SERVICE SEEDS with KINDNESS IN UNITY AND DIVERSITY to Give More for Community. Your journey
            through the web pages is made extremely simple with distinct information about our organization
            and its administrative functioning and reporting.</p>
          <div id="audio">
            <h4>Listen To Our Theme Song</h4>
            <a onclick="playAudio()" type="button"><i class="icofont-play-alt-1"></i></a>
            <a onclick="pauseAudio()" type="button"><i class="icofont-pause"></i></a>
            <audio id="myAudio">
              <source src="./img/anthem.mpeg" type="audio/ogg">
            </audio>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!-- About -->
  <script>
    var x = document.getElementById("myAudio");

    function playAudio() {
      x.play();
    }

    function pauseAudio() {
      x.pause();
    }
  </script>
  <!-- Counters -->
  <section class="counters">
    <div class="container col-lg-12 text-center">
      <div class="row" style="padding-left:10px;">
        <div class="col-md-3 col-6">
          <div class="counter">
            <img class="icon" src="./img/activity.png" alt="">
          </div>
          <h3 class="counter-value" data-toggle="counter-up" data-count="<?= $activityNum ?>"><?= $activityNum + '7125' ?></h3>
          <h4>Activities</h4>
        </div>
        <div class="col-md-3 col-6">
          <div class="counter">
            <img class="icon" src="./img/amount.png" alt="">
          </div>
          <h3 class="counter-value" data-toggle="counter-up" data-count="<?= $amnt ?>"><?= $amnt + '95711319' ?></h3>
          <h4>Amount Spent</h4>
        </div>
        <div class="col-md-3 col-6">
          <div class="counter">
            <img class="icon" src="./img/lion.png" alt="" id="icoclub">
          </div>
          <h3 class="counter-value" data-toggle="counter-up" data-count="<?= $clubsNum ?>"><?= $clubsNum +'-10' ?></h3>
          <h4>Clubs</h4>
        </div>
        <div class="col-md-3 col-6">
          <div class="counter">
            <img class="icon" src="./img/benificary.png" alt="">
          </div>
          <h3 class="counter-value" data-toggle="counter-up" data-count="<?= $b   ?>"><?= $b + '4204356'?></h3>
          <h4>People Served</h4>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Team -->
  <section class="team container">
    <div class="container animate_dist" style="padding: 40px;text-align:center;">
      <h1 class="animated">Our Team</h1>
    </div>

    <div class="row" style="text-align: center;">
      <div class="col-lg-2 col-sm-2">

      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
        <img id="img" src="./img/7.jpg" alt="">

        <h3>Abhay Shastri</h3>
        <h4 class="des animated1">District Governor</h4>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
        <img id="img" src="./img/10.jpg" alt="">
        <h3>Sunita Chitnis</h3>
        <h4 class="des animated1">Cabinet Secretary</h4>
      </div>
      <div class="col-lg-2 col-sm-2">

      </div>
    </div>
    <br><br>
    <div class="row" style="text-align: center;">
      <div class="col-lg-3 col-md-3">

          <img id="img" src="./img/3.jpg" alt="">
          <h3>Santosh Sonawale</h3>
          <h4 class="des animated1">Cabinet Treasurer</h4>
      </div>
      <div class="col-lg-3 col-md-3">
        <img id="img" src="./img/8.jpg" alt="">
          <h3>Vijay Sarda</h3>
          <h4 class="des animated1">GAT Coordinator</h4>
      </div>
      <div class="col-lg-3 col-md-3">
        <img id="img" src="./img/10.jpg" alt="">
            <h3>Sunita Chitnis</h3>
            <h4 class="des animated1">GST Coordinator</h4>
      </div>
      <div class="col-lg-3 col-md-3">

          <img id="img" <img src="./img/2.jpg" alt="">
              <h3>Shreyash Dixit </h3>
              <h4 class="des animated1">GMT Coordinator</h4>
      </div>
    </div>
    <br><br>
    <div class="row" style="text-align: center;">
      <div class="col-lg-3 col-md-3">
        <img id="img" src="./img/1.jpg" alt="">
        <h3>Ravi Satpute</h3>
        <h4 class="des animated1">GLT Coordinator</h4>
      </div>
      <div class="col-lg-3 col-md-3">
        <img id="img" src="./img/4.jpg" alt="">
        <h3>Sunil Jadhav</h3>
        <h4 class="des animated1">LCIF Coordinator</h4>
      </div>
      <div class="col-lg-3 col-md-3">
        <img id="img" src="./img/5.jpg" alt="">
        <h3>Rajesh Agarwal</h3>
        <h4 class="des animated1">PRO</h4>
      </div>
      <div class="col-lg-3 col-md-3">
        <img id="img" src="./img/9.jpg" alt="">
        <h3>Sabina Arun</h3>
        <h4 class="des animated1">Editor</h4>
      </div>
    </div>


      </div>
  </section>


  <!-- new activity and news start -->
  <br><br><br><br>
  <section class="container">
    <div class="row  d-flex">
      <div class="col-md-8 order-2 order-lg-1">

        <br>
        <div class="row" >
            <?php foreach ($activities as $row) : ?>
            <div class="col-6 col-md-6 pb-3 order-2 order-lg-1 order-md-1">
              <img src="<?= "/" . $row['img'] ?>" alt="" id="imgact">
            </div>
          <?php endforeach; ?>
        </div>

      </div>

      <div class="col-md-4 pt-lg-0 order-1 order-md-2 content " style="padding-left:0;">

        <div class="container animate_dist" style="padding-top:80px; text-align:center;">
          <h1 class="animated" >Activities  </h1>
        </div>
      </div>
    </div>
  </section>
<br><br>
  <section class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="container animate_dist" style="padding-top:80px; text-align:center;padding-bottom:20px;">
          <h1 class="animated">News </h1>
        </div>
      </div><br><br>
      <div class="col-md-8">
        <div class="row">
            <?php foreach ($news as $row) : ?>
            <div class="col-6 col-md-6 pb-3">
              <img src="<?= "/" . $row['img'] ?>" alt="" id="imgact">
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
  <!-- new activity and news end -->


  <section class="recent-events">
    <div class="jumbotron" style="background-color:#fff;padding-top:2rem;">
      <div class="container animate_dist" style=" text-align:center;">
        <h1 class="animated" >Events</h1>
      </div>
      <hr>
      <div class="row">
        <section class="multi-slider1">
          <div id="exampleSlider2" class="slider" >
            <div class="MS-content">
                <?php foreach ($events as $event) : ?>
              <div class="item">
                <img src="/<?= $event['img'] ?>" alt="">
              </div>
              <?php endforeach; ?>
            </div>

          </div>
        </section>
        <script>
        $('#exampleSlider2').multislider({
        interval: 10000,
        hoverPause: false,
        });
        setInterval(() => {
        $('#btn-left').click()
        $('#btn-right').click()
        }, 2000)
        </script>
      </div>
    </div>
  </section>



  <!-- Show Case -->
  <section class="showcase">
      <div class="jumbotron">
          <div class="col-lg-12">
            <div class="container animate_dist" style=" text-align:center;">
              <h1 class="animated" >TOP 10 CLUBS</h1>
            </div>
              <div class="row" style="    width: 100%;margin: 0;">
                  <div class="col-md-6">
                      <h2 class="text-center" style="color:#000;padding:10px 0;">Activities</h2>
                      <div class="col-lg-12">
                          <ul class="list-group">
                            <li class="list-group-item" style="background:#ffc107;"><strong style="float: left;">
                              Activity Name</strong><strong style="float: right;">Conducted</strong></li>
                              <?php while ($row = mysqli_fetch_array($topClubsActivity)) : ?>
                              <li class="list-group-item">
                                  <strong style="text-transform: uppercase; float: left;">
                                      <?= $row['clubName'] ?></strong>
                                  <strong style="float: right;">
                                      <?= $row['activityStar'] . " " ?><i class="fa fa-star"
                                          style="color: orange"></i>
                                  </strong>
                              </li>
                              <?php endwhile ?>
                          </ul>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <h2 class="text-center" style="color:#000;padding:10px 0;">
                          Admin Reporting
                      </h2>
                      <div class="col-lg-12">
                          <ul class="list-group">
                            <li class="list-group-item" style="background:#ffc107; "><strong style="float: left;">
                              Admin Reporting</strong><strong style="float: right;">Conducted</strong></li>
                              <?php while ($row = mysqli_fetch_array($topClubs)) : ?>
                              <li class="list-group-item">
                                  <strong style="text-transform: uppercase; float: left;">
                                      <?= $row['clubName'] ?></strong>
                                  <strong style=" float: right;">
                                      <?= $row['stars'] . " " ?><i class="fa fa-star" style="color: gold"></i>
                                  </strong>
                              </li>
                              <?php endwhile ?>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
          </div>
  </section>
  <!-- Show Case -->



  <?php include './includes/footer.php' ?>
</body>

</html>
