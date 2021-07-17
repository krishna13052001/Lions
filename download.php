<?php

include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);

$allClubs = $con->query("SELECT DISTINCT * FROM `clubs` ");
$allClubs = $allClubs->fetch_all(MYSQLI_ASSOC);

$allPositions = $con->query("SELECT DISTINCT `title` FROM `users`");
$allPositions = $allPositions->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['download-data'])) {
    $sql = "SELECT `regionName`, `zoneName`, `clubId`, `clubName`, `id`, `firstName`, `middleName`, `lastName`, `address1`, `address2`, `city`, `state`, `postalCode`, `email`, `phone`, `spouseName`, `dob`, `gender`, `occupation` FROM `users` WHERE (";
    foreach ($_POST['clubs'] as $club) {
        $sql .= "`clubId`='" . $club . "' OR ";
    }
    $sql .= "`clubId` = '-1') AND (";
    foreach ($_POST['titles'] as $title) {
        $sql .= "`title`='" . $title . "' OR ";
    }
    $sql .= "`title` = 'BARNEY')";

    function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
    $res = $con->query($sql);
    $array = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $array[] = $row;
    }
    download_send_headers("data_export_LionsData" . date("Y-m-d") . ".csv");
    echo array2csv($array);
    die();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include './meta.php' ?>
    <title>Download Data | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>


    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">Download Data
          </h1>
        </div>
      </section>


    <div class="container content-container ankit-container">
        </h1><br><br>
        <div class="col-lg-12">
            <style>
            .checks {
                height: 40rem;
                overflow: auto;
            }
            </style>
            <form action="./download.php" method="post" class="form-group" id="form">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-center animated">Select Clubs</h4>
                        <ul class="list-group checks required1">
                            <?php foreach ($allClubs as $club) : ?>
                            <li class="list-group-item"><?= $club['clubName'] . " " ?><input type="checkbox"
                                    name="clubs[]" class="clubs" id="" value="<?= $club['clubId'] ?>"
                                    style="float: right;"></li>
                            <?php endforeach; ?>
                        </ul>
                        <br>
                        <button type="button" class="btn btn-success" id="checkClubs">Check All</button>
                        <button type="button" class="btn btn-danger" id="uncheckClubs">Uncheck All</button>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-center animated" >Select Positions</h4>
                        <ul class="list-group checks required2">
                            <?php foreach ($allPositions as $pos) : ?>
                            <li class="list-group-item"><?= $pos['title'] . " " ?><input type="checkbox" name="titles[]"
                                    class="titles" id="" value="<?= $pos['title'] ?>" style="float: right;"></li>
                            <?php endforeach; ?>
                        </ul>
                        <br>
                        <button type="button" class="btn btn-success" id="checkTitle">Check All</button>
                        <button type="button" class="btn btn-danger" id="uncheckTitle">Uncheck All</button>
                    </div>
                </div>
                <br>
                <br>
                <input type="submit" value="Download Data" class="btn btn-primary" name="download-data"
                    style="display: block; margin: auto">
     
            </form>
            <form action="./d/index.php">
                <input type="submit" value="Download As Lable" class="btn btn-outline-primary" style="display: block; margin: auto">
            </form>
        </div>
    </div>
    <?php include './includes/footer.php' ?>
</body>
<script>
const checkClubs = document.querySelector('#checkClubs');
const uncheckClubs = document.querySelector('#uncheckClubs');
const checkTitle = document.querySelector('#checkTitle');
const uncheckTitle = document.querySelector('#uncheckTitle');

const clubs = document.querySelectorAll('.clubs');
const titles = document.querySelectorAll('.titles');

checkClubs.addEventListener('click', () => {
    clubs.forEach(club => {
        club.checked = true;
    })
})

uncheckClubs.addEventListener('click', () => {
    clubs.forEach(club => {
        club.checked = false;
    })
})

checkTitle.addEventListener('click', () => {
    titles.forEach(title => {
        title.checked = true;
    })
})

uncheckTitle.addEventListener('click', () => {
    titles.forEach(title => {
        title.checked = false;
    })
})

document.querySelector('#form').addEventListener('submit', (e) => {
    if ($('.required1 :checkbox:checked').length <= 0 || $('.required2 :checkbox:checked').length <= 0) {
        e.preventDefault();
        alert("Select Atleast One Option!")
    }
})
</script>

</html>
