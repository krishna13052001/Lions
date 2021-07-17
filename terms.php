<?php
include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);
$privacy = $con->query("SELECT * FROM `general-content` WHERE `type` = 'terms-and-conditions'");
$privacy = $privacy->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <title>Temrs and Conditions | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>

    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">Terms &amp; Conditions
          </h1>
        </div>
      </section>

    <div class="container content-container ankit-district">
        </h1><br>

        <p>
            <?= $privacy['description'] ?>
            <br>
        </p>

    </div>

    <?php include './includes/footer.php' ?>
</body>

</html>
