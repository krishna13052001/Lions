<?php

include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$limit = 28;
$page;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = ($page - 1) * $limit;

if (isset($_GET['search'])) {

    $news = $con->real_escape_string(strip_tags($_GET['news']));

    $result = $con->query("SELECT * FROM `images`, `news` WHERE `images`.`categoryId` = `news`.`newsId` AND `category` = 'news' AND `newsTitle` LIKE '%$news%' GROUP BY `news`.`newsId` ORDER BY `news`.`date` DESC LIMIT $start, $limit");
    $count = $con->query("SELECT * FROM `images`, `news` WHERE `images`.`categoryId` = `news`.`newsId` AND `category` = 'news' AND `newsTitle` LIKE '%$news%' GROUP BY `news`.`newsId`")->num_rows;
} else {
    $result = $con->query("SELECT * FROM `images`, `news` WHERE `images`.`categoryId` = `news`.`newsId` AND `category` = 'news' GROUP BY `news`.`newsId` ORDER BY `news`.`date` DESC LIMIT $start, $limit");
    $count = $con->query("SELECT * FROM `images`, `news` WHERE `images`.`categoryId` = `news`.`newsId` AND `category` = 'news' GROUP BY `news`.`newsId`")->num_rows;
}

$result = $result->fetch_all(MYSQLI_ASSOC);

$pages = ceil($count / $limit);

$prev = $page > 1 ? $page - 1 : 1;
$next = $pages > ($page + 1) ? ($page + 1) : $pages;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <title>News | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>

    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">News
          </h1>
        </div>
      </section>


    <section class="form-body ankit">
        <div class="modal fade" id="myModal">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body text-center" id="modal-body"></div>
                </div>
            </div>
        </div>

        <div class="">
            <form class="form-group" action="./news.php" method="get">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" placeholder="Search News.." class="form-control"
                                style="margin: 0.5rem auto;" name="news" required />
                        </div>
                        <div class="col-md-6">
                            <input type="submit" value="Search" name="search" style="margin: 0.5rem auto;"
                                class="btn btn-primary" />
                            &nbsp;<a href="./news.php" class="btn btn-danger">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
            
             <!-- ======= Portfolio Section ======= -->
                <section id="portfolio" class="portfolio">
                  <div class="container">

                    <div class="row portfolio-container">
                         <?php foreach ($result as $row) : ?>
                      <div class="col-lg-4 col-md-4 portfolio-item filter-app">
                          <img src="./img/loading.gif" data-src="<?= $row['img'] ?>"
                                style="width: 100%; height: 15rem; cursor:pointer;" alt="" data-toggle="modal"
                                data-target="#myModal" class="lazy" onclick="showModal(<?= $row['newsId'] ?>)" />
                        <div class="portfolio-info">
                          <h4><?= $row['newsTitle'] ?></h4>
                        </div>
                      </div>
                      <?php endforeach; ?>


                    </div>
                  </div>
                </section><!-- End Portfolio Section -->
            
            
            <!--<div class="row" id="allNews">-->
            <!--    <?php foreach ($result as $row) : ?>-->
            <!--    <div class="col-md-3" style="margin: 1rem auto;">-->
            <!--        <div class="row">-->
            <!--            <div class="col-lg-12">-->
            <!--                <img src="./img/loading.gif" data-src="<?= $row['img'] ?>"-->
            <!--                    style="width: 100%; height: 15rem; cursor:pointer;" alt="" data-toggle="modal"-->
            <!--                    data-target="#myModal" class="lazy" onclick="showModal(<?= $row['newsId'] ?>)" />-->
            <!--            </div>-->
            <!--            <div class="col-lg-12">-->
            <!--                <h4 class="text-center"><?= $row['newsTitle'] ?></h4>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <?php endforeach; ?>-->
            <!--</div>-->
            
            
            
        </div>
    </section>

    <div class="container">
        <nav aria-label="Page navigation example" class="table-responsive mb-2">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link"
                        href="<?= $actual_link; ?><?= isset($_GET['search']) ? '' : '?';  ?>&page=<?= $prev; ?>"
                        aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = $page == 1 ? 1 : ($pages >= 10 ? $page - 1 : 1), $j = $i + 10; $i <= $pages && $i <= $j; $i++) : ?>
                <li class="page-item <?= $page == $i ? 'active' : ''; ?>"><a class="page-link"
                        href="<?= $actual_link; ?><?= isset($_GET['search']) ? '' : '?';  ?>&page=<?= $i; ?>"><?= $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item">
                    <a class="page-link"
                        href="<?= $actual_link; ?><?= isset($_GET['search']) ? '' : '?';  ?>&page=<?= $next; ?>"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <?php include './includes/footer.php' ?>
</body>

<script src="./url.js"></script>
<script>
const modalBody = document.querySelector("#modal-body");

const showModal = (id) => {
    let newsDetails;
    let newsImages;
    modalBody.innerHTML = "<br/><h3>Loading..</h3>";
    $.ajax({
        url: base + "?category=news&view=true&id=" + id,
        type: "GET",
        dataType: "json", // added data type
        success: function(res) {
            newsDetails = {
                ...res[0]["news"]
            };
            newsImages = {
                ...res[0]["image"]
            };
            modalBody.innerHTML = `
          <h2><u>${newsDetails["newsTitle"]}</u></h2>
          <p>Date: ${newsDetails["date"]}</p>
          <a href="${newsDetails["newsPaperLink"]}" target="_blank">Link to article..</a>
          <p>${newsDetails["description"]}</p>
          `;
            for (const img in newsImages) {
                if (newsImages.hasOwnProperty(img)) {
                    const element = newsImages[img];
                    modalBody.innerHTML += `
                <span style="display: inline-block; width: 30%;"><img src="${uploadUrl}${element["img"]}" style="width: 100%; max-height: 7rem;" /></span>
              `;
                }
            }
            modalBody.innerHTML +=
                `<br /><br/><div><button class="btn btn-danger" data-dismiss="modal">Close</button></div>`;
        },
    });
};

document.addEventListener("DOMContentLoaded", function() {
    var lazyloadImages = document.querySelectorAll("img.lazy");
    var lazyloadThrottleTimeout;

    function lazyload() {
        if (lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
        }

        lazyloadThrottleTimeout = setTimeout(function() {
            var scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function(img) {
                if (img.offsetTop < (window.innerHeight + scrollTop)) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                }
            });
            if (lazyloadImages.length == 0) {
                document.removeEventListener("scroll", lazyload);
                window.removeEventListener("resize", lazyload);
                window.removeEventListener("orientationChange", lazyload);
            }
        }, 20);
    }

    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
});
</script>

</html>
