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

    $events = isset($_GET['events']) ? $con->real_escape_string(strip_tags($_GET['events'])) : ' ';
    $eventType = isset($_GET['eventType']) ? $con->real_escape_string(strip_tags($_GET['eventType'])) : ' ';

    $sql = "SELECT * FROM `images`, `events` WHERE `images`.`categoryId` = `events`.`eventId` AND `category` = 'events' AND `eventTitle` LIKE '%$events%' AND `eventType` LIKE '%$eventType%' GROUP BY `events`.`eventId` ORDER BY `events`.`date` DESC ";

    $result = $con->query($sql . "LIMIT " . $start . ", " . $limit);
    $count = $con->query($sql)->num_rows;
} else {
    $result = $con->query("SELECT * FROM `images`, `events` WHERE `images`.`categoryId` = `events`.`eventId` AND `category` = 'events' GROUP BY `events`.`eventId` ORDER BY `events`.`date` DESC LIMIT $start, $limit");
    $count = $con->query("SELECT * FROM `images`, `events` WHERE `images`.`categoryId` = `events`.`eventId` AND `category` = 'events' GROUP BY `events`.`eventId`")->num_rows;
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
    <title>Event | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>

    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">Events
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
            <form class="form-group" action="./event.php" method="get">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-control" style="margin: 0.5rem auto;" name="eventType">
                                <option value="" selected>All</option>
                                <option value="free">Free</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" placeholder="Search Event.." class="form-control"
                                style="margin: 0.5rem auto;" name="events" />
                        </div>
                        <div class="col-md-4">
                            <input type="submit" value="Search" name="search" style="margin: 0.5rem auto;"
                                class="btn btn-primary" />
                            &nbsp;<a href="./event.php" class="btn btn-danger">Reset</a>
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
                                data-target="#myModal" class="lazy" onclick="showModal(<?= $row['eventId'] ?>)" />
                        <div class="portfolio-info">
                          <h4><?= $row['eventTitle'] ?><br><?= $row['date'] ?></h4>
                        </div>
                      </div>
                      <?php endforeach; ?>


                    </div>
                  </div>
                </section><!-- End Portfolio Section -->
            
            
            
            
            <!--<div class="row" id="allEvents">-->
            <!--    <?php foreach ($result as $row) : ?>-->
            <!--    <div class="col-md-3" style="margin: 1rem auto;">-->
            <!--        <div class="row">-->
            <!--            <div class="col-lg-12">-->
            <!--                <img src="./img/loading.gif" data-src="<?= $row['img'] ?>"-->
            <!--                    style="width: 100%; height: 15rem; cursor:pointer;" alt="" data-toggle="modal"-->
            <!--                    data-target="#myModal" class="lazy" onclick="showModal(<?= $row['eventId'] ?>)" />-->
            <!--            </div>-->
            <!--            <div class="col-lg-12">-->
            <!--                <strong class="text-center"><?= $row['eventTitle'] ?><br><?= $row['date'] ?></strong>-->
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
    let eventDetails;
    let eventImages;
    modalBody.innerHTML = "<br/><h3>Loading..</h3>";
    $.ajax({
        url: base + "?category=events&view=true&id=" + id,
        type: "GET",
        dataType: "json", // added data type
        success: function(res) {
            eventDetails = {
                ...res[0]["event"]
            };
            eventImages = {
                ...res[0]["image"]
            };
            modalBody.innerHTML = `
          <h2>${eventDetails["eventTitle"]}</h2>
          <p>Date: ${eventDetails["date"]}</p>
          <h4>Event type: ${eventDetails["eventType"]} ${
            eventDetails["eventType"] == "paid"
              ? "<br/> Amount : " + eventDetails["amount"]
              : ""
          }</h4>
          <h4>Chief Guest: ${eventDetails["chiefGuest"]}</h4>
          <h4>${
            eventDetails["district"] !== "online"
              ? "District: " + eventDetails["district"]
              : "Online Event"
          }</h4>
          <p>${eventDetails["description"]}</p>
          `;
            for (const img in eventImages) {
                if (eventImages.hasOwnProperty(img)) {
                    const element = eventImages[img];
                    modalBody.innerHTML += `
                <span style="display: inline-block; width: 30%;"><img src="${uploadUrl}${element["img"]}" style="width: 100%; max-height: 7rem;" /></span>
              `;
                }
            }
            modalBody.innerHTML +=
                `<br /><br/><div><a href="./book-ticket.php?e=${eventDetails.eventId}" class="btn btn-success">Join</a>&nbsp;<button class="btn btn-danger" data-dismiss="modal">Cancel</button></div>`;
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
