<?php

include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);

if (!isset($_GET['clubId'])) {
    header("Location: ./organization-chart.php");
} else {
    $clubId = $_GET['clubId'];
    $clubId = $con->real_escape_string(strip_tags($clubId));

    $users = $con->query("SELECT * FROM `users` WHERE `clubId` = '$clubId'");
    $users = $users->fetch_all(MYSQLI_ASSOC);

    if (isset($_GET['download-members'])) {
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
        $res = $con->query("SELECT `regionName`, `zoneName`, `clubId`, `clubName`, `id`, `firstName`, `middleName`, `lastName`, `address1`, `address2`, `city`, `state`, `postalCode`, `email`, `phone`, `spouseName`, `dob`, `gender`, `occupation` FROM `users` WHERE `clubId` = '$clubId'");
        $array = array();
        while ($row = mysqli_fetch_assoc($res)) {
            $array[] = $row;
        }
        download_send_headers("data_export_Club_ID-" . $clubId . date("Y-m-d") . ".csv");
        echo array2csv($array);
        die();
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
    <title>View Members | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>
    
    <!--===============================================================================================-->

    <link rel="stylesheet" type="text/css" href="links/css/util.css">
  <link rel="stylesheet" type="text/css" href="links/css/busi.css">
  <!--===============================================================================================-->
  
     <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">View Members
          </h1>

        </div>
      </section>
      
     
      
      
  <section class="form-body ankit">
         <div class="row">

        <div class="col-lg-4">
         
           
        </div>
        <div class="col-lg-4">
          <a href="#" class="btn-get-started"  onclick="downloadAll()" >Download All Members Data</a>
        </div>
        <div class="col-lg-4">
          
        </div>
      </div>
            <script>
            const downloadAll = () => {
                window.location.href = "./view-members.php?clubId=<?= $clubId ?>&download-members=true";
            }
            </script>
       
            <div class="wrap-table100">
				<div class="table100">
					<table id="myTable">
						<thead>
							<tr class="table100-head">
                        <th class="column1">Id</th>
                        <th class="column2">Title</th>
                        <th class="column3">First Name</th>
                        <th class="column4">Middle Name</th>
                        <th class="column5">Last Name</th>
                        <th class="column6">Email Id</th>
                        <th class="column7">Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['title'] ?></td>
                        <td><?= $user['firstName'] ?></td>
                        <td><?= $user['middleName'] ?></td>
                        <td><?= $user['lastName'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['gender'] ?></td>
                    </tr>
                    <?php endforeach ?>
              	</tbody>
					</table>
				</div>
			</div>
     
    </section>
    <?php include './includes/footer.php' ?>
</body>

</html>
