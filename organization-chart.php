<?php

include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);

$regions = $con->query("SELECT DISTINCT `regionName` FROM `users` ORDER BY `regionName`");
$regions = $regions->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php
    include './meta.php';
    ?>
  <title>Organization Chart | 3234D2</title>
</head>

<body>
  <?php include './includes/header.php' ?>

  <section id="intro" class="d-flex align-items-center" style="background-image:url(img/indexbg7.jpg)">
    <div class="container">
      <h1 class="animated">Organization Chart
      </h1>
    </div>
  </section>


  <section id="Chart" class="Chart">
      <div class="container">
    <?php foreach ($regions as $region) : ?>
        <div class="section-title" style="text-align:center; padding:10px;">
          <h2 class="animated" style="margin-bottom:10px;"><?= $region['regionName'] ?></h2>
          <?php
                    $regionName = $region['regionName'];
                    $cp = $con->query("SELECT `firstName`, `middleName`, `lastName` FROM `users` WHERE `regionName` = '$regionName' AND `title` LIKE '%region chairperson%'");
                    $cp = $cp->fetch_assoc();
                    ?>
          <h4>Region Chairperson: <?php
                        error_reporting(E_ERROR | E_PARSE);
                        try {
                            echo $cp['firstName'] . " " . $cp['middleName'] . " " . $cp['lastName'];
                        } catch (Exception $e) {
                            echo '';
                        }    ?></h4>
        </div>
        <?php
              $zones = $con->query("SELECT DISTINCT `zoneName` FROM `users` WHERE `regionName` = '$regionName' ORDER BY `zoneName`");
              $zones = $zones->fetch_all(MYSQLI_ASSOC);
              ?>
                
        <div class="row mb-5">
            <?php foreach ($zones as $zone) : ?>
          <div class="col-lg-3 col-md-6" style=" padding: 10px;">
            <div class="box featured" style="height:300px;">
            <?php
                      $zoneName = $zone['zoneName'];
                      $zcp = $con->query("SELECT `firstName`, `middleName`, `lastName` FROM `users` WHERE `regionName` = '$regionName' AND `zoneName` = '$zoneName' AND `title` LIKE '%zone chairperson%'");
                      $zcp = $zcp->fetch_assoc()
                      ?>
              <h3><?php echo trim($zone['zoneName']).' <br>Zone Chairperson : '.$zcp['firstName'] . ' ' . $zcp['lastName']?></h3>
            <?php
                                        $clubs = $con->query("SELECT DISTINCT `clubName`, `clubId` FROM `users` WHERE `regionName` = '$regionName' AND `zoneName` = '$zoneName' ORDER BY `clubId`");
                                        $clubs = $clubs->fetch_all(MYSQLI_ASSOC);
            ?>
            <ul>
                <?php foreach ($clubs as $club) : ?>
                <li><a href="./view-members.php?clubId=<?= $club['clubId'] ?>"><?= trim($club['clubName']) ?> <span>(<?= $club['clubId'] ?>)</span></a></li>
                <?php endforeach ?>
            </ul>
            
            </div>
          </div>
          <?php endforeach ?>
          

        </div>
        <hr>
        <?php endforeach ?>

      </div>
    </section><!-- End Pricing Section -->

  <?php include './includes/footer.php' ?>
</body>

</html>
