<?php

include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$limit = 40;
$page;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = (int)(($page - 1) * $limit);

if (isset($_GET['search'])) {

    $search = $con->real_escape_string(strip_tags($_GET['search']));

    $sql = "SELECT * FROM `images` WHERE `category` = '$search' ORDER BY `id` DESC ";

    $result = $con->query($sql . "LIMIT " . $start . ", " . $limit);
    $count = $con->query($sql)->num_rows;
} else {
    $result = $con->query("SELECT * FROM `images` ORDER BY `id` DESC LIMIT $start, $limit");
    $count = $con->query("SELECT * FROM `images`")->num_rows;
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
    <title>Gallery | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>

    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">Gallery
          </h1>
        </div>
      </section>


    <section class="form-body ankit">
        <div class="modal fade" id="myModal">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content" id="modal-content">
                    <div class="modal-body text-center" id="modal-body"></div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="option">
                <select id="select-search" class="select form-control">
                    <option value="all">All</option>
                    <option value="events">Events</option>
                    <option value="activities">Activities</option>
                    <option value="news">News</option>
                </select>
            </div>
            
            <!-- ======= Portfolio Section ======= -->
                <section id="portfolio" class="portfolio">
                  <div class="container">

                    <div class="row portfolio-container">
                         <?php foreach ($result as $row) : ?>
                      <div class="col-lg-4 col-md-4 portfolio-item filter-app">
                                                     <img src="./img/loading.gif" data-src="<?= $row['img'] ?>"
                                style="width: 100%; height: 15rem; cursor:pointer;" alt="" data-toggle="modal"
                                data-target="#myModal" data-id="<?= $row['id'] ?>" class="lazy"
                                onclick="showModal(this)" />
                      
                      </div>
                      <?php endforeach; ?>

                    </div>
                  </div>
                </section><!-- End Portfolio Section -->
            
            <!--<div class="row" id="gallery">-->
            <!--    <?php foreach ($result as $row) : ?>-->
            <!--    <div class="col-md-3" style="margin: 1rem auto;">-->
            <!--        <div class="row">-->
            <!--            <div class="col-lg-12">-->
            <!--                <img src="./img/loading.gif" data-src="<?= $row['img'] ?>"-->
            <!--                    style="width: 100%; height: 15rem; cursor:pointer;" alt="" data-toggle="modal"-->
            <!--                    data-target="#myModal" data-id="<?= $row['id'] ?>" class="lazy"-->
            <!--                    onclick="showModal(this)" />-->
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
const modal = document.querySelector("#modal-content");
const search = document.querySelector('#select-search');
let urlParams = new URLSearchParams(window.location.search);

window.addEventListener('load', () => {
    search.value = urlParams.get('search') ? urlParams.get('search') : 'all';
})

search.addEventListener('change', (e) => {
    window.location.href = search.value == 'all' ? './gallery.php' : `./gallery.php?search=${search.value}`;
});

showModal = (elmnt) => {
    modal.innerHTML = `<img src=${elmnt.src} style="width: 100%; max-height: 90vh;"/>`;
    console.log(elmnt.getAttribute('data-id'))
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
