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
    $member = $user['title'];

    $unverifiedNews = $con->query("SELECT * FROM `news` WHERE `clubId` = '$clubId' AND `verified` = '0'");
    $unverifiedNews = $unverifiedNews->fetch_all(MYSQLI_ASSOC);
    if (isset($_POST['add-news'])) {
        $newsTitle = $_POST['newsTitle'];
        $newsTitle = strip_tags($newsTitle);
        $newsTitle = mysqli_real_escape_string($con, $newsTitle);

        if (isset($_POST['date']) && $_POST['date'] != null) {
            $date = $_POST['date'];
            $date = strip_tags($date);
            $date = mysqli_real_escape_string($con, $date);
        } else {
            $date = date('Y-m-d');
            $date = mysqli_real_escape_string($con, $date);
        }

        $description = $_POST['description'];
        $description = strip_tags($description);
        $description = mysqli_real_escape_string($con, $description);

        if (isset($_POST['newsPaperLink']) && $_POST['newsPaperLink'] != null) {
            $newsPaperLink = $_POST['newsPaperLink'];
            $newsPaperLink = strip_tags($newsPaperLink);
            $newsPaperLink = mysqli_real_escape_string($con, $newsPaperLink);
        } else {
            $newsPaperLink = '#';
        }

        $authorId = $id;

        $clubId = $user['clubId'];

        $addNewsSql = "INSERT INTO `news`(`newsTitle`, `date`, `description`, `newsPaperLink`, `authorId`, `clubId`) VALUES ('$newsTitle', '$date', '$description', '$newsPaperLink', '$authorId', '$clubId')";
        $getNewsSql = "SELECT `newsId` FROM `news` WHERE `newsTitle` = '$newsTitle' AND `date` = '$date' AND `authorId` = '$authorId' AND `clubId` = '$clubId'";

        if (mysqli_query($con, $addNewsSql)) {
            $newsId = mysqli_query($con, $getNewsSql);
            $newsId = mysqli_fetch_array($newsId);
            $newsId = $newsId['newsId'];

            foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
                $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
                $imageUploadPath = './../' . $uploadPath . $file_name;

                $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                if (in_array(strtolower($ext), $allowTypes)) {
                    $file_tmp = $_FILES["image"]["tmp_name"][$key];
                    $compressedImage = compressImage($file_tmp, $imageUploadPath, 50);
                    $addImageSql = "INSERT INTO `images`(`img`, `category`, `categoryId`) VALUES ('$compressedImage', 'news', '$newsId')";
                    if (mysqli_query($con, $addImageSql)) {
                        $status = 'success';
                    }
                } else {
                    array_push($error, "$file_name, ");
                }
            }
            echo "<script>alert(`News Added!`); window.location.href = `./news-reporting.php`;</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <title>News Reporting | 3234D2</title>
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='../assets/img/favicon.ico' />
    <link rel="stylesheet" href="./../bootstrap-4.1.3-dist/css/bootstrap.min.css" />
</head>

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
                if($member === "lion member") { 
                include './member-sidebar.php';
                } elseif($member === "Club Treasurer") {
                include './clubtreasurer-sidebar.php';
                } elseif($member === "Club Secretary") {
                include './clubsecretary-sidebar.php';
                } else {
                include './clubpresident-sidebar.php';
                }
            ?>

            

    <?php include './header.php' ?>
    <div class="main-content">
        <section class="section">
        <?php if ($user['verified'] == 0) {
            echo '<h1 class="text-center">Your Profile isn\'t updated!</h1>';
        }
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
            $power = false;
        }
        ?>

        <h2 style="color:#585858;" class="text-center">News Reporting</h2>
        <hr>
        <form action="./news-reporting.php" method="post" class="form-group" enctype="multipart/form-data" id="form">
        <div class="form-group">
            <label for="newsTitle">Enter News Title*</label>
            <input type="text" name="newsTitle" id="newsTitle" class="form-control" required placeholder="Enter News Title*">
        </div>
        <div class="form-group">
            <label for="date">Enter News Date*</label>
            <input type="date" name="date" id="date" class="form-control" placeholder="Enter News Date*">
        </div>
        <div class="form-group">
            <label for="description">Enter Description*</label>
            <textarea name="description" id="description" required placeholder="Enter Description*" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="url">Enter Url</label>
            <input type="url" name="newsPaperLink" id="url" placeholder="Enter Url" class="form-control">
        </div>
        <div class="form-group">
            <label for="image">Upload Image*</label>
            <input type="file" name="image[]" id="image" required accept="image/*" multiple class="form-control">
        </div>
            <br>
            <input type="submit" value="Add News" name="add-news" class="btn btn-success" <?php if ($user['verified'] == 0) {
                                                                                                echo 'disabled';
                                                                                            } ?>>
            <script>
                $("input[type='submit']").click(function(e) {
                    var $fileUpload = $("input[type='file']");
                    if (parseInt($fileUpload.get(0).files.length) > 3) {
                        alert("You can only upload a maximum of 3 files");
                        e.preventDefault();
                        // window.location.href = "./activity-reporting.php";
                    }
                });

                $('#form').on('submit', (e) => {
                    let r = confirm("Are you sure you want to submit?");
                    if (!r) {
                        e.preventDefault()
                    }
                })
            </script>
            <p class="text-center"><br><b>Disclaimer: Any false/irrelevant/unparliamentary data/information fed will
                    lead to
                    strict actions being taken against the respective clubs/members, that may lead to BAN and/or REMOVAl
                    of
                    the
                    involved clubs/members. </b><br><br></p>
        </form>
        <?php if ($power) : ?>
            <ul class="list-group" id="verify-news">
                <h2 style="color: #585858;" class="text-center">Verify News</h2>
                <?php foreach ($unverifiedNews as $news) : ?>
                    <?php
                    $id = $news['newsId'];
                    $img = $con->query("SELECT * FROM `images` WHERE `categoryId` = '$id' AND `category` = 'news' LIMIT 1");
                    $img = $img->fetch_assoc();
                    ?>
                    <li class="list-group-item">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-md-3" style="margin: 1rem auto;">
                                    <img src="/<?= $img['img'] ?>" alt="" style="width: 100%;">
                                    <a href="./approve-news.php?id=<?= $news['newsId'] ?>">Verify</a><br>
                                    <a href="./approve-news.php?delete=<?= $news['newsId'] ?>" style="color: firebrick;">Delete</a>
                                </div>
                                <div class="col-md-8 text-center" style="margin: 1rem auto;">
                                    <h4><?= $news['newsTitle'] ?></h4>
                                    <p><?= $news['description'] ?></p>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>
    </section>
    <!-- <?php include './footer.php' ?> -->
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