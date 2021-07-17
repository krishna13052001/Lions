<?php
include './../creds.php';
include './../upload-image.php';
session_start();
$con = new mysqli($host, $user, $pass, $dbname);
if (!isset($_SESSION['super-id'])) {
    header("Location: ./super-login.php");
} else {
    $id = $_SESSION['super-id'];
    $super = $con->query("SELECT * FROM `super` WHERE `id` = '$id'");
    $super = $super->fetch_assoc();

    $sliders = $con->query("SELECT * FROM `slider`");
    $sliders = $sliders->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['add-slider'])) {
        foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
            $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
            $imageUploadPath = './../' . $uploadPath . $file_name;

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allowTypes)) {
                $file_tmp = $_FILES["image"]["tmp_name"][$key];
                $compressedImage = compressImage($file_tmp, $imageUploadPath, 50);
                $con->query("INSERT INTO `slider`(`image`) VALUES ('$compressedImage')");
            }
        }
    } else if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $img = $con->query("SELECT * FROM `slider` WHERE `id` = '$id'");
        $img = $img->fetch_assoc();
        unlink($img['image']);

        if ($con->query("DELETE FROM `slider` WHERE `id` = '$id'")) {
            header("Location: ./update-slider.php");
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
    <title>Update Gallery | 3234D2</title>
</head>

<body>
    <?php include_once "./header.php" ?>
    <section class="members">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
        </div>
        <div class="col-lg-4">
          <form action="./update-slider.php" method="post" class="form-group container" enctype="multipart/form-data">
            <div style="text-align:center;">
              <h2 class="animated">Add Slider Image</h2>
            </div>
              <input type="file" name="image[]" id="" class="form-control" accept="image/*" multiple required><br>
              <div style="text-align:center">
                <button type="submit" value="Add" class="btn btn-outline-success" name="add-slider" style="width:140px;">Add</button>
              </div>

            </form>
        </div>
        <div class="col-lg-4">
        </div>
      </div>
    </div>
        <br>
            <div class="container">
        <div class="row" >
            <?php foreach ($sliders as $img) : ?>
          <div class="col-lg-4" >
            <img src="<?= $img['image'] ?>" alt=""
                style="height: 15rem; width: 20rem; display: block; margin: auto; box-shadow: 0px 6px 15px rgb(16 110 234);">
                <div style="text-align:center;padding: 20px;">
                  <button href="./update-slider.php?id=<?php $img['id'] ?>" class="btn btn-outline-danger" type="button" name="button">Delete</button>
                </div>

          </div>
            <?php endforeach ?>

        </div>
    </div>
    </section>
    <?php include_once "./footer.php" ?>
</body>

</html>
