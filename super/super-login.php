<?php @session_start();
include './../creds.php';

$con = new mysqli($host, $user, $pass, $dbname);
if (isset($_POST['Login'])) {
    $id = $_POST['id'];
    $id = strip_tags($id);
    $id = $con->real_escape_string($id);

    $password = $_POST['password'];

    $result = $con->query("SELECT * FROM `super` WHERE `id` = '$id'");
    $row = $result->num_rows;

    if ($row == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['super-id'] = $id;
            header("Location: ./home.php");
        }
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
    <title>Super Login | 3234D2</title>
    <link rel="stylesheet" href="./../bootstrap-4.1.3-dist/css/bootstrap.min.css" />
    <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
    <script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
    <script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

  <style media="screen">
  .wrapper {
    background: #50a3a2;
    background: -webkit-linear-gradient(top left, #50a3a2 0%, #53e3a6 100%);
    background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%);
    position: absolute;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    padding-top: 80px;
  }
  .wrapper.form-success .container h1 {
    -webkit-transform: translateY(85px);
            transform: translateY(85px);
  }
  .container {
    max-width: 600px;
    margin: 0 auto;
    padding: 80px 0;
    height: 400px;
    text-align: center;
  }
  .container h1 {
    font-size: 40px;
    font-weight: 700;
    -webkit-transition-duration: 1s;
            transition-duration: 1s;
    -webkit-transition-timing-function: ease-in-put;
            transition-timing-function: ease-in-put;
  }

  .container p{
    font-weight: 500;
    font-size: 20px;
  }
  form {
    padding: 20px 0;
    position: relative;
    z-index: 2;
  }
  form input {
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
    outline: 0;
    border: 1px solid rgba(255, 255, 255, 0.4);
    background-color: rgba(255, 255, 255, 0.2);
    width: 424px;
    border-radius: 10px;
    padding: 10px 15px;
    margin: 0 auto 10px auto;
    display: block;
    text-align: center;
    font-size: 18px;
    color: white;
    -webkit-transition-duration: 0.25s;
            transition-duration: 0.25s;
    font-weight: 300;
  }
  form input:hover {
    background-color: rgba(255, 255, 255, 0.4);
  }
  form input:focus {
    background-color: white;
    width: 300px;
    color: #000;
    font-weight: 400;
  }
  form button {
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
    outline: 0;
    background-color: white;
    border: 0;
    padding: 10px 15px;
    color: #53e3a6;
    border-radius: 3px;
    width: 150px;
    cursor: pointer;
    font-size: 18px;
    -webkit-transition-duration: 0.25s;
            transition-duration: 0.25s;
  }
  form button:hover {
    background-color: #f5f7f9;
  }
  .bg-bubbles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
  }
  .bg-bubbles li {
    position: absolute;
    list-style: none;
    display: block;
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.15);
    bottom: -160px;
    -webkit-animation: square 25s infinite;
    animation: square 25s infinite;
    -webkit-transition-timing-function: linear;
    transition-timing-function: linear;
  }
  .bg-bubbles li:nth-child(1) {
    left: 10%;
  }
  .bg-bubbles li:nth-child(2) {
    left: 20%;
    width: 80px;
    height: 80px;
    -webkit-animation-delay: 2s;
            animation-delay: 2s;
    -webkit-animation-duration: 17s;
            animation-duration: 17s;
  }
  .bg-bubbles li:nth-child(3) {
    left: 25%;
    -webkit-animation-delay: 4s;
            animation-delay: 4s;
  }
  .bg-bubbles li:nth-child(4) {
    left: 40%;
    width: 60px;
    height: 60px;
    -webkit-animation-duration: 22s;
            animation-duration: 22s;
    background-color: rgba(255, 255, 255, 0.25);
  }
  .bg-bubbles li:nth-child(5) {
    left: 70%;
  }
  .bg-bubbles li:nth-child(6) {
    left: 80%;
    width: 120px;
    height: 120px;
    -webkit-animation-delay: 3s;
            animation-delay: 3s;
    background-color: rgba(255, 255, 255, 0.2);
  }
  .bg-bubbles li:nth-child(7) {
    left: 32%;
    width: 160px;
    height: 160px;
    -webkit-animation-delay: 7s;
            animation-delay: 7s;
  }
  .bg-bubbles li:nth-child(8) {
    left: 55%;
    width: 20px;
    height: 20px;
    -webkit-animation-delay: 15s;
            animation-delay: 15s;
    -webkit-animation-duration: 40s;
            animation-duration: 40s;
  }
  .bg-bubbles li:nth-child(9) {
    left: 25%;
    width: 10px;
    height: 10px;
    -webkit-animation-delay: 2s;
            animation-delay: 2s;
    -webkit-animation-duration: 40s;
            animation-duration: 40s;
    background-color: rgba(255, 255, 255, 0.3);
  }
  .bg-bubbles li:nth-child(10) {
    left: 90%;
    width: 160px;
    height: 160px;
    -webkit-animation-delay: 11s;
            animation-delay: 11s;
  }
  @-webkit-keyframes square {
    0% {
      -webkit-transform: translateY(0);
              transform: translateY(0);
    }
    100% {
      -webkit-transform: translateY(-700px) rotate(600deg);
              transform: translateY(-700px) rotate(600deg);
    }
  }
  @keyframes square {
    0% {
      -webkit-transform: translateY(0);
              transform: translateY(0);
    }
    100% {
      -webkit-transform: translateY(-700px) rotate(600deg);
              transform: translateY(-700px) rotate(600deg);
    }
  }
  </style>

  <div class="wrapper">
      <div class="container">
          <h1>Welcome To Super Login</h1>
          <p>Here, you can add images to the gallery, add/view/delete members and clubs and view
                  general reports. <br> Enter your Lions Id and Password to login..</p>

        <form action="./super-login.php" method="post" class="form">
            <input type="number" name="id" id="id" class="form-input" required placeholder="Enter Lions Id*">
            <input type="password" name="password" id="password" class="form-input" required placeholder="Enter Password*">
            <button type="submit" value="Super Login" class="btn btn-success" name="Login">Super Login</buttom>
        </form>
        </div>
        <ul class="bg-bubbles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js" integrity="sha512-d8F1J2kyiRowBB/8/pAWsqUl0wSEOkG5KATkVV4slfblq9VRQ6MyDZVxWl2tWd+mPhuCbpTB4M7uU/x9FlgQ9Q==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js" integrity="sha512-CEiA+78TpP9KAIPzqBvxUv8hy41jyI3f2uHi7DGp/Y/Ka973qgSdybNegWFciqh6GrN2UePx2KkflnQUbUhNIA==" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

</body>

</html>
