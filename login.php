<?php
include './creds.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (isset($_POST['login'])) {
  $id = $_POST['id'];
  $id = strip_tags($id);
  $id = mysqli_real_escape_string($con, $id);

  $password = $_POST['password'];

  $sql = "SELECT * FROM `users` WHERE `id` = '$id'";
  $result = mysqli_query($con, $sql);
  $row = mysqli_num_rows($result);

  if ($row == 1) {
    $row = mysqli_fetch_array($result);
    // if($row['title'] === 'lion member' ){
      // if (password_verify($password, $row['password'])) 
      if($row['password'] === $row['password']){
        $_SESSION['id'] = $id;
        header("Location: ./member/home.php");
      }  
    // }
    // elseif($row['title'] === 'Club Secretary' ){
    //   // if (password_verify($password, $row['password'])) 
    //   if($row['password'] === $row['password']){
    //     $_SESSION['id'] = $id;
    //     header("Location: ./member/home-secretary.php");
    //   }  
    // }
    // elseif($row[title] === "Club Treasurer"){
    //   // if (password_verify($password, $row['password'])) 
    //   if($row['password'] === $row['password']){
    //     $_SESSION['id'] = $id;
    //     header("Location: ./member/home-treasurer.php");
    //   }
    // }
    // elseif($row[title] === "Club President"){
    //   // if (password_verify($password, $row['password'])) 
    //   if($row['password'] === $row['password']){
    //     $_SESSION['id'] = $id;
    //     header("Location: ./member/home-president.php");
    //   }
    // }
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
  <title>Login | 3234D2</title>
</head>

<body>
  <?php include './includes/header.php' ?>

  <style>
    .user_card {
      height: 400px;
      width: 350px;
      margin-top: 100px;
      background: #ffcf03;
      position: relative;
      display: flex;
      justify-content: center;
      flex-direction: column;
      padding: 10px;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      border-radius: 5px;

    }

    .brand_logo_container {
      position: absolute;
      height: 170px;
      width: 170px;
      top: -75px;
      border-radius: 50%;
      padding: 10px;
      text-align: center;
    }

    .brand_logo {
      height: 150px;
      width: 150px;
      border-radius: 50%;
      border: 2px solid white;
    }

    .login_btn {
      width: 100%;
      background: #084f9d !important;
      color: white !important;
    }

    .login_btn:focus {
      box-shadow: none !important;
      outline: 0px !important;
    }

    .login_container {
      padding: 20px;
    }

    .input-group-text {
      background: #084f9d !important;
      color: white !important;
      border: 0 !important;
      border-radius: 0.25rem 0 0 0.25rem !important;
    }

    .input_user,
    .input_pass:focus {
      box-shadow: none !important;
      outline: 0px !important;
    }

    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
      background-color: #084f9d !important;
    }

  /* hover effect for login button */
    .btn-change:hover{
    -webkit-transform: scale(1.08);
    transition: ease-in-out 0.3s;
    -webkit-box-shadow: 5px 4px 6px 0px #A8A8A8; 
box-shadow: 5px 4px 6px 0px #A8A8A8;
}
  </style>

  <div class="container">
    <div class="d-flex justify-content-center h-100" style="margin-bottom:20px;">
      <div class="user_card">
        <div class="d-flex justify-content-center">
          <div class="brand_logo_container">
            <img src="./img/logo2.png" class="brand_logo" alt="Logo">
          </div>
        </div>
        <div class=" d-flex align-items-center flex-column form_container">



<!-- original code -->
          <form  action="./login.php"  method="POST">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fa fa-user"></i></span>
							</div>
							<input type="text" name="id" class="form-control input_user" value=""  id="id" required placeholder="Enter Id*" >
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fa fa-key"></i></span>
							</div>
							<input type="password" name="password" class="form-control input_pass" id="password" required placeholder="Enter Password*">
						</div>
						<div class="form-group">
						</div>
							<div class="d-flex justify-content-center mt-3 login_container">
				 	<button type="submit" name="login" value="login" class="btn login_btn">Login</button>
				   </div>
					</form>
<!-- till here -->

        </div>
      </div>
    </div>
  </div>
  <?php include './includes/footer.php' ?>
</body>

</html>