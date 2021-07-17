<?php
include './../creds.php';
include './../upload-image.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
} else {
    $id = $_SESSION['id'];
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);
    $clubId = $user['clubId'];
    
    $clubDetail = "SELECT * FROM `clubs` WHERE `clubId` = '$clubId'";
    $clubDetail = mysqli_query($con, $clubDetail);
    $clubDetail = mysqli_fetch_assoc($clubDetail);

    $prevStars = (int)$clubDetail['stars'];
    if (isset($_GET['month'])) {
        $month = $_GET['month'];
        $month = $con->real_escape_string(strip_tags($month));

        $submittedReports = $con->query("SELECT * FROM `updated-reports`, `reports` WHERE `updated-reports`.`clubId` = '$clubId' AND `updated-reports`.`month` = '$month' AND `updated-reports`.`reportId` = `reports`.`id` AND `reports`.`month` = '$month'");

        if ($clubDetail["month-" . $month] == '1' || $currentMonth - $month > 1 || $currentMonth - $month < 0) {
            $updated = false;
        } else {
            $updated = false;
        }

        $reports = mysqli_query($con, "SELECT * FROM `reports` WHERE `month` = '$month'");
    } else if (isset($_POST['update-report'])) {
        $month = $_POST['month'];
        $month = $con->real_escape_string(strip_tags($month));
        $sql1 = "UPDATE `clubs` SET `month-" . $month . "`='1',`last-updated`='$date' WHERE `clubId` = '$clubId'";
        mysqli_query($con, $sql1);
        foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
            $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
            $imageUploadPath = './../' . $uploadPath . $file_name;

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allowTypes)) {
                $file_tmp = $_FILES["image"]["tmp_name"][$key];
                $compressedImage = compressImage($file_tmp, $imageUploadPath, 50);
                $addImageSql = "INSERT INTO `admin-images`(`img`, `clubId`, `month`) VALUES ('$compressedImage', '$clubId', '$month')";
                if (mysqli_query($con, $addImageSql)) {
                    $status = 'success';
                }
            } else {
                array_push($error, "$file_name, ");
            }
        }
        foreach ($_POST['report-item'] as $check) {
            $myData = explode("*", $check);
            $multiplier = (int)$myData[1];
            $reportId = $myData[0];
            if (mysqli_query($con, "INSERT INTO `updated-reports`(`reportId`, `clubId`, `month`, `multiplier`) VALUES ('$reportId', '$clubId', '$month', '$multiplier')")) {
                $sql = mysqli_query($con, "SELECT * FROM `reports` WHERE `id` = $reportId");
                $sql = mysqli_fetch_assoc($sql);
                $prevStars += $multiplier * (int)$sql['stars'];
                mysqli_query($con, "UPDATE `clubs` SET `stars` = '$prevStars' WHERE `clubId` = '$clubId'");
            }
        }
        header("Location: ./admin-reporting.php");
    } else {
        header("Location: ./admin-reporting.php?month=" . $currentMonth);
    }
}
mysqli_close($con);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <title>Admin Reporting | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
    <section class="members container">
        <?php if ($user['verified'] == 0) {
            echo '<h1 class="text-center">Your Profile isn\'t updated!</h1>';
        } ?>
        <h2 class="text-center">Admin Reporting</h2>
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
        <h4 class="text-center">Total Admin Points: <?= $prevStars . " " ?><i class="fa fa-star" style="color: gold"></i>
            &nbsp;|&nbsp;
            Total Activity Points: <?= $clubDetail['activityStar'] . " " ?><i class="fa fa-star" style="color: orange"></i>
        </h4>
        <?php if (!$power) : ?>
            <script>
                alert("You Can't use this feature!")
                window.location.href = "./home.php";
            </script>
        <?php endif ?>
        <?php if (!$updated) : ?>
            <form action="./admin-reporting.php" method="post" class="form-group" id="form" enctype="multipart/form-data">
                <select name="month" id="month" class="form-control">
                    <option value="" disabled selected>Select Month</option>
                    <option value="7">July 2020</option>
                    <option value="8">August 2020</option>
                    <option value="9">September 2020</option>
                    <option value="10">October 2020</option>
                    <option value="11">November 2020</option>
                    <option value="12">December 2020</option>
                    <option value="1">January 2021</option>
                    <option value="2">February 2021</option>
                    <option value="3">March 2021</option>
                    <option value="4">April 2021</option>
                    <option value="5">May 2021</option>
                    <option value="6">June 2021</option>
                </select>
                <br>
                <ul class="list-group">
                    <?php while ($row = mysqli_fetch_assoc($reports)) : ?>
                        <li class="list-group-item add-report">
                            <?= $row['title'] . " " ?>
                            <?php if ($row['multiple'] == 1) : ?>
                                <input type="number" name="" id="" placeholder="Enter Multiple" value="1" class="multiple-check" style="width: 3rem; margin:auto 1rem;" min="1">
                            <?php endif; ?>
                            <input type="checkbox" name="report-item[]" id="report-item" data-multiple="<?= $row['multiple'] ?>" value="<?= $row['id'] ?>*1" style="float: right;" class="check-box">
                        </li>
                    <?php endwhile; ?>
                </ul>
                <br>
                <span>Upload Report Proof (max. 3 photos)</span>
                <input type="file" name="image[]" id="" multiple accept="image/*" required class="form-control"><br>
                <?php
                if ($power) {
                    echo '<input type="submit" class="btn btn-success" value="Report" name="update-report" />';
                }
                ?>
            </form>
        <?php endif; ?>
        <?php if ($updated) : ?>
            <select name="month" id="month" class="form-control">
                <option value="" disabled selected>Select Month</option>
                <option value="7">July 2020</option>
                <option value="8">August 2020</option>
                <option value="9">September 2020</option>
                <option value="10">October 2020</option>
                <option value="11">November 2020</option>
                <option value="12">December 2020</option>
                <option value="1">January 2021</option>
                <option value="2">February 2021</option>
                <option value="3">March 2021</option>
                <option value="4">April 2021</option>
                <option value="5">May 2021</option>
                <option value="6">June 2021</option>
            </select>
            <br>
            <ul class="list-group">
                <?php while ($row = mysqli_fetch_assoc($submittedReports)) : ?>
                    <li class="list-group-item">
                        <strong>Report For: <?= $row['title'] ?></strong><br>
                        <strong>Awarded Stars: <?= (int)$row['stars'] * (int)$row['multiplier'] ?></strong><br>
                    </li>
                <?php endwhile; ?>
            </ul>
            <br>
        <?php endif; ?>
        <p class="text-center"><b>Disclaimer: Any false/irrelevant/unparliamentary data/information fed will lead to
                strict actions being taken against the respective clubs/members, that may lead to BAN and/or REMOVAl of
                the
                involved clubs/members. </b><br><br></p>
    </section>
    <?php include './footer.php' ?>
</body>
<script>
    var url_string = window.location.href;
    var url = new URL(url_string);
    var currentMonth = url.searchParams.get("month");

    const month = document.querySelector("#month");
    month.value = currentMonth;
    month.addEventListener('change', () => {
        window.location.href = `./admin-reporting.php?month=${month.value}`
    })

    $("input[type='submit']").click(function(e) {
        var $fileUpload = $("input[type='file']");
        if (parseInt($fileUpload.get(0).files.length) > 3) {
            alert("You can only upload a maximum of 3 files");
            e.preventDefault();
            // window.location.href = "./activity-reporting.php";
        }
    });

    $('#form').on('submit', (e) => {
        let r = confirm("Are you sure you want to submit? Once submitted it can't be altered!");
        if (!r) {
            e.preventDefault()
        }
    })
</script>
<script>
    const reports = document.querySelectorAll(".add-report");

    reports.forEach(report => {
        let r = report.querySelector('.multiple-check');
        let c = report.querySelector('.check-box');
        if (r) {
            r.addEventListener('keyup', () => {
                if (r.value == '') {
                    r.value = 1;
                }
                c.value = c.value.split('*')[0] + "*" + Number(r.value);
            })
            r.addEventListener('mouseup', () => {
                if (r.value == '') {
                    r.value = 1;
                }
                c.value = c.value.split('*')[0] + "*" + Number(r.value);
            })
        }
    })
</script>

</html>