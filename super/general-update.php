<?php
include './../creds.php';
session_start();
include './../upload-image.php';
$con = new mysqli($host, $user, $pass, $dbname);
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if (!isset($_SESSION['super-id'])) {
    header("Location: ./super-login.php");
} else {
    $id = $_SESSION['super-id'];
    $super = $con->query("SELECT * FROM `super` WHERE `id` = '$id'");
    $super = $super->fetch_assoc();

    $terms_query = "SELECT * FROM `general-content` WHERE `type` = 'terms-and-conditions'";
    $terms = mysqli_query($con, $terms_query);
    $terms = mysqli_fetch_array($terms);

    $policy_query = "SELECT * FROM `general-content` WHERE `type` = 'privacy-policy'";
    $policy = mysqli_query($con, $policy_query);
    $policy = mysqli_fetch_array($policy);

    $activityTypes = $con->query("SELECT DISTINCT `type` FROM `activitytype`")->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['update-terms-and-conditions'])) {
        $terms = $_POST['terms-and-conditions'];
        $terms = mysqli_real_escape_string($con, trim($terms));

        $sql = "UPDATE `general-content` SET `description`='$terms' WHERE `type` = 'terms-and-conditions'";
        if (mysqli_query($con, $sql)) {
            header("Location: ./general-update.php");
        }
    } else if (isset($_POST['update-privacy-policy'])) {
        $terms = $_POST['privacy-policy'];
        $terms = mysqli_real_escape_string($con, trim($terms));

        $sql = "UPDATE `general-content` SET `description`='$terms' WHERE `type` = 'privacy-policy'";
        if (mysqli_query($con, $sql)) {
            header("Location: ./general-update.php");
        }
    } else if (isset($_POST['add-event'])) {

        $status = 'error';
        $message = '';
        $error = array();
        $response = array();

        $eventTitle = $_POST['eventTitle'];
        $eventTitle = strip_tags($eventTitle);
        $eventTitle = mysqli_real_escape_string($con, $eventTitle);

        //Check if amount is present, present => paid, else free
        if (isset($_POST['amount'])) {
            $amount = $_POST['amount'];
            $amount = strip_tags($amount);
            $amount = mysqli_real_escape_string($con, $amount);
        } else {
            $amount = 0;
        }

        if (isset($_POST['venue']) && $_POST['venue'] != null) {
            $venue = $_POST['venue'];
            $venue = strip_tags($venue);
            $venue = mysqli_real_escape_string($con, $venue);
        } else {
            $venue = 'online';
        }

        $chiefGuest = $_POST['chiefGuest'];
        $chiefGuest = strip_tags($chiefGuest);
        $chiefGuest = mysqli_real_escape_string($con, $chiefGuest);

        if (isset($_POST['date']) && $_POST['date'] != null) {
            $date = $_POST['date'];
            $date = strip_tags($date);
            $date = mysqli_real_escape_string($con, $date);
        } else {
            $date = date('Y-m-d');
        }

        $description = $_POST['description'];
        $description = strip_tags($description);
        $description = mysqli_real_escape_string($con, $description);

        $eventType = $_POST['eventType'];
        $eventType = strip_tags($eventType);
        $eventType = mysqli_real_escape_string($con, $eventType);

        if ($eventType == 'free') {
            $amount = 0;
        }

        $authorId = $id;

        $clubId = 0;

        $addEventSql = "INSERT INTO `events`(`eventTitle`, `amount`, `chiefGuest`, `date`, `description`, `eventType`, `authorId`, `clubId`, `district`) VALUES ('$eventTitle', '$amount', '$chiefGuest', '$date', '$description', '$eventType', '$authorId', '$clubId', '$venue')";
        $getEventIdSql = "SELECT `eventId` FROM `events` WHERE `eventTitle` = '$eventTitle' AND `date` = '$date' AND `authorId` = '$authorId' AND `clubId` = '$clubId' AND `eventType` = '$eventType' ";

        if (mysqli_query($con, $addEventSql)) {
            $eventId = mysqli_query($con, $getEventIdSql);
            $eventId = mysqli_fetch_array($eventId);
            $eventId = $eventId['eventId'];

            foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
                $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
                $imageUploadPath = './../' . $uploadPath . $file_name;

                $ext = pathinfo($file_name, PATHINFO_EXTENSION);

                if (in_array(strtolower($ext), $allowTypes)) {
                    $file_tmp = $_FILES["image"]["tmp_name"][$key];
                    $compressedImage = compressImage($file_tmp, $imageUploadPath, 50);
                    $addImageSql = "INSERT INTO `images`(`img`, `category`, `categoryId`) VALUES ('$compressedImage', 'events', '$eventId')";
                    if (mysqli_query($con, $addImageSql)) {
                        $status = 'success';
                    }
                } else {
                    array_push($error, "$file_name, ");
                }
            }
        }

        $response += array('status' => $status);
        $response += array('imgError' => $error);
    } else if (isset($_POST['add-activity-category'])) {
        $type = $con->real_escape_string(strip_tags($_POST['type']));
        $category = $con->real_escape_string(strip_tags($_POST['category']));
        $placeholder = $con->real_escape_string(strip_tags($_POST['placeholder']));

        if ($con->query("INSERT INTO `activitytype`(`type`, `sub-type`, `category`, `beneficiaries`, `star`, `placeholder`) VALUES ('$type','Others','$category','9999999999','1','$placeholder')")) {
            echo "<script>alert(`$category successfully added under Others in $type`); window.location.href=`./general-update.php`</script>";
        } else {
            echo "<script>alert(`Could not Add $placeholder`); window.location.href=`./general-update.php`</script>";
        }
    } else if (isset($_POST['add-resource'])) {
        $folder_path = './../uploads/';

        $title = $_POST['title'];
        $title = $con->real_escape_string(strip_tags($title));

        $category = $con->real_escape_string(strip_tags($_POST['category']));

        $filename = rand(1000, 10000) . '-' . basename($_FILES['file']['name']);
        $newname = $folder_path . $filename;

        $FileType = pathinfo($newname, PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $newname)) {

            if ($con->query("INSERT INTO `resources`(`title`, `path`, `category`) VALUES ('$title', '$newname', '$category')")) {
                echo '<script>alert(`File Uploaded`)</script>';
            } else {
                echo '<script>alert(`Something went Wrong`)</script>';
            }
        } else {

            echo "<script>alert(`Upload Failed!`)</script>";
        }
    } else if (isset($_GET['delete-resource-id'])) {
        $rid = $_GET['delete-resource-id'];
        $path = $con->query("SELECT `path` FROM `resources` WHERE `id` = '$rid'");
        $path = $path->fetch_assoc();
        $path = $path['path'];
        unlink($path);
        if ($con->query("DELETE FROM `resources` WHERE `id` = '$rid'")) {
            echo "<script>alert(`Resource Deleted!`); window.location.href=`./general-update.php`</script>";
        }
    }

    $resources = $con->query("SELECT * FROM `resources` ORDER BY `id` DESC");
    $resources = $resources->fetch_all(MYSQLI_ASSOC);
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
  <title>General | 3234D2</title>
</head>

<body>
  <?php include "./header.php" ?>
  <section class="members">
    <div class="section-title">
      <h2 class="text-center animated" style="padding:10px;">Welcome
        <?php echo $super['title'] . ' ' . $super['name'] ?>
      </h2>
    </div>

    <div class="col-lg-12">
      <div class="row">
        <div class="col-md-6">
          <h4 class="text-center">Update Terms and Conditions</h4>
          <form action="./general-update.php" method="post" autocomplete="off" class="form-group">
            <textarea name="terms-and-conditions" id="terms-and-conditions" rows="5" required placeholder="Enter Terms and Conditions"><?php echo $terms['description']; ?></textarea>
            <br>
            <div style="text-align:center;">
              <input type="submit" value="Update" class="btn btn-primary" name="update-terms-and-conditions">
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <h4 class="text-center">Update Privacy Policies</h4>
          <form action="./general-update.php" method="post" autocomplete="off" class="form-group">
            <textarea name="privacy-policy" id="privacy-policy" rows="5" required placeholder="Enter Privacy Policies"><?php echo $policy['description']; ?></textarea>
            <br>
            <div style="text-align:center;">
              <input type="submit" value="Update" class="btn btn-primary" name="update-privacy-policy">
            </div>
          </form>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <h4 class="text-center">Add Paid Event</h4>
              <div class="row">

                <div class="col-md-6">
                  <form action="./general-update.php" method="post" class="form-group" enctype="multipart/form-data" id="form">
                    <input type="text" name="eventTitle" id="" class="form-control" required placeholder="Enter Event Title*">
                    <br>
                    <select name="eventType" required class="form-control">
                      <option value="" disabled selected>Select Event Type</option>
                      <option value="free">Free</option>
                      <option value="paid">Paid</option>
                    </select>
                    <br>
                    <input type="number" name="amount" id="" class="form-control" placeholder="Enter Amount*" required>
                  </form>
                </div>
                <div class="col-md-6">
                  <form action="./general-update.php" method="post" class="form-group" enctype="multipart/form-data" id="form">
                    <input type="text" name="venue" id="" class="form-control" placeholder="Enter Venue" required><br>
                    <input type="text" name="chiefGuest" id="" class="form-control" placeholder="Enter Chief Guest Name" value=""><br>

                    <input type="file" name="image[]" id="" multiple accept="image/*" required class="form-control">
                  </form>
                </div>
              </div>

              <form action="./general-update.php" method="post" class="form-group" enctype="multipart/form-data" id="form">
                <textarea name="description" id="" required placeholder="Enter Event Description*" class="form-control"></textarea><br>
                <div style="text-align:center;">
                  <input type="submit" value="Create" name="add-event" class="btn btn-success" style="width:20%;">
                </div>

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
              </form>
            </div>
            <div class="col-md-6">
              <h4 class="text-center">Add Activity Type</h4>
              <form action="./general-update.php" class="form-group" method="POST">
                <select name="type" required class="form-control">
                  <option value="" disabled selected>Select Activity Type</option>
                  <?php foreach ($activityTypes as $type) : ?>
                  <option value="<?= $type['type'] ?>"><?= $type['type'] ?></option>
                  <?php endforeach; ?>
                </select>
                <br>
                <p style="margin-bottom: 20px;">Note: The category entered here will be visible under Others Sub-Type in activity
                  reporting</p>
                <input type="text" name="category" required placeholder="Enter Category*" class="form-control">
                <br>
                <input type="text" name="placeholder" required placeholder="Enter Placeholder*" class="form-control">
                <br>
                <div style="text-align:center;">
                  <input type="submit" value="Add" class="btn btn-primary" name="add-activity-category" style="width:20%;">
                </div>
              </form>
            </div>
          </div>
        </div>


        <div class="col-md-12">
          <h2 class="text-center animated">View Activities Status</h2>
          <div id="activityData"></div>
        </div>
        <div class="col-md-6">
          <h4 class="text-center">Add Downloadable Resource</h4>
          <br>
          <div class="row">
            <div class="col-md-6">
            <form action="./general-update.php" method="post" autocomplete="off" class="form-group" enctype="multipart/form-data">
              <input type="text" name="title" id="title" class="form-control" required placeholder="Enter Document Title*">
            </form>
            </div>
            <div class="col-md-6">
            <form action="./general-update.php" method="post" autocomplete="off" class="form-group" enctype="multipart/form-data">
              <select name="category" required class="form-control">
                <option value="" disabled selected>Select Category</option>
                <option value="district">District</option>
                <option value="international">International</option>
              </select>
            </form>
            </div>
          </div>

          <div style="text-align:center;">
            <form action="./general-update.php" method="post" autocomplete="off" class="form-group" enctype="multipart/form-data">
              <p>Upload PDF</p>
              <input type="file" name="file" id="file" class="form-control" required>
            <br>
              <input type="submit" value="Add" class="btn btn-primary" name="add-resource" style="width:20%;">
            </form>
          </div>
        </div>
        <div class="col-md-6">
          <h4 class="text-center">Delete Downloadable Resource</h4>
          <div class="row">
            <?php foreach ($resources as $resource) : ?>
            <div class="list-group-item col-md-3">
              <h6 class="titleP">
                <?= $resource['title'] ?><br><small><?= ucfirst($resource['category']) ?></small>
              </h6>
              <style media="screen">
              .btn-sm{
                padding: 5px;
              }
              </style>
              <div>
                <button type="button" name="button" class="btn btn-outline-primary btn-sm"  href="<?= $resource['path'] ?>" target="_blank" >View</button>
                <button type="button" name="button" class="btn btn-outline-danger btn-sm"  href="./general-update.php?delete-resource-id=<?= $resource['id'] ?>">Delete</button>
              </div>

            </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <br><br>
  <?php include "./footer.php" ?>
</body>
<script src="./../ckeditor/ckeditor.js"></script>
<script src="../url.js"></script>

<style media="screen">

/*--------------------------------------------------------------
# Chart
--------------------------------------------------------------*/


.Chart .box {
  padding: 20px;
  background: #eff5f7;
  text-align: center;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.12);
  border-radius: 5px;
  position: relative;
  overflow: hidden;
  transition: all .3s ease-in-out;
}


.Chart .box:hover {
  transform: scale(1.05);
}

.Chart h3 {
  font-weight: 400;
  margin: -20px -20px 20px -20px;
  padding: 20px 15px;
  font-size: 16px;
  font-weight: 600;
}

.Chart span {
  color: darkslategrey;
  font-size: 12px;
  font-weight: 300;
}

.Chart ul {
  padding: 0;
  list-style: none;
  color: #444444;
  text-align: center;
  line-height: 20px;
  font-size: 14px;
}

.Chart ul li {
  padding-bottom: 16px;
}


.Chart .featured h3 {
  color: white;
  background: #084f9d;
  height:70px;
}

.gtext{
  padding: 20px;
  text-align: center;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.12);
  border-radius: 5px;
  position: relative;
  overflow: hidden;
  transition: all .3s ease-in-out;
}

.gtext:hover {
  transform: scale(1.05);
}

</style>

<script>
  CKEDITOR.replace("privacy-policy");
  CKEDITOR.replace("terms-and-conditions");

  const activityData = document.querySelector('#activityData');

  $(async () => {
    let template = `<ul class="list-group"></ul>`;
    await fetch(base + '?get-data').then(res => {
      res.json().then(res => {
        console.log(res)
        res.forEach(elmnt => {
          template += `
                <li class="list-group-item">
                <div class="gtext">
                    <h3>${elmnt.Type}</h3>
                    <h5>Total Amount Spent: <strong>${elmnt['Amount Spent']}</strong><br/>
                        Benefeciaries: <strong>${elmnt['Benefeciaries']}</strong>
                    </h5>
                </div><br />
                    <div class="row mb-5 Chart">
                        ${
                            elmnt.Details.map(sub => {
                            return `
                          <div class="col-lg-2 col-md-3 col-sm-6" style=" padding: 10px;">
                            <div class="box featured" style="height:200px;">
                                <h3>${sub['activitySubType']}</h3>
                                <ul>
                                <li>Amount: ${sub['amount']}</li>
                                <li>Benefeciaries: ${sub['people-served']}</li>
                                </ul>
                            </div>
                          </div>
                            `;
                        }).join('')
                    }
                    </div>
                </li>`
        });
        template += `</ul>`
        activityData.innerHTML = template;
      });
    });
  });
</script>

</html>
