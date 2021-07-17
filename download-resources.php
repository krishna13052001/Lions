<?php
include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);

$resources = $con->query("SELECT * FROM `resources` WHERE `category` = 'district'");
$resources = $resources->fetch_all(MYSQLI_ASSOC);

$intnl = $con->query("SELECT * FROM `resources` WHERE `category` = 'international'");
$intnl = $intnl->fetch_all(MYSQLI_ASSOC);

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
    <title>Download Resources | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>

    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">Download Resources
          </h1>
        </div>
      </section>

    <div class="container content-container ankit-download-resources">
        </h1><br>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-center">District Level</h4>
                    <div class="row">
                        <?php foreach ($resources as $resource) : ?>
                        <div class="col-md-6">
                            <a href="<?= $resource['path'] ?>" download>
                                <ul class="downloadUL">
                                    <li><img src="img/logo.png" alt="pdf icon" style="display: block; margin: auto;">
                                    </li>
                                    <li>
                                        <p class="titleP"><?= $resource['title'] ?></p>
                                    </li>
                                </ul>
                            </a>
                            <hr>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="text-center">International Level</h4>
                    <div class="row">
                        <?php foreach ($intnl as $resource) : ?>
                        <div class="col-md-6">
                            <a href="<?= $resource['path'] ?>" download>
                                <ul class="downloadUL">
                                    <li><img src="img/logo.png" alt="pdf icon" style="display: block; margin: auto;">
                                    </li>
                                    <li>
                                        <p class="titleP"><?= $resource['title'] ?></p>
                                    </li>
                                </ul>
                            </a>
                        </div>
                        <hr>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include './includes/footer.php' ?>
</body>
