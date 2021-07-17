<?php
include './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);
if (!isset($_GET['e'])) {
    header("Location: ./event.php");
    die();
} else {
    $eId = $con->real_escape_string(strip_tags($_GET['e']));
    $event = $con->query("SELECT e.*, c.`clubName` AS `eventClubName` FROM `events` e INNER JOIN `clubs` c ON e.`clubId` = c.`clubId` WHERE e.`eventId` = '$eId'")->fetch_assoc();
    $images = $con->query("SELECT * FROM `images` WHERE `category` = 'events' AND `categoryId` = '$eId'");
    $imgCount = $images->num_rows;
    $images = $images->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['book-ticket'])) {
        $today = $date = date('Y-m-d');
        if ($today < $event['date']) {
            $firstName = $con->real_escape_string(strip_tags($_POST['firstName']));
            $lastName = $con->real_escape_string(strip_tags($_POST['lastName']));
            $email = $con->real_escape_string(strip_tags($_POST['email']));
            $phone = $con->real_escape_string(strip_tags($_POST['phone']));
            $city = $con->real_escape_string(strip_tags($_POST['city']));
            $clubName = $con->real_escape_string(strip_tags($_POST['clubName']));
            $uuid = "LCI-" . strtoupper(substr(md5(uniqid()), 0, 6));
            if ($con->query("INSERT INTO `booked-tickets`(`id`, `eventId`, `firstName`, `lastName`, `email`, `phone`, `city`, `clubName`) VALUES ('$uuid','$eId','$firstName','$lastName','$email','$phone','$city','$clubName')")) {
                $to = $email;
                $subject = "Your ticket for the event \"".$event['eventTitle']."\"";
                $message = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
</head>

<body>
    <h2>
    Dear '.$firstName.',<br>
    Thank you for registering for the event, "'.$event['eventTitle'].'".<br>
    Your booking is now confirmed.<br>
    <br>
    Ticket: <a href="https://lions3234d2.com/view-ticket.php?t='.$uuid.'" style="color: dodgerblue; text-decoration: none;">Link</a><br>
    Venue: '.$event['district'].'<br>
    <br>
    For any questions or queries contact us at <a href="mailto:support@lions3234d2.com" style="color: dodgerblue; text-decoration: none;">support@lions3234d2.com</a><br>
    We look forward to seeing you in the event.<br>
    Regards,<br>
    Lions 3234D2 team
    </h2>
</body>

</html>
                ';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: <support@lions3234d2.com>' . "\r\n";
                if (mail($to,$subject,$message,$headers)) {
                    header("Location: ./view-ticket.php?t=" . $uuid);
                }
            }
        } else {
            echo "<script>alert(`Event Expired!`); window.location.href=`./event.php`</script>";
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
    <?php include_once './meta.php' ?>
    <style>
    .slider-img {
        width: 100%;
        height: 32rem;
    }
    </style>
    <title>Book Tickets | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>
    <div class="container">


        <div id="slider" class="carousel slide" data-ride="carousel">

            <!-- Indicators -->
            <ul class="carousel-indicators">
                <?php for ($i = 0; $i < $imgCount; $i++) : ?>
                <li data-target="#slider" data-slide-to="0" <?= $i === 0 ? 'class="active"' : '' ?>></li>
                <?php endfor; ?>
            </ul>

            <!-- The slideshow -->
            <div class="carousel-inner">
                <?php for ($i = 0; $i < $imgCount; $i++) : ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                    <img src="<?= substr($images[$i]['img'], 3) ?>" alt="<?= $event['eventTitle'] ?>"
                        class="slider-img">
                </div>
                <?php endfor; ?>
            </div>

            <!-- Left and right controls -->
            <a class="carousel-control-prev" href="#slider" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#slider" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>
        </div>
        <br>
        <h2 class="text-center">Ticket for <?= ucfirst($event['eventTitle']) ?></h2>
        <form action="./book-ticket.php?e=<?= $eId ?>" method="post" class="was-validated">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-9">
                                <input type="number" id="lionId" min="0" placeholder="Enter your Lions id here.."
                                    class="form-control">
                            </div>
                            <div class="col-3">
                                <button type="button" id="lionBtn" class="btn btn-warning"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <label for="lionId"><small>Are you a District 3234D2 member? Enter your Id here.</small></label>
                        <small class="invalid-feedback">Please enter a valid Id.</small>
                        <br>
                        <div class="alert alert-danger" id="alert" style="display: none;"></div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <input type="text" name="firstName" id="firstName" placeholder="Enter First Name*" required
                            class="form-control">
                        <p class="invalid-feedback">Please enter your First Name.</p>
                    </div>

                    <div class="form-group">
                        <input type="text" name="lastName" id="lastName" placeholder="Enter Last Name*" required
                            class="form-control">
                        <p class="invalid-feedback">Please enter your Last Name.</p>
                    </div>

                    <div class="form-group">
                        <input type="text" name="email" id="email" placeholder="Enter Email*" required
                            class="form-control">
                        <p class="invalid-feedback">Please enter your Email.</p>
                    </div>

                    <div class="form-group">
                        <input type="tel" name="phone" id="phone" placeholder="Enter Phone*" required
                            class="form-control">
                        <p class="invalid-feedback">Please enter your Phone.</p>
                    </div>

                    <div class="form-group">
                        <select name="city" id="city" required class="form-control">
                            <option value="" disabled selected>Select City</option>
                            <option value="Achalpur">Achalpur</option>
                            <option value="Ahmednagar">Ahmednagar</option>
                            <option value="Akola">Akola</option>
                            <option value="Ambarnath">Ambarnath</option>
                            <option value="Amravati">Amravati</option>
                            <option value="Aurangabad">Aurangabad</option>
                            <option value="Barshi">Barshi</option>
                            <option value="Beed">Beed</option>
                            <option value="Bhivandi-Nizampur">Bhivandi-Nizampur</option>
                            <option value="Bhusawal">Bhusawal</option>
                            <option value="Chandrapur">Chandrapur</option>
                            <option value="Dhule">Dhule</option>
                            <option value="Gondia">Gondia</option>
                            <option value="Hinganghat">Hinganghat</option>
                            <option value="Ichalkaranji">Ichalkaranji</option>
                            <option value="Jalgaon">Jalgaon</option>
                            <option value="Jalna">Jalna</option>
                            <option value="Kalyan-Dombivli">Kalyan-Dombivli</option>
                            <option value="Kolhapur">Kolhapur</option>
                            <option value="Latur">Latur</option>
                            <option value="Malegaon">Malegaon</option>
                            <option value="Mira-Bhayandar">Mira-Bhayandar</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Nagpur">Nagpur</option>
                            <option value="Nanded Waghala">Nanded Waghala</option>
                            <option value="Nandurbar">Nandurbar</option>
                            <option value="Nashik">Nashik</option>
                            <option value="Navi Mumbai">Navi Mumbai</option>
                            <option value="Osmanabad">Osmanabad</option>
                            <option value="Panvel">Panvel</option>
                            <option value="Parbhani">Parbhani</option>
                            <option value="Pimpri-Chinchwad">Pimpri-Chinchwad</option>
                            <option value="Pune">Pune</option>
                            <option value="Ratnagiri">Ratnagiri</option>
                            <option value="Sangli-Miraj-Kupwad">Sangli-Miraj-Kupwad</option>
                            <option value="Satara">Satara</option>
                            <option value="Solapur">Solapur</option>
                            <option value="Thane">Thane</option>
                            <option value="Udgir">Udgir</option>
                            <option value="Ulhasnagar">Ulhasnagar</option>
                            <option value="Vasai-Virar">Vasai-Virar</option>
                            <option value="Wardha">Wardha</option>
                            <option value="Yavatmal">Yavatmal</option>
                        </select>
                        <p class="invalid-feedback">Please enter your name.</p>
                    </div>
                    <input type="hidden" name="clubName" id="clubName" value="N/A">
                    <input type="submit" value="Book" name="book-ticket" class="btn btn-primary"> &nbsp;
                    <button class="btn btn-danger" id="reset" type="button">Reset</button>
                    <br>
                    <br>
                </div>
            </div>
        </form>
    </div>
    <?php include './includes/footer.php' ?>
</body>
<script src="./url.js"></script>
<script>
$(() => {
    $('.landing').remove()
})

const lionId = document.querySelector('#lionId')
const lionBtn = document.querySelector('#lionBtn')
const reset = document.querySelector('#reset')
const alert = document.querySelector('#alert')

const firstName = document.querySelector('#firstName')
const lastName = document.querySelector('#lastName')
const city = document.querySelector('#city')
const email = document.querySelector('#email')
const phone = document.querySelector('#phone')
const clubName = document.querySelector('#clubName')

lionBtn.addEventListener('click', () => {
    alert.style.display = 'none';
    let userId = lionId.value;
    if (userId) {
        fetch(`./api.php?user-id=${userId}&get-user-info=true`).then(res => {
            res.json().then(res => {
                if (res) {
                    firstName.value = res.firstName
                    lastName.value = res.lastName
                    email.value = res.email
                    phone.value = res.phone
                    city.value = res.city
                    clubName.value = res.clubName
                } else {
                    alert.innerHTML = 'No member found!'
                    alert.style.display = 'block'
                }
            })
        })
    }
})

reset.addEventListener('click', () => {
    lionId.value = null
    firstName.value = null
    lastName.value = null
    email.value = null
    phone.value = null
    city.value = ""
    clubName.value = "N/A"
})
</script>

</html>
