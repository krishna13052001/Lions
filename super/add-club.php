<?php
include './../creds.php';
session_start();
$con = new mysqli($host, $user, $pass, $dbname);
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (!isset($_SESSION['super-id'])) {
    header("Location: ./super-login.php");
} else {
    $id = $_SESSION['super-id'];
    $super = $con->query("SELECT * FROM `super` WHERE `id` = '$id'");
    $super = $super->fetch_assoc();

    $limit = 50;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $start = ($page - 1) * $limit;

    $result = $con->query("SELECT * FROM `clubs` ORDER BY `clubName` LIMIT $start, $limit");
    $count = $con->query("SELECT count(`clubId`) AS `id` FROM `clubs`");

    $result = $result->fetch_all(MYSQLI_ASSOC);

    $count = $count->fetch_all(MYSQLI_ASSOC);
    $count = $count[0]['id'];

    $pages = ceil($count / $limit);

    $prev = $page > 1 ? $page - 1 : 1;
    $next = $pages > ($page + 1) ? ($page + 1) : $pages;

    if (isset($_GET['delete-id'])) {
        $clubId = $_GET['delete-id'];

        $con->query("DELETE FROM `clubs` WHERE `clubId` = '$clubId'");
        $con->query("DELETE FROM `users` WHERE `clubId` = '$clubId'");
        header("Location: ./add-club.php");
    } else if (isset($_POST['add-club'])) {
        $clubName = $_POST['clubName'];
        $clubName = strip_tags($clubName);
        $clubName = $con->real_escape_string($clubName);

        $clubId = $_POST['clubId'];
        $clubId = strip_tags($clubId);
        $clubId = $con->real_escape_string($clubId);

        if ($con->query("INSERT INTO `clubs`(`clubId`, `clubName`) VALUES ('$clubId', '$clubName')")) {
            header("Location: ./add-club.php");
        }
    }
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
    <title>Manage Clubs | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
     <link rel="stylesheet" type="text/css" href="./../links/css/util.css">
            <link rel="stylesheet" type="text/css" href="./../links/css/busi.css">

   <section class="members">
        <div class="container">
            <h2 class="text-center animated">Welcome
                <?php echo $super['title'] . ' ' . $super['name'] ?>
            </h2>
            <h4 class="text-center">Add Club</h4>
            <div class="row">
              <div class="col-lg-6">
                <form action="./add-club.php" method="post" class="form-group">
                    <p>Enter Club Name*</p>
                    <input type="text" name="clubName" id="" class="form-control" required placeholder="Enter Club Name*">
                  </form>
              </div>
              <div class="col-lg-6">
                <form action="./add-club.php" method="post" class="form-group">
                  <p>Enter Club Id*</p>
                  <input type="number" name="clubId" id="" class="form-control" required placeholder="Enter Club Id*">
                  </form>
              </div>
            </div>
                  <div class="row">
              <div class="col-lg-2">
              </div>
              <div class="col-lg-8">
                <div style="text-align:center;">
                  <input type="submit" value="Add Club" class="btn btn-danger" name="add-club">
                </div>
                <div class="ser">
  			             <input type="text" name="" id="myInput" placeholder="Enter the Club Name to search " onkeyup="searchfun()" style="width:100%;">
  			         </div>
              </div>
              <div class="col-lg-2">
              </div>
            </div>


            <br>
            <div style="text-align:center;padding:10px;">
              <h2 class="text-center animated">All Clubs</h2>
            </div>

            <div class="wrap-table100">
            <div class="table100">
              <table id="myTable">
                <thead>
                  <tr class="table100-head">
                            <th>Id</th>
                            <th>Club Name</th>
                            <th>Admin Stars</th>
                            <th>Activity Stars</th>
                            <th>Last Update</th>
                            <th>Delete!</th>
                            <th>View!</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $club) : ?>
                        <tr>
                            <td><?= $club['clubId'] ?></td>
                            <td><?= $club['clubName'] ?></td>
                            <td><?= $club['stars'] ?></td>
                            <td><?= $club['activityStar'] ?></td>
                            <td><?= $club['last-updated'] ?></td>
                            <td><a onclick="confirmDelete(event)" href="./add-club.php?delete-id=<?= $club['clubId'] ?>"
                                    style="color: firebrick; text-decoration: none;">Delete</a></td>
                            <td><a href="./club-performance.php?id=<?= $club['clubId'] ?>"
                                    style="color: dodgerblue; text-decoration: none;">View</a></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
          </div>
            <br>
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
                        <?php for ($i = 1; $i <= $pages; $i++) : ?>
                        <li class="page-item <?= $_GET['page'] == $i ? 'active' : ''; ?>"><a class="page-link"
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
    </section>
    <?php include './footer.php' ?>
</body>
<script>
		const searchfun = () =>{
			let filter = document.getElementById('myInput').value.toUpperCase();
			let myTable  = document.getElementById('myTable');
			 let tr = myTable.getElementsByTagName('tr');

			 for(var i = 0 ; i<tr.length; i++){
				 let td = tr[i].getElementsByTagName('td')[1];

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
	</script>
<script>
const confirmDelete = (e) => {
    let r = confirm("Are you sure you want to DELETE this member?");
    if (!r) {
        e.preventDefault();
        // window.location.href = "./add-member.php"
    }
}
</script>

</html>