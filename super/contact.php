<?php
include './../creds.php';
session_start();
$con = new mysqli($host, $user, $pass, $dbname);
if (!isset($_SESSION['super-id'])) {
    header("Location: ./super-login.php");
} else {
    $id = $_SESSION['super-id'];
    $super = $con->query("SELECT * FROM `super` WHERE `id` = '$id'");
    $super = $super->fetch_assoc();

    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


    $limit = 20;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $start = ($page - 1) * $limit;


    $contact = $con->query("SELECT * FROM `contact` ORDER BY `id` DESC LIMIT $start, $limit");
    $contact = $contact->fetch_all(MYSQLI_ASSOC);

    $count = $con->query("SELECT count(`id`) AS `id` FROM `contact`");
    $count = $count->fetch_all(MYSQLI_ASSOC);
    $count = $count[0]['id'];

    $pages = ceil($count / $limit);

    $prev = $page > 1 ? $page - 1 : 1;
    $next = $pages > ($page + 1) ? ($page + 1) : $pages;
}

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
    <title>Contact | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
    <section class="members">
        <h2 class="text-center" style="text-transform: capitalize !important;">Welcome
            <?php echo $super['title'] . ' ' . $super['name'] ?>
        </h2>
        <br>
        <ul class="list-group">
            <?php foreach ($contact as $req) : ?>
            <li class="list-group-item">
                <strong>Query: <?= $req['query'] ?></strong><br>
                <p>
                    Name: <?= $req['name'] ?><br>
                    <strong><?= $req['message'] ?></strong><br>
                    Number: <a href="tel:<?= $req['number'] ?>">Call</a><br>
                    Email: <a href="mailto:<?= $req['email'] ?>">Email (Personal)</a>
                </p>
            </li>
            <?php endforeach ?>
        </ul>
        <br>
        <div class="container">
            <nav aria-label="Page navigation example" class="table-responsive mb-2">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="./contact.php?page=<?= $prev; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $pages; $i++) : ?>
                    <li class="page-item <?= $_GET['page'] == $i ? 'active' : ''; ?>"><a class="page-link"
                            href="./contact.php?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item">
                        <a class="page-link" href="./contact.php?page=<?= $next; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
    <?php include './footer.php' ?>
</body>

</html>