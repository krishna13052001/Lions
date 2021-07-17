<?php
include './creds.php';
$con = mysqli_connect($host, $user, $pass, $dbname);

if (isset($_POST['contact-us'])) {
    $status = 'error';
    $response = array();

    $query = $_POST['query'];
    $query = strip_tags($query);
    $query = mysqli_real_escape_string($con, $query);

    $name = $_POST['name'];
    $name = strip_tags($name);
    $name = mysqli_real_escape_string($con, $name);

    $email = $_POST['email'];
    $email = strip_tags($email);
    $email = mysqli_real_escape_string($con, $email);

    $number = $_POST['number'];
    $number = strip_tags($number);
    $number = mysqli_real_escape_string($con, $number);

    $message = $_POST['message'];
    $message = strip_tags($message);
    $message = mysqli_real_escape_string($con, $message);

    $sql = "INSERT INTO `contact`(`query`, `name`, `number`, `email`, `message`) VALUES ('$query', '$name', '$number', '$email', '$message')";
    if (mysqli_query($con, $sql)) {
        $status = "success";
    }
    $response += array('status' => $status);
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
    <title>Contact | 3234D2</title>
</head>

<body>
    <?php include './includes/header.php' ?>



    <section id="intro" class="d-flex align-items-center"  style="background-image:url(img/indexbg7.jpg)">
        <div class="container">
          <h1 class="animated">Contact Us
          </h1>
        </div>
      </section>




      <style>
          .contact .info {
        padding: 30px;
        background: #fff;
        width: 100%;
        border: 2px solid #e9ecee;
        border-radius: 4px;
      }

      .contact select{
        padding-left: 2px;
          margin-top: 33px;
      }

      .nice {
        font-size: 12px;
        height: 36px;
        line-height: 34px;
      }
      .contact .info i {
        font-size: 20px;
        color: #214294;
        float: left;
        width: 44px;
        height: 44px;
        background: #f2f4f5;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50px;
        transition: all 0.3s ease-in-out;
      }

      .contact .info h4 {
        padding: 0 0 0 60px;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #364146;
      }

      .contact .info p {
        padding: 0 0 10px 60px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #627680;
      }

      .contact .info .social-links {
        padding-left: 60px;
      }

      .contact .info .social-links a {
        font-size: 18px;
        display: inline-block;
        background: #333;
        color: #fff;
        line-height: 1;
        padding: 8px 0;
        border-radius: 50%;
        text-align: center;
        width: 36px;
        height: 36px;
        transition: 0.3s;
        margin-right: 10px;
      }

      .contact .info .social-links a:hover {
        background: #214294;
        color: #fff;
      }

      .contact .info .email:hover i, .contact .info .address:hover i, .contact .info .phone:hover i {
        background: #214294;
        color: #fff;
      }

      .contact .php-email-form {
        width: 100%;
        padding: 30px;
        background: #fff;
        border: 2px solid #e9ecee;
        border-radius: 4px;
      }

      .contact .php-email-form .form-group {
        padding-bottom: 8px;
      }

      .contact .php-email-form .validate {
        display: none;
        color: red;
        margin: 0 0 15px 0;
        font-weight: 400;
        font-size: 13px;
      }

      .contact .php-email-form .error-message {
        display: none;
        color: #fff;
        background: #ed3c0d;
        text-align: left;
        padding: 15px;
        font-weight: 600;
      }

      .contact .php-email-form .error-message br + br {
        margin-top: 25px;
      }

      .contact .php-email-form .sent-message {
        display: none;
        color: #fff;
        background: #18d26e;
        text-align: center;
        padding: 15px;
        font-weight: 600;
      }

      .contact .php-email-form .loading {
        display: none;
        background: #fff;
        text-align: center;
        padding: 15px;
      }

      .contact .php-email-form .loading:before {
        content: "";
        display: inline-block;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        margin: 0 10px -6px 0;
        border: 3px solid #18d26e;
        border-top-color: #eee;
        -webkit-animation: animate-loading 1s linear infinite;
        animation: animate-loading 1s linear infinite;
      }

      .contact .php-email-form input, .contact .php-email-form textarea {
        border-radius: 0;
        box-shadow: none;
        font-size: 14px;
      }

      .contact .php-email-form input {
        height: 44px;
      }

      .contact .php-email-form textarea {
        padding: 10px 12px;
      }

      .contact .php-email-form button[type="submit"] {
        background: #214294;
        border: 0;
        padding: 10px 24px;
        color: #fff;
        transition: 0.4s;
        border-radius: 4px;
      }

      .contact .php-email-form button[type="submit"]:hover {
        background: #1eb4ff;
      }

      @-webkit-keyframes animate-loading {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }

      @keyframes animate-loading {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }

      </style>

        <main id="main">


          <!-- ======= Contact Section ======= -->
          <section id="contact" class="contact section-bg">
            <div class="container">
              <br><br>
              <div class="row">

                <div class="col-lg-5 d-flex align-items-stretch" data-aos="fade-right">
                  <div class="info">
                    <div class="address">
                      <i class="icofont-google-map"></i>
                      <h4>Location:</h4>
                      <p>Kamla Neharu Hospital, Mangalwar Peth, Mangalwar Peth, Pune, Maharashtra 411018</p>
                    </div>

                    <div class="email">
                    <a target="_blank" href="mailto:support@lions3234d2.com">  <i class="icofont-envelope"></i>
                      <h4>Email:</h4>
                      <p>support@lions3234d2.com</p></a>
                    </div>

                    <div class="phone">
                      <a target="_blank"  href="tel:+918882655840"><i class="icofont-phone"></i>
                      <h4>Call:</h4>
                      <p>+91 8882655840</p></a>
                    </div>

                    <iframe id="maps-api"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3783.1381080241113!2d73.85957531436873!3d18.522660073945854!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bc2c06725555555%3A0x57d0b26361d0f7f2!2sLion%60s%20Club%20Of%20Poona!5e0!3m2!1sen!2sin!4v1583649280084!5m2!1sen!2sin"
                        frameborder="0" allowfullscreen style="width: 100%; height: 50%;"></iframe>
                                        </div>

                </div>

                <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch" data-aos="fade-left">
                  <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="name">Your Name*</label>
                        <input type="text" name="name" class="form-control" id="name" data-rule="minlen:4" data-msg="Please enter at least 4 chars"required />
                        <div class="validate"></div>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="name">Your Email*</label>
                        <input type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="Please enter a valid email" required/>
                        <div class="validate"></div>
                      </div>

                    <div class="form-group col-md-6">
                      <label for="name">Phone No.*</label>
                      <input type="number" class="form-control" name="phone" id="phone" data-rule="minlen:4" data-msg="Please enter phone no"   pattern="[6-9]{1}[0-9]{9}" required/>
                      <div class="validate"></div>
                    </div>
                    <div class="form-group col-md-6">

                      <select name="query" id="query" class="form-control">
                          <option selected disabled value="">
                              Select The Query
                          </option>
                          <option value="membership Registration">Membership Registration </option>
                          <option value="donations"> Donations </option>
                          <option value="event Registration"> Event Registrations </option>
                          <option value="other"> Other </option>
                      </select>
                      <!-- <div class="validate"></div> -->
                    </div>

                    <div class="form-group col-md-12">
                      <label for="name">Message*</label>
                      <textarea class="form-control" name="message" rows="10" data-rule="required" data-msg="Please write something for us" required></textarea>
                      <div class="validate"></div>
                    </div>
                    </div>
                    <div class="text-center"><button type="submit">Send Message</button></div>
                  </form>
                </div>

              </div>

            </div>
            <br><br>
          </section><!-- End Contact Section -->

        </main><!-- End #main -->

    <?php include './includes/footer.php' ?>
</body>

</html>
