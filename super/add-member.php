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

    if (!isset($_GET['search'])) {
        $result = $con->query("SELECT * FROM `users` LIMIT $start, $limit");
        $count = $con->query("SELECT count(`id`) AS `id` FROM `users`");
    } else {
        $search = $_GET['search'];
        $result = $con->query("SELECT * FROM `users` WHERE `title` LIKE '%$search%' OR `firstName` LIKE '%$search%' OR `middleName` LIKE '%$search%' OR `lastName` LIKE '%$search%' OR `clubName` LIKE '%$search%' LIMIT $start, $limit");
        $count = $con->query("SELECT count(`id`) AS `id` FROM `users`  WHERE `title` LIKE '%$search%' OR `firstName` LIKE '%$search%' OR `middleName` LIKE '%$search%' OR `lastName` LIKE '%$search%' OR `clubName` LIKE '%$search%'");
    }
    $result = $result->fetch_all(MYSQLI_ASSOC);

    $count = $count->fetch_all(MYSQLI_ASSOC);
    $count = $count[0]['id'];

    $pages = ceil($count / $limit);

    $prev = $page > 1 ? $page - 1 : 1;
    $next = $pages > ($page + 1) ? ($page + 1) : $pages;

    $regions = $con->query("SELECT DISTINCT `regionName` FROM `users`");
    $regions = $regions->fetch_all(MYSQLI_ASSOC);

    if (isset($_GET['delete-id'])) {
        $Id = $_GET['delete-id'];

        $con->query("DELETE FROM `users` WHERE `id` = '$Id'");
        header("Location: ./add-member.php");
    } else if (isset($_POST['add-member'])) {
        $regionName = $_POST['regionName'];
        $regionName = strip_tags($regionName);
        $regionName = mysqli_real_escape_string($con, $regionName);

        $clubId = $_POST['clubId'];
        $clubId = strip_tags($clubId);
        $clubId = mysqli_real_escape_string($con, $clubId);

        $clubName = $_POST['club'];
        $clubName = strip_tags($clubName);
        $clubName = mysqli_real_escape_string($con, $clubName);

        $zoneName = $_POST['zoneName'];
        $zoneName = strip_tags($zoneName);
        $zoneName = mysqli_real_escape_string($con, $zoneName);

        $firstName = $_POST['firstName'];
        $firstName = strip_tags($firstName);
        $firstName = mysqli_real_escape_string($con, $firstName);

        if (isset($_POST['middleName'])) {
            $middleName = $_POST['middleName'];
            $middleName = strip_tags($middleName);
            $middleName = mysqli_real_escape_string($con, $middleName);
        } else {
            $middleName = "";
        }

        $lastName = $_POST['lastName'];
        $lastName = strip_tags($lastName);
        $lastName = mysqli_real_escape_string($con, $lastName);

        $address1 = $_POST['address1'];
        $address1 = strip_tags($address1);
        $address1 = mysqli_real_escape_string($con, $address1);

        $address2 = $_POST['address2'];
        $address2 = strip_tags($address2);
        $address2 = mysqli_real_escape_string($con, $address2);

        $city = $_POST['city'];
        $city = strip_tags($city);
        $city = mysqli_real_escape_string($con, $city);

        $state = $_POST['state'];
        $state = strip_tags($state);
        $state = mysqli_real_escape_string($con, $state);

        $postalCode = $_POST['postalCode'];
        $postalCode = strip_tags($postalCode);
        $postalCode = mysqli_real_escape_string($con, $postalCode);

        $email = $_POST['email'];
        $email = strip_tags($email);
        $email = mysqli_real_escape_string($con, $email);

        $phone = $_POST['phone'];
        $phone = strip_tags($phone);
        $phone = mysqli_real_escape_string($con, $phone);

        if (isset($_POST['spouseName'])) {
            $spouseName = $_POST['spouseName'];
            $spouseName = strip_tags($spouseName);
            $spouseName = mysqli_real_escape_string($con, $spouseName);
        } else {
            $spouseName = "";
        }

        $dob = $_POST['dob'];
        $dob = strip_tags($dob);
        $dob = mysqli_real_escape_string($con, $dob);

        $gender = $_POST['gender'];
        $gender = strip_tags($gender);
        $gender = mysqli_real_escape_string($con, $gender);

        $occupation = $_POST['occupation'];
        $occupation = strip_tags($occupation);
        $occupation = mysqli_real_escape_string($con, $occupation);

        $userId = $_POST['id'];


        if ($con->query("INSERT INTO `users`(`regionName`, `zoneName`, `clubId`, `clubName`, `firstName`, `middleName`, `lastName`, `address1`, `address2`, `city`, `state`, `postalCode`, `email`, `phone`, `spouseName`, `dob`, `gender`, `occupation`, `id`) VALUES ('$regionName', '$zoneName', '$clubId', '$clubName', '$firstName', '$middleName', '$lastName', '$address1', '$address2', '$city', '$state', '$postalCode', '$email', '$phone', '$spouseName', '$dob', '$gender', '$occupation', '$userId')")) {
            echo "<script>alert(`" . $firstName . "` Added!); window.location.href = `./add-member.php`;</script>";
        }
    } else if (isset($_GET['delete-id'])) {
        $id = $_GET['delete-id'];

        $con->query("DELETE FROM `users` WHERE `id` = '$id'");
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
    <section class="members">
        <div class="container">
            <h2 class="text-center animated">Welcome
                <?php echo $super['title'] . ' ' . $super['name'] ?>
            </h2>
            <h4 class="text-center">Add Member</h4>

            <div class="row">
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Region Name*</span>
                <select name="regionName" id="region" class="form-control" required>
                    <option value="" disabled selected>Select Region Name</option>
                    <?php foreach ($regions as $region) : ?>
                    <option value="<?= $region['regionName'] ?>"><?= $region['regionName'] ?></option>
                    <?php endforeach ?>
                </select>
                </form>
              </div>
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Zone Name*</span>
                <select name="zone" id="zone" class="form-control" required>
                    <option value="" selected disabled>Select Region First</option>

                </select>
                </form>
              </div>
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Club Name*</span>
                <select name="club" id="club" class="form-control" required>
                    <option value="" selected disabled>Select Zone First</option>

                </select>
              </form>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <input type="number" name="clubId" id="clubId" class="form-control" required hidden/>

                <span>Enter Name*</span>
                <input type="text" name="firstName" id="" class="form-control" placeholder="Enter Name*" required/>
                </form>
              </div>
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Middle Name</span>
                <input type="text" name="middleName" id="" class="form-control" placeholder="Enter Middle Name"/>
                </form>
              </div>
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Last Name*</span>
                <input type="text" name="lastName" id="" class="form-control" placeholder="Enter Last Name*" required/>
              </form>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Address Line 1*</span>
                <input type="text" name="address1" id="" class="form-control" placeholder="Enter Address Line 1*"
                    required/>
                    </form>
              </div>
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Address Line 2*</span>
                <input type="text" name="address2" id="" class="form-control" placeholder="Enter Address Line 2*"
                    required/>
                    </form>
              </div>
              <div class="col-lg-4">
                <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter City*</span>
                <input type="text" name="city" id="" class="form-control" placeholder="Enter City*" required/>
              </form>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter State*</span>
                <input type="text" name="state" id="" class="form-control" placeholder="Enter State*" required/>
              </form>
              </div>
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Postal Code*</span>
                <input type="number" name="postalCode" id="" class="form-control" placeholder="Enter Postal Code*"
                    required/>
              </form>
              </div>
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Email* (Required for resetting password!)</span>
                <input type="email" name="email" id="" class="form-control" placeholder="Enter Email*" required/>
              </form>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Phone Number*</span>
                <input type="text" name="phone" id="" class="form-control" placeholder="Enter Phone*" required>
              </form>
              </div>
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Spouse Name</span>
                <input type="text" name="spouseName" id="" class="form-control" placeholder="Enter Spouse Name"/>
              </form>
              </div>
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter D.O.B.*</span>
                <input type="date" name="dob" id="" class="form-control" placeholder="Enter DOB*" required/>
              </form>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Gender*</span>
                <select name="gender" id="" class="form-control" required>
                    <option value="" disabled>Select Gender</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                    <option value="Other">Other</option>
                </select>
              </form>
              </div>
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Occupation*</span>
                <input type="text" name="occupation" id="" class="form-control" placeholder="Enter Occupation*"
                    required>
              </form>
              </div>
              <div class="col-lg-4">
              <form action="./add-member.php" method="POST" class="form-group">
                <span>Enter Id*</span>
                <input type="number" name="id" id="" class="form-control" placeholder="Enter Id*" required>
              </form>
              </div>
            </div>

            <div style="text-align:center;">
              <form action="./add-member.php" method="POST" class="form-group">
                  <input type="submit" value="Submit" name="add-member" class="btn btn-success" style="width:20%;">
              </form>
            </div>

            <br>
            <h2 class="text-center">All Members</h2>
            <div class="option">
    <style>

  /* ------------------------------------ */
  .ser input {
    display: block;
      outline: none;
      /* border: none !important; */
      border-radius: 1.2rem;
      border: 2px solid deepskyblue;
      padding-top: 10px;
      /* padding-left: 0px; */
      padding-bottom: 10px;
      text-align: center;
      margin: 10px;
      width: 860px;
  }

  @media only screen and (max-width: 576px) {
    .ser input{
        padding: 5px;
        width: 207px;
    }
  }

  @media only screen and (max-width: 768px) {
    .ser input{
        padding: 8px;
        width: 207px;
    }
  }
  </style>
            <div class="row">
              <div class="col-lg-2">
              </div>
              <div class="col-lg-8">
                <div style="text-align:center;">
                </div>
                <div class="ser">
  			             <input type="text" name="" id="myInput" placeholder="Enter the Name to search " onkeyup="searchfun()" style="width:100%;">
  			         </div>
              </div>
              <div class="col-lg-2">
              </div>
            </div>
            
                <br>
            </div>
            <div class="container" style="overflow-x: auto;">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Club Name</th>
                            <th>Club Id</th>
                            <th>Title</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Postal Code</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Spouse Name</th>
                            <th>D.O.B.</th>
                            <th>Gender</th>
                            <th>Occupation</th>
                            <th>Delete!</th>
                            <th>Edit!</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $club) : ?>
                        <tr>
                            <td><?= $club['id'] ?></td>
                            <td><?= $club['clubName'] ?></td>
                            <td><?= $club['clubId'] ?></td>
                            <td><?= $club['title'] ?></td>
                            <td><?= $club['firstName'] . " " . $club['middleName'] . " " . $club['lastName'] . " " ?>
                            </td>
                            <td><?= $club['address1'] . "<br/>" . $club['address2'] ?></td>
                            <td><?= $club['city'] ?></td>
                            <td><?= $club['state'] ?></td>
                            <td><?= $club['postalCode'] ?></td>
                            <td><?= $club['email'] ?></td>
                            <td><?= $club['phone'] ?></td>
                            <td><?= $club['spouseName'] ?></td>
                            <td><?= $club['dob'] ?></td>
                            <td><?= $club['gender'] ?></td>
                            <td><?= $club['occupation'] ?></td>
                            <td><a href="./add-member.php?delete-id=<?= $club['id'] ?>"
                                    style="color: firebrick; text-decoration: none;"
                                    onclick="confirmDelete(event)">Delete</a>
                            </td>
                            <td><a href="./edit-member.php?edit-id=<?= $club['id'] ?>"
                                    style="color: dodgerblue; text-decoration: none;">Edit</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
<script src="./../url.js"></script>
<script>
		const searchfun = () =>{
			let filter = document.getElementById('myInput').value.toUpperCase();
			let myTable  = document.getElementById('myTable');
			 let tr = myTable.getElementsByTagName('tr');

			 for(var i = 0 ; i<tr.length; i++){
				 let td = tr[i].getElementsByTagName('td')[4];

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
const regions = document.querySelector('#region');
const zones = document.querySelector('#zone');
const club = document.querySelector('#club');
const clubId = document.querySelector('#clubId');
let region;
regions.addEventListener('change', async () => {
    region = regions.value;
    await fetch(base + `?region=${region}&get-zone=true`).then(async (res) => {
        zones.innerHTML =
            `<option value="" selected disabled>Loading..</option>`
        await res.json().then(res => {
            zones.innerHTML =
                `<option value="" selected disabled>Select Zone</option>`
            res.forEach(zone => {
                zones.innerHTML +=
                    `<option value="${zone['zoneName']}">${zone['zoneName']}</option>`
            })
        })
    })
});

zones.addEventListener('change', async () => {
    region = regions.value;
    zone = zones.value;
    await fetch(base + `?type=clubs&category=all`).then(async (res) => {
        club.innerHTML = `<option value="" selected disabled>Loading..</option>`
        await res.json().then(res => {
            club.innerHTML = `<option value="" selected disabled>Select Club</option>`
            res.forEach(elmnt => {
                club.innerHTML +=
                    `<option value="${elmnt['clubName']}" data-id="${elmnt['clubId']}">${elmnt['clubName']}</option>`
            })
        })
    })
});

club.addEventListener('change', () => {
    clubId.setAttribute('value', ($('#club option:selected').attr('data-id')))
});

const confirmDelete = (e) => {
    let r = confirm("Are you sure you want to DELETE this member?");
    if (!r) {
        e.preventDefault();
        // window.location.href = "./add-member.php"
    }
}
</script>

</html>