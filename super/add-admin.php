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
    if (isset($_POST['add-admin'])) {
        $id = $_POST['id'];
        $id = strip_tags($id);
        $id = $con->real_escape_string($id);

        $name = $_POST['name'];
        $name = strip_tags($name);
        $name = $con->real_escape_string($name);

        $title = $_POST['title'];
        $title = strip_tags($title);
        $title = $con->real_escape_string($title);

        $password = $_POST['password'];
        $password = password_hash($password, PASSWORD_BCRYPT);

        $con->query("INSERT INTO `super` (`id`, `password`, `title`, `name`) VALUES ('$id', '$password', '$title', '$name')");
    } else if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $con->query("DELETE FROM `super` WHERE `id` = $id");
        if ($id = $_SESSION['super-id']) {
            session_destroy();
        }
        header("Location: ./add-admin.php");
    }

    $admins = $con->query("SELECT * FROM `super`");
    $admins = $admins->fetch_all(MYSQLI_ASSOC);
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
    <title>Add Super Admin | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
    <section class="members">
        <div class="container">
            <h2 class="text-center animated">Welcome
                <?php echo $super['title'] . ' ' . $super['name'] ?>
            </h2>
            <h4 class="text-center">Add Super Admin</h4>
            <div class="row">
              <div class="col-lg-6">
                <form action="./add-admin.php" method="post" class="form-group">
                    <p>Enter Lions Id of Super</p>
                    <input type="number" name="id" id="id" class="form-control" required placeholder="Enter Lions Id*">
                    <br>
                    <p>Enter Password</p>
                    <input type="password" name="password" id="password" class="form-control" required
                        placeholder="Enter Password*">
                </form>
              </div>
              <div class="col-lg-6">
                <form action="./add-admin.php" method="post" class="form-group">
                <p>Enter Super Title</p>
                <input type="text" name="title" id="title" class="form-control" required placeholder="Enter Title*">
                <br>
                <p>Enter Super Name</p>
                <input type="text" name="name" id="name" class="form-control" required placeholder="Enter Name*">

            </form>
            </div>
          </div>
          <div style="text-align:center;">
          <input type="submit" value="Add Super" class="btn btn-danger" name="add-admin">
          </div>
        </div>
      </section>
      <section class="members">
        <div class="container">
            <h2 class="text-center animated">All Super Admins</h2><br>
            <div class="row">
                  <?php foreach ($admins as $admin) : ?>
              <div class="col-lg-6">
                <table class="table table-bordered table-hover">
                              <tbody>

                                <tr>
                                  <td class="table-active"><b>ID:</b></td>
                                  <td><?= $admin['id'] ?></td>
                                </tr>
                                <tr>
                                  <td class="table-active"><b>Title:</b></td>
                                  <td><?= $admin['title'] ?></td>
                                </tr>
                                <tr>
                                  <td class="table-active"><b>Name:</b></td>
                                  <td><?= $admin['name'] ?></td>
                                </tr>
                                <tr>
                                  <td class="table-active"><b>Total Number of Employees</b></td>
                                  <td>26 to 50 People</td>
                                </tr>
                                <tr style="text-align:center;">
                                  <td><a href="./add-admin.php?id=<?= $admin['id'] ?>"  style="color: firebrick; ">Delete</a></td>
                                  <td></td>
                                </tr>

                              </tbody>
                            </table>
              </div>
                  <?php endforeach ?>
            </div>




            <br>
            </div>
    </section>
    <?php include './footer.php' ?>
</body>

</html>
