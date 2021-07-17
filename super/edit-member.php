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

    if (isset($_GET['edit-id'])) {
        $edit_id = $_GET['edit-id'];

        $user = $con->query("SELECT * FROM `users` WHERE `id` = '$edit_id'");
        $user = $user->fetch_assoc();
    }

    $titles = $con->query("SELECT DISTINCT `title` FROM `users`");
    $titles = $titles->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['update-profile'])) {
        $regionName = $_POST['regionName'];
        $regionName = strip_tags($regionName);
        $regionName = $con->real_escape_string($regionName);

        $zoneName = $_POST['zoneName'];
        $zoneName = strip_tags($zoneName);
        $zoneName = $con->real_escape_string($zoneName);

        $firstName = $_POST['firstName'];
        $firstName = strip_tags($firstName);
        $firstName = $con->real_escape_string($firstName);

        if (isset($_POST['middleName'])) {
            $middleName = $_POST['middleName'];
            $middleName = strip_tags($middleName);
            $middleName = $con->real_escape_string($middleName);
        } else {
            $middleName = "";
        }

        $lastName = $_POST['lastName'];
        $lastName = strip_tags($lastName);
        $lastName = $con->real_escape_string($lastName);

        $address1 = $_POST['address1'];
        $address1 = strip_tags($address1);
        $address1 = $con->real_escape_string($address1);

        $address2 = $_POST['address2'];
        $address2 = strip_tags($address2);
        $address2 = $con->real_escape_string($address2);

        $city = $_POST['city'];
        $city = strip_tags($city);
        $city = $con->real_escape_string($city);

        $state = $_POST['state'];
        $state = strip_tags($state);
        $state = $con->real_escape_string($state);

        $postalCode = $_POST['postalCode'];
        $postalCode = strip_tags($postalCode);
        $postalCode = $con->real_escape_string($postalCode);

        $email = $_POST['email'];
        $email = strip_tags($email);
        $email = $con->real_escape_string($email);

        $phone = $_POST['phone'];
        $phone = strip_tags($phone);
        $phone = $con->real_escape_string($phone);

        if (isset($_POST['spouseName'])) {
            $spouseName = $_POST['spouseName'];
            $spouseName = strip_tags($spouseName);
            $spouseName = $con->real_escape_string($spouseName);
        } else {
            $spouseName = "";
        }

        $dob = $_POST['dob'];
        $dob = strip_tags($dob);
        $dob = $con->real_escape_string($dob);

        $gender = $_POST['gender'];
        $gender = strip_tags($gender);
        $gender = $con->real_escape_string($gender);

        $occupation = $_POST['occupation'];
        $occupation = strip_tags($occupation);
        $occupation = $con->real_escape_string($occupation);

        $title = $_POST['title'];
        $title = strip_tags($title);
        $title = $con->real_escape_string($title);

        $id = $_POST['id'];

        $sql = "UPDATE `users` SET `regionName`='$regionName',`zoneName`='$zoneName',`title`='$title',`firstName`='$firstName',`middleName`='$middleName',`lastName`='$lastName',`address1`='$address1',`address2`='$address2',`city`='$city',`state`='$state',`postalCode`='$postalCode',`email`='$email',`phone`='$phone',`spouseName`='$spouseName',`dob`='$dob',`gender`='$gender',`occupation`='$occupation',`verified`='1' WHERE `id` = '$id'";
        if ($con->query($sql)) {
            header("Location: ./add-member.php");
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
    <title>Edit Member | 3234D2</title>
</head>

<body>
    <?php include './header.php' ?>
    <section class="members">
        <div class="container">
            <h2 class="text-center">Welcome
                <?php echo $super['title'] . ' ' . $super['name'] ?>
            </h2>
            <h4 class="text-center">Edit Member</h4>
            <form action="./edit-member.php" method="POST" class="form-group">
                <input type="number" name="id" id="" hidden value="<?= $edit_id ?>">
                <p>Enter Region Name*</p>
                <input type="text" name="regionName" id="" class="form-control" required
                    placeholder="Enter Region Name*" value="<?php echo $user['regionName'] ?>">

                <p>Enter Zone Name*</p>
                <input type="text" name="zoneName" id="" class="form-control" placeholder="Enter Zone Name*" required
                    value="<?php echo $user['zoneName'] ?>">

                <p>Enter Title*</p>
                <select name="title" id="" required class="form-control">
                    <option value="" disabled selected>Select Title</option>
                    <?php foreach ($titles as $pos) : ?>
                    <option value="<?= $pos['title'] ?>" <?= $user['title'] === $pos['title'] ? 'selected' : '' ?>>
                        <?= $pos['title'] ?></option>
                    <?php endforeach ?>
                </select>

                <p>Enter Name*</p>
                <input type="text" name="firstName" id="" class="form-control" placeholder="Enter Name*" required
                    value="<?php echo $user['firstName'] ?>">


                <p>Enter Middle Name</p>
                <input type="text" name="middleName" id="" class="form-control" placeholder="Enter Middle Name"
                    value="<?php $user['middleName'] ?>">

                <p>Enter Last Name*</p>
                <input type="text" name="lastName" id="" class="form-control" placeholder="Enter Last Name*" required
                    value="<?php echo $user['lastName'] ?>">

                <p>Enter Address Line 1*</p>
                <input type="text" name="address1" id="" class="form-control" placeholder="Enter Address Line 1*"
                    required value="<?php echo $user['address1'] ?>">

                <p>Enter Address Line 2*</p>
                <input type="text" name="address2" id="" class="form-control" placeholder="Enter Address Line 2*"
                    required value="<?php echo $user['address2'] ?>">

                <p>Enter City*</p>
                <input type="text" name="city" id="" class="form-control" placeholder="Enter City*" required
                    value="<?php echo $user['city'] ?>">

                <p>Enter State*</p>
                <input type="text" name="state" id="" class="form-control" placeholder="Enter State*" required
                    value="<?php echo $user['state'] ?>">

                <p>Enter Postal Code*</p>
                <input type="number" name="postalCode" id="" class="form-control" placeholder="Enter Postal Code*"
                    required value="<?php echo $user['postalCode'] ?>">

                <p>Enter Email* (Required for resetting password!)</p>
                <input type="email" name="email" id="" class="form-control" placeholder="Enter Email*" required
                    value="<?php echo $user['email'] ?>">

                <p>Enter Phone Number*</p>
                <input type="text" name="phone" id="" class="form-control" placeholder="Enter Phone*" required
                    value="<?php echo $user['phone'] ?>" pattern="[6-9]{1}[0-9]{9}">

                <p>Enter Spouse Name</p>
                <input type="text" name="spouseName" id="" class="form-control" placeholder="Enter Spouse Name*"
                    value="<?php echo $user['spouseName'] ?>">

                <p>Enter D.O.B.*</p>
                <input type="date" name="dob" id="" class="form-control" placeholder="Enter DOB*" required
                    value="<?php echo $user['dob'] ?>">

                <p>Enter Gender*</p>
                <select name="gender" id="" class="form-control" required>
                    <option value="" disabled>Select Gender</option>
                    <option value="Female" <?php if ($user['gender'] == 'Female') {
                                                echo 'selected';
                                            } ?>>Female</option>
                    <option value="Male" <?php if ($user['gender'] == 'Male') {
                                                echo 'selected';
                                            } ?>>Male</option>
                    <option value="Other" <?php if ($user['gender'] == 'Other') {
                                                echo 'selected';
                                            } ?>>Other</option>
                </select>

                <p>Enter Occupation*</p>
                <input type="text" name="occupation" id="" class="form-control" placeholder="Enter Occupation*" required
                    value="<?php echo $user['occupation'] ?>">

                <br>
                <input type="submit" value="Submit" name="update-profile" class="btn btn-success">
            </form>
        </div>
    </section>
    <?php include './footer.php' ?>
</body>

</html>