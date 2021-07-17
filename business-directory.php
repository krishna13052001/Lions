<?php

include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


$limit = 50;
$page;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start = ($page - 1) * $limit;

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $result = $con->query("SELECT * FROM `users` WHERE (`title` LIKE '%$search%' OR `firstName` LIKE '%$search%' OR `lastName` LIKE '%$search%' OR `clubName` LIKE '%$search%') AND (`occupation` != '-') LIMIT $start, $limit");
    $count = $con->query("SELECT count(`id`) AS `id` FROM `users`  WHERE (`title` LIKE '%$search%' OR `firstName` LIKE '%$search%' OR `lastName` LIKE '%$search%' OR `clubName` LIKE '%$search%') AND (`occupation` != '-')");
} else if (isset($_GET['download-members'])) {
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
    $res = $con->query("SELECT `regionName`, `zoneName`, `clubId`, `clubName`, `id`, `firstName`, `middleName`, `lastName`, `address1`, `address2`, `city`, `state`, `postalCode`, `email`, `phone`, `spouseName`, `dob`, `gender`, `occupation` FROM `users`  WHERE `occupation` != '-'");
    $array = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $array[] = $row;
    }
    download_send_headers("data_export_All_Members" . date("Y-m-d") . ".csv");
    echo array2csv($array);
    die();
} else {
    $result = $con->query("SELECT * FROM `users` WHERE `occupation` != '-' LIMIT $start, $limit");
    $count = $con->query("SELECT count(`id`) AS `id` FROM `users` WHERE `occupation` != '-'");
}
$result = $result->fetch_all(MYSQLI_ASSOC);

$count = $count->fetch_all(MYSQLI_ASSOC);
$count = $count[0]['id'];

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
    <title>Business | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>
    
      <!--===============================================================================================-->

    <link rel="stylesheet" type="text/css" href="links/css/util.css">
  <link rel="stylesheet" type="text/css" href="links/css/busi.css">
  <!--===============================================================================================-->

    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">Business Directory
          </h1>
        </div>
      </section>

    <section class="form-body ankit">
        
          <div class="row">
         <div class="col-lg-6">
          <input type="text" name="" class="form-control" id="myInput" placeholder="Enter Name to Search Directory " onkeyup="searchfun()" style="margin:20px;">
        </div>
          <!--<form action="./business-directory.php" method="GET" class="search-field">-->
          <!--    <input type="text" name="search" id="search"-->
          <!--        placeholder="Enter Name / Designation / Club / Email Id to search" required>-->
          <!--    <button><i class="fa fa-search"></i></button>-->
          <!--</form>-->

        <div class="col-lg-6">
                      <a href="#" class="btn-get-started"  onclick="downloadAll()">Download All Members Data</a>

        </div>
      </div>
      <script>
      const downloadAll = () => {
          window.location.href = "./business-directory.php?download-members=true";
      }
      </script>

        <div class="wrap-table100">
          <div class="table100" >


            <table id="myTable">
                <thead>
                    <tr class="table100-head">
                        <th><strong>Id</strong></th>
                        <th><strong>Title</strong></th>
                        <th><strong>First Name</strong></th>
                        <th><strong>Last Name</strong></th>
                        <th><strong>Club Name</strong></th>
                        <th><strong>Email Id</strong></th>
                        <th><strong>Phone</strong></th>
                        <th><strong>Occupation</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $user) : ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['title'] ?></td>
                        <td><?= $user['firstName'] ?></td>
                        <td><?= $user['lastName'] ?></td>
                        <td><?= $user['clubName'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['phone'] ?></td>
                        <td><?= $user['occupation'] ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </section>
    
    
    
        <div class="pagination p1 justify-content-center">
  <ul>
    <a href="<?= $actual_link; ?><?= isset($_GET['search']) ? '' : '?';  ?>&page=<?= $prev; ?>"
    aria-label="Previous"><li> < </li></a>
<?php for ($i = $page == 1 ? 1 : ($pages >= 10 ? $page - 1 : 1), $j = $i + 10; $i <= $pages && $i < $j; $i++) : ?>
    <a class="is-active <?= $page == $i ? 'active' : ''; ?>"   href="<?= $actual_link; ?><?= isset($_GET['search']) ? '' : '?';  ?>&page=<?= $i; ?>">
      <li><?= $i; ?></li></a>
    <?php endfor; ?>

    <a href="<?= $actual_link; ?><?= isset($_GET['search']) ? '' : '?';  ?>&page=<?= $next; ?>"
    aria-label="Next"><li>></li></a>
  </ul>
  </div>
  </div>
    <script>
		const searchfun = () =>{
			let filter = document.getElementById('myInput').value.toUpperCase();
			let myTable  = document.getElementById('myTable');
			 let tr = myTable.getElementsByTagName('tr');

			 for(var i = 0 ; i<tr.length; i++){
				 let td = tr[i].getElementsByTagName('td')[2];

				 if(td){
					 let textvalue = td.textContent || td.innerHTML;
					 if (textvalue.toUpperCase().indexOf(filter) > -1){
						 tr[i].style.display = "";
					 }
					 else{
						 tr[i].style.display = "none";
					 }
				 }


                }
		}

(function ($) {
    "use strict";

})(jQuery)
	</script>
    
   
    <?php include './includes/footer.php' ?>
</body>
<script src="./url.js"></script>
<script src="./pagination.js"></script>

</html>
