<?php
include './../creds.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['super-id'])) {
    header("Location: ./login.php");
} else {
    $id = $_SESSION['super-id'];
    $user = "SELECT * FROM `super` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);

    if (isset($_POST['change'])) {
        $cpass = $_POST['cpass'];
        $npass1 = $_POST['npass1'];
        $npass2 = $_POST['npass2'];

        $password = $user['password'];
        if (password_verify($cpass, $password) && $npass1 === $npass2) {
            $npass2 = password_hash($npass1, PASSWORD_BCRYPT);
            if (mysqli_query($con, "UPDATE `super` SET `password` = '$npass2' WHERE `id` = '$id'")) {
                echo "<script>alert(`Password Updated!`)</script>";
            }
        } else {
            echo "<script>alert(`Invalid password!`)</script>";
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include './meta.php';
    ?>
    <title>Reset | Lions 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>

    <section class="members">
        <div class="container">
            <h2 class="text-center">Welcome
                <?php echo $user['title'] . ' ' . $user['name'] ?>
            </h2>
            <h4 class="text-center">Change Super Password</h4>
            <h5 class="text-center"><em>*This won't change your user password in member login!</em></h5>
            <form action="./reset.php" method="post" class="form-group">
                <p>Current Password*</p>
                <input type="password" name="cpass" id="cpass" required placeholder="Current Password*"
                    class="form-control">
                <p>Enter New Password*</p>
                <input type="password" name="npass1" id="npass1" required placeholder="New Password*"
                    class="form-control">
                <br>
                <p class="alert alert-danger" role="alert" id="err1">Password size must be greater than 8</p>
                <p>Confirm New Password*</p>
                <input type="password" name="npass2" id="npass2" required placeholder="Confirm New Password*"
                    class="form-control">
                <br>
                <p class="alert alert-danger" role="alert" id="err2">Passwords do not match!</p>
                <br>
                <input type="submit" value="Change Password" class="btn btn-danger" id="chng" name="change">
            </form>
            <br>
            <h5 class="text-center">Note: We do not store your passwords directly and hence won't send your passwords
                via emails or messages.<br>
                <br>
            </h5>
    </section> <?php include './footer.php' ?> </body>
<script>
const npass1 = document.querySelector('#npass1');
const npass2 = document.querySelector('#npass2');
const chng = document.querySelector('#chng');
const err1 = document.querySelector('#err1');
const err2 = document.querySelector('#err2');

err1.style.display = "none"
err2.style.display = "none"
chng.style.display = 'none'

npass1.addEventListener('keyup', () => {
    err1.style.display = npass1.value.length >= 8 ? 'none' : 'block'
    chng.style.display = npass1.value.length >= 8 && npass2.value === npass1.value ? 'block' : 'none'
})
npass2.addEventListener('keyup', () => {
    err2.style.display = npass2.value === npass1.value ? 'none' : 'block'
    chng.style.display = npass2.value === npass1.value && npass1.value.length >= 8 ? 'block' : 'none'
})
</script>

</html>