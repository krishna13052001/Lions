<?php
include './../creds.php';
session_start();
$con = mysqli_connect($host, $user, $pass, $dbname);
if (!isset($_SESSION['id'])) {
    header("Location: ./../login.php");
}
else {
    $id = $_SESSION['id'];
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);
    $member = $user['title'];
    
    $clubId = $user['clubId'];

    
    $reportedActivities = $con->query("SELECT DISTINCT a.*, t.`placeholder` FROM `activities` a INNER JOIN `activitytype` t ON (a.`activityType` = t.`type` AND a.`activitySubType` = t.`sub-type` AND a.`activityCategory` = t.`category`) WHERE `clubId` = '$clubId' AND `status`= 1 ORDER BY `activityId` DESC")->fetch_all(MYSQLI_ASSOC);
    

    if ($user['verified'] != 1) {
        header("Location: ./member-login.php");
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <?php
    include './meta.php';
    ?>
    <title>Treasurer Home | 3234D2</title> <!-- General CSS Files -->
    <link rel="stylesheet" href="../assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel='shortcut icon' type='image/x-icon' href='../assets/img/favicon.ico' />
    <link rel="stylesheet" href="./../bootstrap-4.1.3-dist/css/bootstrap.min.css" />
    <!-- <script src="./../bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<script src="./../bootstrap-4.1.3-dist/js/multislider.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./style.css" /> -->

</head>


<?php //include './header.php' 
?>

<body>

<script>

/*Lightbox JS: Fullsize Image Overlays by Lokesh Dhakar – http://www.huddletogether.com

For more information on this script, visit: http://huddletogether.com/projects/lightbox/ */

var loadingImage="http://static.caspio.com/images/xref/loading.gif";

var closeButton="http://static.caspio.com/images/xref/close.gif";

function getPageScroll(){var yScroll;if(self.pageYOffset){yScroll=self.pageYOffset}else{if(document.documentElement&&document.documentElement.scrollTop){yScroll=document.documentElement.scrollTop}else{if(document.body){yScroll=document.body.scrollTop}}}arrayPageScroll=new Array("",yScroll);return arrayPageScroll}function getPageSize(){var xScroll,yScroll;if(window.innerHeight&&window.scrollMaxY){xScroll=document.body.scrollWidth;yScroll=window.innerHeight+window.scrollMaxY}else{if(document.body.scrollHeight>document.body.offsetHeight){xScroll=document.body.scrollWidth;yScroll=document.body.scrollHeight}else{xScroll=document.body.offsetWidth;yScroll=document.body.offsetHeight}}var windowWidth,windowHeight;if(self.innerHeight){windowWidth=self.innerWidth;windowHeight=self.innerHeight}else{if(document.documentElement&&document.documentElement.clientHeight){windowWidth=document.documentElement.clientWidth;windowHeight=document.documentElement.clientHeight}else{if(document.body){windowWidth=document.body.clientWidth;windowHeight=document.body.clientHeight}}}if(yScroll<windowHeight){pageHeight=windowHeight}else{pageHeight=yScroll}if(xScroll<windowWidth){pageWidth=windowWidth}else{pageWidth=xScroll}arrayPageSize=new Array(pageWidth,pageHeight,windowWidth,windowHeight);return arrayPageSize}function pause(numberMillis){var now=new Date();var exitTime=now.getTime()+numberMillis;while(true){now=new Date();if(now.getTime()>exitTime){return}}}function getKey(e){if(e==null){keycode=event.keyCode}else{keycode=e.which}key=String.fromCharCode(keycode).toLowerCase();if(key=="x"){hideLightbox()}}function listenKey(){document.onkeypress=getKey}function showLightbox(objLink){var objOverlay=document.getElementById("overlay");var objLightbox=document.getElementById("lightbox");var objCaption=document.getElementById("lightboxCaption");var objImage=document.getElementById("lightboxImage");var objLoadingImage=document.getElementById("loadingImage");var objLightboxDetails=document.getElementById("lightboxDetails");var arrayPageSize=getPageSize();var arrayPageScroll=getPageScroll();if(objLoadingImage){objLoadingImage.style.top=(arrayPageScroll[1]+((arrayPageSize[3]-35-objLoadingImage.height)/2)+"px");objLoadingImage.style.left=(((arrayPageSize[0]-20-objLoadingImage.width)/2)+"px");objLoadingImage.style.display="block"}objOverlay.style.height=(arrayPageSize[1]+"px");objOverlay.style.display="block";imgPreload=new Image();imgPreload.onload=function(){objImage.src=objLink.href;var lightboxTop=arrayPageScroll[1]+((arrayPageSize[3]-35-imgPreload.height)/2);var lightboxLeft=((arrayPageSize[0]-20-imgPreload.width)/2);objLightbox.style.top=(lightboxTop<0)?"0px":lightboxTop+"px";objLightbox.style.left=(lightboxLeft<0)?"0px":lightboxLeft+"px";objLightboxDetails.style.width=imgPreload.width+"px";if(objLink.getAttribute("title")){objCaption.style.display="block";objCaption.innerHTML=objLink.getAttribute("title")}else{objCaption.style.display="none"}if(navigator.appVersion.indexOf("MSIE")!=-1){pause(250)}if(objLoadingImage){objLoadingImage.style.display="none"}selects=document.getElementsByTagName("select");for(i=0;i!=selects.length;i++){selects[i].style.visibility="hidden"}objLightbox.style.display="block";arrayPageSize=getPageSize();objOverlay.style.height=(arrayPageSize[1]+"px");listenKey();return false};imgPreload.src=objLink.href}function hideLightbox(){objOverlay=document.getElementById("overlay");objLightbox=document.getElementById("lightbox");objOverlay.style.display="none";objLightbox.style.display="none";selects=document.getElementsByTagName("select");for(i=0;i!=selects.length;i++){selects[i].style.visibility="visible"}document.onkeypress=""}function initLightbox(){if(!document.getElementsByTagName){return}var anchors=document.getElementsByTagName("a");for(var i=0;i<anchors.length;i++){var anchor=anchors[i];if(anchor.getAttribute("href")&&(anchor.getAttribute("rel")=="lightbox")){anchor.onclick=function(){showLightbox(this);return false}}}var objBody=document.getElementsByTagName("body").item(0);var objOverlay=document.createElement("div");objOverlay.setAttribute("id","overlay");objOverlay.onclick=function(){hideLightbox();return false};objOverlay.style.display="none";objOverlay.style.position="absolute";objOverlay.style.top="0";objOverlay.style.left="0";objOverlay.style.zIndex="90";objOverlay.style.width="100%";objBody.insertBefore(objOverlay,objBody.firstChild);var arrayPageSize=getPageSize();var arrayPageScroll=getPageScroll();var imgPreloader=new Image();imgPreloader.onload=function(){var objLoadingImageLink=document.createElement("a");objLoadingImageLink.setAttribute("href","#");objLoadingImageLink.onclick=function(){hideLightbox();return false};objOverlay.appendChild(objLoadingImageLink);var objLoadingImage=document.createElement("img");objLoadingImage.src=loadingImage;objLoadingImage.setAttribute("id","loadingImage");objLoadingImage.style.position="absolute";objLoadingImage.style.zIndex="150";objLoadingImageLink.appendChild(objLoadingImage);imgPreloader.onload=function(){};return false};imgPreloader.src=loadingImage;var objLightbox=document.createElement("div");objLightbox.setAttribute("id","lightbox");objLightbox.style.display="none";objLightbox.style.position="absolute";objLightbox.style.zIndex="100";objBody.insertBefore(objLightbox,objOverlay.nextSibling);var objLink=document.createElement("a");objLink.setAttribute("href","#");objLink.setAttribute("title","Click to close");objLink.onclick=function(){hideLightbox();return false};objLightbox.appendChild(objLink);var imgPreloadCloseButton=new Image();imgPreloadCloseButton.onload=function(){var objCloseButton=document.createElement("img");objCloseButton.src=closeButton;objCloseButton.setAttribute("id","closeButton");objCloseButton.style.position="absolute";objCloseButton.style.zIndex="200";objLink.appendChild(objCloseButton);return false};imgPreloadCloseButton.src=closeButton;var objImage=document.createElement("img");objImage.setAttribute("id","lightboxImage");objLink.appendChild(objImage);var objLightboxDetails=document.createElement("div");objLightboxDetails.setAttribute("id","lightboxDetails");objLightbox.appendChild(objLightboxDetails);var objCaption=document.createElement("div");objCaption.setAttribute("id","lightboxCaption");objCaption.style.display="none";objLightboxDetails.appendChild(objCaption);var objKeyboardMsg=document.createElement("div");objKeyboardMsg.setAttribute("id","keyboardMsg");objKeyboardMsg.innerHTML='press <a href="#" onclick="hideLightbox(); return false;"><kbd>x</kbd></a> to close';objLightboxDetails.appendChild(objKeyboardMsg)}

document.addEventListener('DataPageReady', function (func) {
initLightbox();
});

</script>

<style>

#lightbox{background-color:#eee;padding:10px;border-bottom:2px solid #666;border-right:2px solid #666;}#lightboxDetails{font-size:.8em;padding-top:.4em;}#lightboxCaption{float:left;}#keyboardMsg{float:right;}#closeButton{top:5px;right:5px;}#lightbox img{border:none;clear:both;}#overlay img{border:none;}#overlay{background-image:url(http://static.caspio.com/images/xref/overlay.png);}* html #overlay{background-color:#333;background-color:transparent;background-image:url(http://b3.caspio.com/RMA_ref/blank.gif);filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=" http://static.caspio.com/images/xref/overlay.png",sizingMethod="scale");}

</style>
        
      </div>
    </div>
  </div>


    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
                        <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i>
                            </a></li>
                        
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">

                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image" src="../assets/img/user.png" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title"><?php echo $user['title'] . ' <br><em>"' . $user['firstName'] . ' ' . $user['middleName'] . ' ' . $user['lastName'] . '"</em>' ?></div>
                            <hr>

                            <a href="./member-login.php" class="dropdown-item has-icon"> <i class="far
										fa-user"></i>Update Profile</a>

                            <a href="./reset.php" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                                Change Password
                            </a>

                            <div class="dropdown-divider"></div>
                            <a href="./logout.php" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- side bar -->
            <?php 
              $msg = "";
              if($member === "lion member") { 
                $msg = "Lion Member";
                include './member-sidebar.php';
              } elseif($member === "Club Treasurer") {
                $msg ="Club Treasurer";
                include './clubtreasurer-sidebar.php';
              } elseif($member === "Club Secretary") {
                $msg ="Club Secretary";
                include './clubsecretary-sidebar.php';
              } else {
                $msg ="Club President";
                include './clubpresident-sidebar.php';
              }
            ?>

            <div class="main-content">
                <section class="section">

                <div class="card">
                  <div class="card-header">
                    <h4>Approved Activities</h4>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-striped table-md" id="pending-table">
                        <tr>
                        <th>Title</th>
                                      <th>Officers</th>
                                      <th>City</th>
                                      <th>Amount</th>
                                      <th>Lion Hours</th>
                                      <th>Type</th>
                                      <th>Sub Type</th>
                                      <th>Category</th>
                                      <th>People Served</th>
                                      <th>Date</th>
                                      <th>Bill</th>
                        </tr>

                        <?php foreach ($reportedActivities as $row) : ?>
                        <tr>
                        <td><?= $row['activityTitle'] ?></td>
                                          <td><?= $row['cabinetOfficers'] ?></td>
                                          <td><?= $row['city'] ?></td>
                                          <td><?= $row['amount'] ?></td>
                                          <td><?= $row['lionHours'] ?></td>
                                          <td><?= $row['activityType'] ?></td>
                                          <td><?= $row['activitySubType'] ?></td>
                                          <td><?= $row['activityCategory'] ?></td>
                                          <td><?= $row['placeholder'] ?>:<?= $row['peopleServed'] ?></td>
                                          <td><?= $row['date'] ?></td>
                                          <?php
                                          if($row['bills'] == ""){
                                            echo "<td>Not Available</td>";
                                          }
                                          else{

                                            echo "<td><a rel='lightbox' href='bills/".$row['bills']."'>
                                            <img border='0'style='display:none' height='50' src='".$row['bills']."'/>View Bill</a></td>";
                                          }
                                            ?>

                        
                          <!-- <?php 
                         echo "<a class='btn btn-primary' href='manage-reporting.php?activityId=".$row['activityId']."'>Edit</a>&nbsp";
                          ?>

                          <?php
                        echo "<a class='btn btn-warning' id='reject' data-toggle='modal' data-target='#remarks' href='#'>Reject</a>&nbsp;";
                          ?>
                          
                        <?php
                         echo "<a class='btn btn-success' id='approve' href='?type=status&operation=approve&id=".$row['activityId']."'>Approve</a>&nbsp;";
                          ?>
                        </td> -->

                        </tr>
                        <?php endforeach; ?>
                        
                        
                        
                      </table>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    <nav class="d-inline-block">
                      <ul class="pagination mb-0">
                        <li class="page-item disabled">
                          <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1 <span
                              class="sr-only">(current)</span></a></li>
                        <li class="page-item">
                          <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                          <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                      </ul>
                    </nav>
                  </div>
                </div>

              </section>
            </div>


            <script>
            
            
            
            
            </script>
      

    <script src="../assets/js/app.min.js"></script>
    <!-- JS Libraies -->
    <script src="../assets/bundles/apexcharts/apexcharts.min.js"></script>
    <!-- Page Specific JS File -->
    <script src="../assets/js/page/index.js"></script>
    <!-- Template JS File -->
    <script src="../assets/js/scripts.js"></script>
    <!-- Custom JS File -->
    <script src="../assets/js/custom.js"></script>

</body>

</html>