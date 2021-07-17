<?php
/* 
****************************************
******************************
******************

APIs For Lions Club Pune Website by Ritam.

******************
******************************
****************************************
*/

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

date_default_timezone_set('Asia/Kolkata');

include 'creds.php';
include 'upload-image.php';

$con = mysqli_connect($host, $user, $pass, $dbname);


//Add Event API
if (isset($_POST['add-event'])) {

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

    $chiefGuest = $_POST['chiefGuest'];
    $chiefGuest = strip_tags($chiefGuest);
    $chiefGuest = mysqli_real_escape_string($con, $chiefGuest);

    if (isset($_POST['date']) && $_POST['date'] != null) {
        $date = $_POST['date'];
        $date = strip_tags($date);
        $date = mysqli_real_escape_string($con, $date);
    } else {
        $date = date('Y-m-d H:i:s');
    }

    if (isset($_POST['venue']) && $_POST['venue'] != null) {
        $venue = $_POST['venue'];
        $venue = strip_tags($venue);
        $venue = mysqli_real_escape_string($con, $venue);
    } else {
        $venue = 'online';
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

    $authorId = $_POST['authorId'];
    $authorId = strip_tags($authorId);
    $authorId = mysqli_real_escape_string($con, $authorId);

    $clubId = $_POST['clubId'];
    $clubId = strip_tags($clubId);
    $clubId = mysqli_real_escape_string($con, $clubId);

    $addEventSql = "INSERT INTO `events`(`eventTitle`, `amount`, `chiefGuest`, `date`, `description`, `eventType`, `authorId`, `clubId`, `district`) VALUES ('$eventTitle', '$amount', '$chiefGuest', '$date', '$description', '$eventType', '$authorId', '$clubId', '$venue')";
    $getEventIdSql = "SELECT `eventId` FROM `events` WHERE `eventTitle` = '$eventTitle' AND `date` = '$date' AND `authorId` = '$authorId' AND `clubId` = '$clubId' AND `eventType` = '$eventType' ";

    if (mysqli_query($con, $addEventSql)) {
        $eventId = mysqli_query($con, $getEventIdSql);
        $eventId = mysqli_fetch_array($eventId);
        $eventId = $eventId['eventId'];

        foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
            $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
            $imageUploadPath = './' . $uploadPath . $file_name;

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allowTypes)) {
                $tname = $_FILES["image"]["tmp_name"][$key];
                move_uploaded_file($tname, $imageUploadPath);
                $addImageSql = "INSERT INTO `images`(`img`, `category`, `categoryId`) VALUES ('$imageUploadPath', 'events', '$eventId')";
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

    echo json_encode($response);
}


//Add Activity
else if (isset($_POST['add-activity'])) {

    $status = 'error';
    $message = '';
    $error = array();
    $response = array();

    $amount = $_POST['amount'];
    $amount = strip_tags($amount);
    $amount = mysqli_real_escape_string($con, $amount);

    $activityTitle = $_POST['activityTitle'];
    $activityTitle = strip_tags($activityTitle);
    $activityTitle = mysqli_real_escape_string($con, $activityTitle);

    $city = $_POST['city'];
    $city = strip_tags($city);
    $city = mysqli_real_escape_string($con, $city);

    if (isset($_POST['date']) && $_POST['date'] != null) {
        $date = $_POST['date'];
        $date = strip_tags($date);
        $date = mysqli_real_escape_string($con, $date);
    } else {
        $date = date('Y-m-d H:i:s');
        $date = mysqli_real_escape_string($con, $date);
    }

    $cabinetOfficers = $_POST['cabinetOfficers'];
    $cabinetOfficers = strip_tags($cabinetOfficers);
    $cabinetOfficers = mysqli_real_escape_string($con, $cabinetOfficers);

    $description = $_POST['description'];
    $description = strip_tags($description);
    $description = mysqli_real_escape_string($con, $description);

    $lionHours = $_POST['lionHours'];
    $lionHours = strip_tags($lionHours);
    $lionHours = mysqli_real_escape_string($con, $lionHours);

    $mediaCoverage = $_POST['mediaCoverage'];
    $mediaCoverage = strip_tags($mediaCoverage);
    $mediaCoverage = mysqli_real_escape_string($con, $mediaCoverage);

    $peopleServed = $_POST['peopleServed'];
    $peopleServed = strip_tags($peopleServed);
    $peopleServed = mysqli_real_escape_string($con, $peopleServed);

    $activityType = $_POST['activityType'];
    $activityType = strip_tags($activityType);
    $activityType = mysqli_real_escape_string($con, $activityType);

    $activitySubType = $_POST['activitySubType'];
    $activitySubType = strip_tags($activitySubType);
    $activitySubType = mysqli_real_escape_string($con, $activitySubType);

    $activityCategory = $_POST['activityCategory'];
    $activityCategory = strip_tags($activityCategory);
    $activityCategory = mysqli_real_escape_string($con, $activityCategory);

    $place = $_POST['place'];
    $place = strip_tags($place);
    $place = mysqli_real_escape_string($con, $place);

    $authorId = $_POST['authorId'];
    $authorId = strip_tags($authorId);
    $authorId = mysqli_real_escape_string($con, $authorId);

    $clubId = $_POST['clubId'];
    $clubId = strip_tags($clubId);
    $clubId = mysqli_real_escape_string($con, $clubId);


    $addActivitySql = "INSERT INTO `activities`(`amount`, `activityTitle`, `city`, `date`, `cabinetOfficers`, `description`, `lionHours`, `mediaCoverage`, `peopleServed`, `activityType`, `place`, `authorId`, `clubId`, `activitySubType`, `activityCategory`) VALUES ('$amount','$activityTitle','$city','$date','$cabinetOfficers','$description','$lionHours','$mediaCoverage','$peopleServed','$activityType','$place','$authorId','$clubId', '$activitySubType', '$activityCategory')";
    $getActivitySql = "SELECT `activityId` FROM `activities` WHERE `activityTitle` = '$activityTitle' AND `date` = '$date' AND `authorId` = '$authorId' AND `clubId` = '$clubId'";

    if (mysqli_query($con, $addActivitySql)) {
        $activityId = mysqli_query($con, $getActivitySql);
        $activityId = mysqli_fetch_array($activityId);
        $activityId = $activityId['activityId'];

        $points = $con->query("SELECT * FROM `activitytype` WHERE `type` = '$activityType' AND `sub-type` = '$activitySubType' AND `category` = '$activityCategory'");
        $points = $points->fetch_assoc();
        $stars = (int)(($peopleServed / $points['beneficiaries']) * $points['star']);
        $myClub = $con->query("SELECT * FROM `clubs` WHERE `clubId` = '$clubId'");
        $myClub = $myClub->fetch_assoc();
        $myClubPoints = $myClub['activityStar'];
        if ($stars <= 1) {
            $stars = 1;
        }
        $stars = $myClubPoints + $stars;
        $con->query("UPDATE `clubs` SET `activityStar`='$stars' WHERE `clubId` = '$clubId'");

        foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
            $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
            $imageUploadPath = './' . $uploadPath . $file_name;

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allowTypes)) {
                $tname = $_FILES["image"]["tmp_name"][$key];
                move_uploaded_file($tname, $imageUploadPath);
                $addImageSql = "INSERT INTO `images`(`img`, `category`, `categoryId`) VALUES ('$imageUploadPath', 'activities', '$activityId')";
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
    echo json_encode($response);
}

//Add News
else if (isset($_POST['add-news'])) {
    $status = 'error';
    $message = '';
    $error = array();
    $response = array();

    $newsTitle = $_POST['newsTitle'];
    $newsTitle = strip_tags($newsTitle);
    $newsTitle = mysqli_real_escape_string($con, $newsTitle);

    if (isset($_POST['date']) && $_POST['date'] != null) {
        $date = $_POST['date'];
        $date = strip_tags($date);
        $date = mysqli_real_escape_string($con, $date);
    } else {
        $date = date('Y-m-d H:i:s');
        $date = mysqli_real_escape_string($con, $date);
    }

    $description = $_POST['description'];
    $description = strip_tags($description);
    $description = mysqli_real_escape_string($con, $description);

    $newsPaperLink = $_POST['newsPaperLink'];
    $newsPaperLink = strip_tags($newsPaperLink);
    $newsPaperLink = mysqli_real_escape_string($con, $newsPaperLink);

    $authorId = $_POST['authorId'];
    $authorId = strip_tags($authorId);
    $authorId = mysqli_real_escape_string($con, $authorId);

    $clubId = $_POST['clubId'];
    $clubId = strip_tags($clubId);
    $clubId = mysqli_real_escape_string($con, $clubId);

    $addNewsSql = "INSERT INTO `news`(`newsTitle`, `date`, `description`, `newsPaperLink`, `authorId`, `clubId`) VALUES ('$newsTitle', '$date', '$description', '$newsPaperLink', '$authorId', '$clubId')";
    $getNewsSql = "SELECT `newsId` FROM `news` WHERE `newsTitle` = '$newsTitle' AND `date` = '$date' AND `authorId` = '$authorId' AND `clubId` = '$clubId'";

    if (mysqli_query($con, $addNewsSql)) {
        $newsId = mysqli_query($con, $getNewsSql);
        $newsId = mysqli_fetch_array($newsId);
        $newsId = $newsId['newsId'];

        foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
            $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
            $imageUploadPath = './' . $uploadPath . $file_name;

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allowTypes)) {
                $tname = $_FILES["image"]["tmp_name"][$key];
                move_uploaded_file($tname, $imageUploadPath);
                $addImageSql = "INSERT INTO `images`(`img`, `category`, `categoryId`) VALUES ('$imageUploadPath', 'news', '$newsId')";
                if (mysqli_query($con, $addImageSql)) {
                    $status = 'success';
                }
            } else {
                array_push($error, "$file_name, ");
            }
        }
    }
    echo json_encode($_POST);
    $response += array('status' => $status);
    $response += array('imgError' => $error);
    echo json_encode($response);
}

//Get Stuff
//API CALL: https://websitename/api.php?category=news&view=true

else if (isset($_GET['category'], $_GET['view'])) {
    $category = $_GET['category'];
    $json_array = array();

    if ($category == "events") {
        $sql = (isset($_GET['id'])) ? "SELECT * FROM `events` WHERE `eventId` = '" . $_GET['id'] . "'" : "SELECT * FROM `events`";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $sql2 = "SELECT `img` FROM `images` WHERE `category` = 'events' AND `categoryId` = " . $row['eventId'];
            $images = array();
            $x = mysqli_query($con, $sql2);
            while ($path = mysqli_fetch_assoc($x)) {
                $images[] = $path;
            }
            $json_array[] = array('event' => $row, 'image' => $images);
        }
    } else if ($category == "activities") {
        $sql = (isset($_GET['id'])) ? "SELECT * FROM `activities` WHERE `activityId` = '" . $_GET['id'] . "'" : "SELECT * FROM `activities`";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $sql2 = "SELECT `img` FROM `images` WHERE `category` = 'activities' AND `categoryId` = " . $row['activityId'];
            $images = array();
            $x = mysqli_query($con, $sql2);
            while ($path = mysqli_fetch_assoc($x)) {
                $images[] = $path;
            }
            $json_array[] = array('activity' => $row, 'image' => $images);
        }
    } else if ($category == "news") {
        $sql = (isset($_GET['id'])) ? "SELECT * FROM `news` WHERE `newsId` = '" . $_GET['id'] . "'" : "SELECT * FROM `news` WHERE `verified` = '1'";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $sql2 = "SELECT `img` FROM `images` WHERE `category` = 'news' AND `categoryId` = " . $row['newsId'];
            $images = array();
            $x = mysqli_query($con, $sql2);
            while ($path = mysqli_fetch_assoc($x)) {
                $images[] = $path;
            }
            $json_array[] = array('news' => $row, 'image' => $images);
        }
    }
    echo json_encode($json_array);
}

//API CALL: https://websitename/api.php?category=news&id=1&delete=true
else if (isset($_GET['category'], $_GET['id'], $_GET['delete'])) {
    $status = 'error';
    $response = array();
    $category = $_GET['category'];
    $id = $_GET['id'];

    if ($category == 'news') {
        $sql = "DELETE FROM `news` WHERE `newsId` = '$id'";
    } else if ($category == 'activities') {
        $sql = "DELETE FROM `activities` WHERE `activityId` = '$id'";
    } else if ($category == 'events') {
        $sql = "DELETE FROM `events` WHERE `eventId` = '$id'";
    }
    if (mysqli_query($con, $sql)) {
        $imgSql = "SELECT * FROM `images` WHERE `category` = '$category' AND `categoryId` = '$id'";
        $images = mysqli_query($con, $imgSql);
        while ($path = mysqli_fetch_array($images)) {
            unlink($path['img']);
        }
        $imgSql = "DELETE FROM `images` WHERE `category` = '$category' AND `categoryId` = '$id'";
        mysqli_query($con, $imgSql);
        $status = 'success';
    }
    $response += array('status' => $status);
    echo json_encode($response);
}

//Get ALL Images: http://localhost/api.php?type=images&category=all
else if (isset($_GET['type'], $_GET['category'])) {
    $response = array();
    $category = $_GET['category'];

    if ($_GET['type'] == 'images') {
        if ($category == 'all') {
            $sql = "SELECT * FROM `images`";
        } else {
            $sql = "SELECT * FROM `images` WHERE `category` = '$category'";
        }
        $images = mysqli_query($con, $sql);
        while ($path = mysqli_fetch_assoc($images)) {
            $response[] = $path;
        }
        echo json_encode($response);
    } else if ($_GET['type'] == 'clubs') {
        if ($category == 'all') {
            $sql = "SELECT * FROM `clubs`";
        } else {
            $sql = "SELECT * FROM `clubs` WHERE `clubId` = '$category'";
            $membersSql = "SELECT `id` FROM `users` WHERE `clubId` = '$category'";
            $membersSql = mysqli_query($con, $membersSql);
            $members = array();
            while ($row = mysqli_fetch_assoc($membersSql)) {
                $members[] = $row;
            }
            $response += array('members' => $members);
        }
        $clubs = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($clubs)) {
            $response[] = $row;
        }
        echo json_encode($response);
    } else if ($_GET['type'] == 'contact') {
        $sql = "SELECT * FROM `contact`";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $response[] = $row;
        }
        echo json_encode($response);
    }
}

//Verify News
else if (isset($_GET['verify-news'], $_GET['newsId'])) {
    $status = 'error';
    $response = array();

    $newsId = $con->real_escape_string(strip_tags($_GET['newsId']));

    if ($con->query("UPDATE `news` SET `verified` = '1' WHERE `newsId` = '$newsId'")) {
        $status = 'success';
    }

    $response += array('status' => $status);
    echo json_encode($response);
} else if (isset($_GET['unverified-news'], $_GET['clubId'])) {
    $response = array();

    $clubId = $_GET['clubId'];

    $unews = $con->query("SELECT * FROM `news` WHERE `verified` = '0' AND `clubId` = '$clubId'")->fetch_all(MYSQLI_ASSOC);
    foreach ($unews as $news) {
        $response[] = $news;
    }

    echo json_encode($response);
}

//Login
else if (isset($_POST['login'])) {
    $status = 'error';
    $type = "invalid";
    $response = array();
    $json_array = array();

    $id = $_POST['id'];
    $id = strip_tags($id);
    $id = mysqli_real_escape_string($con, $id);

    $password = $_POST['password'];

    $sql_user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $result1 = mysqli_query($con, $sql_user);
    $row1 = mysqli_num_rows($result1);

    if ($row1 == 1) {
        $row = mysqli_fetch_assoc($result1);
        $password_hash = $row['password'];
        if (password_verify($password, $password_hash)) {
            $status = "success";
            $type = 'user';
            foreach ($row as $key => $value) {
                if ($key != 'password') {
                    $json_array += array($key => $value);
                }
            }
            $response += array('details' => $json_array);
        }
    } else {
        $type = "invalid";
    }
    $response += array('error' => $status);
    $response += array('type' => $type);
    echo json_encode($response);
}

//Update Profile
else if (isset($_POST['update-profile'])) {

    $status = "error";

    $id = $_POST['id'];
    $id = $con->real_escape_string(strip_tags($id));

    $regionName = $_POST['regionName'];
    $regionName = strip_tags($regionName);
    $regionName = mysqli_real_escape_string($con, $regionName);

    $zoneName = $_POST['zoneName'];
    $zoneName = strip_tags($zoneName);
    $zoneName = mysqli_real_escape_string($con, $zoneName);

    $firstName = $_POST['firstName'];
    $firstName = strip_tags($firstName);
    $firstName = mysqli_real_escape_string($con, $firstName);

    if (isset($_POST['middleName'])) {
        $middleName = $_POST['middleName'];
        $middleName = strip_tags($middleName);
        $middleName = mysqli_real_escape_string($con, $middleName);
    } else {
        $middleName = "";
    }

    $lastName = $_POST['lastName'];
    $lastName = strip_tags($lastName);
    $lastName = mysqli_real_escape_string($con, $lastName);

    $address1 = $_POST['address1'];
    $address1 = strip_tags($address1);
    $address1 = mysqli_real_escape_string($con, $address1);

    $address2 = $_POST['address2'];
    $address2 = strip_tags($address2);
    $address2 = mysqli_real_escape_string($con, $address2);

    $city = $_POST['city'];
    $city = strip_tags($city);
    $city = mysqli_real_escape_string($con, $city);

    $state = $_POST['state'];
    $state = strip_tags($state);
    $state = mysqli_real_escape_string($con, $state);

    $postalCode = $_POST['postalCode'];
    $postalCode = strip_tags($postalCode);
    $postalCode = mysqli_real_escape_string($con, $postalCode);

    $email = $_POST['email'];
    $email = strip_tags($email);
    $email = mysqli_real_escape_string($con, $email);

    $phone = $_POST['phone'];
    $phone = strip_tags($phone);
    $phone = mysqli_real_escape_string($con, $phone);

    if (isset($_POST['spouseName'])) {
        $spouseName = $_POST['spouseName'];
        $spouseName = strip_tags($spouseName);
        $spouseName = mysqli_real_escape_string($con, $spouseName);
    } else {
        $spouseName = "";
    }

    $dob = $_POST['dob'];
    $dob = strip_tags($dob);
    $dob = mysqli_real_escape_string($con, $dob);

    $gender = $_POST['gender'];
    $gender = strip_tags($gender);
    $gender = mysqli_real_escape_string($con, $gender);

    $occupation = $_POST['occupation'];
    $occupation = strip_tags($occupation);
    $occupation = mysqli_real_escape_string($con, $occupation);

    $sql = "UPDATE `users` SET `regionName`='$regionName',`zoneName`='$zoneName',`firstName`='$firstName',`middleName`='$middleName',`lastName`='$lastName',`address1`='$address1',`address2`='$address2',`city`='$city',`state`='$state',`postalCode`='$postalCode',`email`='$email',`phone`='$phone',`spouseName`='$spouseName',`dob`='$dob',`gender`='$gender',`occupation`='$occupation',`verified`='1' WHERE `id` = '$id'";
    if (mysqli_query($con, $sql)) {
        $status = "success";
    }

    echo json_encode(array('status' => $status));
}

//Contact Us
else if (isset($_POST['contact-us'])) {
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
    echo json_encode($response);
}

//Member Directory
else if (isset($_GET['member-directory'])) {
    $response = array();

    $limit = 25;
    $page;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $start = ($page - 1) * $limit;
    $sql = "SELECT * FROM `users` LIMIT $start, $limit";
    $sql = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }

    $count = $con->query("SELECT count(`id`) AS `id` FROM `users`");
    $count = $count->fetch_assoc();
    $count = $count['id'];

    $response[] = array('total' => (int)($count / $limit) + 1);
    echo json_encode($response);
}

//Search Member Directory
else if (isset($_GET['search-member-directory'], $_GET['search'], $_GET['column'], $_GET['page'])) {
    $response = array();

    $limit = 25;
    $page;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $search = $con->real_escape_string(strip_tags($_GET['search']));
    $column = $con->real_escape_string(strip_tags($_GET['column']));

    $start = ($page - 1) * $limit;
    $sql = "SELECT * FROM `users` WHERE `$column` LIKE '%$search%' LIMIT $start, $limit";
    $sql = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }

    $count = $con->query("SELECT count(`id`) AS `id` FROM `users` WHERE `$column` LIKE '%$search%'");
    $count = $count->fetch_assoc();
    $count = $count['id'];

    $response[] = array('total' => (int)($count / $limit) + 1);
    echo json_encode($response);
}

//Search Business Directory
else if (isset($_GET['search-business-directory'], $_GET['search'], $_GET['column'], $_GET['page'])) {
    $response = array();

    $limit = 25;
    $page;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $search = $con->real_escape_string(strip_tags($_GET['search']));
    $column = $con->real_escape_string(strip_tags($_GET['column']));

    $start = ($page - 1) * $limit;
    $sql = "SELECT * FROM `users` WHERE `$column` LIKE '%$search%' AND `occupation` != '-' LIMIT $start, $limit";
    $sql = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }

    $count = $con->query("SELECT count(`id`) AS `id` FROM `users` WHERE `$column` LIKE '%$search%' AND `occupation` != '-'");
    $count = $count->fetch_assoc();
    $count = $count['id'];

    $response[] = array('total' => (int)($count / $limit) + 1);
    echo json_encode($response);
}

//Business Directory
else if (isset($_GET['business-directory'])) {
    $response = array();
    $limit = 25;
    $page;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $start = ($page - 1) * $limit;
    $sql = "SELECT * FROM `users` WHERE `occupation` != '-' LIMIT $start, $limit";
    $sql = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }

    $count = $con->query("SELECT count(`id`) AS `id` FROM `users` WHERE `occupation` != '-'");
    $count = $count->fetch_assoc();
    $count = $count['id'];

    $response[] = array('total' => (int)($count / $limit) + 1);
    echo json_encode($response);
}

//Slider Image
else if (isset($_GET['slider-img'])) {
    $response = array();
    $sql = "SELECT * FROM `slider`";
    $sql = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }
    echo json_encode($response);
}

//Activity Type
else if (isset($_GET['get-type'])) {
    $response = array();

    $sql = $con->query("SELECT DISTINCT `type` FROM `activitytype`");
    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }
    echo json_encode($response);
}

//Activity Sub Type
else if (isset($_GET['get-subtype'])) {
    $type = $_GET['get-subtype'];
    $type = strip_tags($type);
    $type = $con->real_escape_string($type);

    $response = array();

    $sql = $con->query("SELECT DISTINCT `sub-type` FROM `activitytype` WHERE `type` = '$type'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }
    echo json_encode($response);
}

//Activity Category
else if (isset($_GET['get-category'])) {
    $type = $_GET['get-category'];
    $type = strip_tags($type);
    $type = $con->real_escape_string($type);

    $response = array();

    $sql = $con->query("SELECT DISTINCT `category` FROM `activitytype` WHERE `sub-type` = '$type'");
    while ($row = mysqli_fetch_assoc($sql)) {
        $response[] = $row;
    }
    echo json_encode($response);
}

//Activity Placeholder
else if (isset($_GET['get-placeholder'])) {
    $type = $_GET['get-placeholder'];
    $type = strip_tags($type);
    $type = $con->real_escape_string($type);

    $response = $con->query("SELECT DISTINCT `placeholder` FROM `activitytype` WHERE `category` = '$type'")->fetch_assoc();
    echo json_encode($response, JSON_PRETTY_PRINT);
}

//Get Zones
else if (isset($_GET['get-zone'])) {
    $response = array();
    $region = $con->real_escape_string(strip_tags($_GET['region']));

    $zones = $con->query("SELECT DISTINCT `zoneName` FROM `users` WHERE `regionName` = '$region' ORDER BY `zoneName`");
    while ($row = mysqli_fetch_assoc($zones)) {
        $response[] = $row;
    }
    echo json_encode($response);
}

//Club based on region and Zone
else if (isset($_GET['get-club'], $_GET['zone'], $_GET['region'])) {
    $response = array();
    $zone = $con->real_escape_string(strip_tags($_GET['zone']));
    $region = $con->real_escape_string(strip_tags($_GET['region']));

    $clubs = $con->query("SELECT DISTINCT `clubName`, `clubId` FROM `users` WHERE `regionName` = '$region' AND `zoneName` = '$zone' ORDER BY `clubId`");
    while ($row = mysqli_fetch_assoc($clubs)) {
        $response[] = $row;
    }
    echo json_encode($response);
}

//Change Password
else if (isset($_POST['change-password'])) {

    $id = $con->real_escape_string(strip_tags($_POST['id']));
    $user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $user = mysqli_query($con, $user);
    $user = mysqli_fetch_array($user);

    $cpass = $_POST['cpass'];
    $npass1 = $_POST['npass1'];
    $npass2 = $_POST['npass2'];

    $password = $user['password'];
    if (password_verify($cpass, $password) && $npass1 === $npass2) {
        $npass2 = password_hash($npass1, PASSWORD_BCRYPT);
        if (mysqli_query($con, "UPDATE `users` SET `password` = '$npass2' WHERE `id` = '$id'")) {
            echo json_encode(array('status' => 'success'));
        }
    } else {
        echo json_encode(array('status' => 'failed'));
    }
}

//Get Reports
else if (isset($_GET['get-reports'], $_GET['month'])) {

    $response = array();

    $month = $con->real_escape_string(strip_tags($_GET['month']));
    $reports = mysqli_query($con, "SELECT * FROM `reports` WHERE `month` = '$month'");
    while ($row = mysqli_fetch_assoc($reports)) {
        $response[] = $row;
    }

    echo json_encode($response);
}

//Submitted Reports
else if (isset($_GET['get-submitted-reports'], $_GET['month'], $_GET['clubId'])) {

    $response = array();

    $month = $con->real_escape_string(strip_tags($_GET['month']));
    $clubId = $con->real_escape_string(strip_tags($_GET['clubId']));

    $submittedReports = $con->query("SELECT * FROM `updated-reports`, `reports` WHERE `updated-reports`.`clubId` = '$clubId' AND `updated-reports`.`month` = '$month' AND `updated-reports`.`reportId` = `reports`.`id` AND `reports`.`month` = '$month'");
    while ($row = $submittedReports->fetch_assoc()) {
        $response[] = $row;
    }

    echo json_encode($response);
}

//Check if updated
else if (isset($_GET['check-if-updated'], $_GET['month'], $_GET['clubId'])) {
    $month = $con->real_escape_string(strip_tags($_GET['month']));
    $clubId = $con->real_escape_string(strip_tags($_GET['clubId']));

    $res = $con->query("SELECT `month-" . $month . "` FROM `clubs` WHERE `clubId` = '$clubId'");
    $res = $res->fetch_array();

    echo json_encode(array('updated' => $res[0]));
}

//Submit Report
else if (isset($_POST['update-report'])) {

    $month = $_POST['month'];
    $month = $con->real_escape_string(strip_tags($month));

    $clubId = $con->real_escape_string(strip_tags($_POST['clubId']));

    $clubDetail = "SELECT * FROM `clubs` WHERE `clubId` = '$clubId'";
    $clubDetail = mysqli_query($con, $clubDetail);
    $clubDetail = mysqli_fetch_assoc($clubDetail);

    $prevStars = (int)$clubDetail['stars'];

    $sql1 = "UPDATE `clubs` SET `month-" . $month . "`='1',`last-updated`='$date' WHERE `clubId` = '$clubId'";
    if (mysqli_query($con, $sql1)) {

        foreach ($_FILES["image"]["tmp_name"] as $key => $tmp_name) {
            $file_name = rand(1000, 10000) . '-' . basename($_FILES["image"]["name"][$key]);
            $imageUploadPath = './' . $uploadPath . $file_name;

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allowTypes)) {
                $tname = $_FILES["image"]["tmp_name"][$key];
                move_uploaded_file($tname, $imageUploadPath);
                $addImageSql = "INSERT INTO `admin-images`(`img`, `clubId`, `month`) VALUES ('$imageUploadPath', '$clubId', '$month')";
                if (mysqli_query($con, $addImageSql)) {
                    $status = 'success';
                }
            } else {
                array_push($error, "$file_name, ");
            }
        }

        foreach ($_POST['report-item'] as $check) {
            $check = str_replace("\"", "", $check);
            $myData = explode("*", $check);
            $multiplier = (int)$myData[1];
            $reportId = $myData[0];
            if (mysqli_query($con, "INSERT INTO `updated-reports`(`reportId`, `clubId`, `month`, `multiplier`) VALUES ('$reportId', '$clubId', '$month', '$multiplier')")) {
                $sql = mysqli_query($con, "SELECT * FROM `reports` WHERE `id` = $reportId");
                $sql = mysqli_fetch_assoc($sql);
                $prevStars += $multiplier * (int)$sql['stars'];
                mysqli_query($con, "UPDATE `clubs` SET `stars` = '$prevStars' WHERE `clubId` = '$clubId'");
            }
        }
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'failed'));
    }
} else if (isset($_GET['is-powerful'], $_GET['title'])) {
    $power;
    if (
        strpos(strtolower($_GET['title']), 'president') !== false ||
        strpos(strtolower($_GET['title']), 'club president') !== false ||
        strpos(strtolower($_GET['title']), 'club secretary') !== false ||
        strpos(strtolower($_GET['title']), 'secretary') !== false ||
        strpos(strtolower($_GET['title']), 'district governor') !== false ||
        strpos(strtolower($_GET['title']), 'cabinet secretary') !== false ||
        strpos(strtolower($_GET['title']), 'cabinet treasurer') !== false ||
        strpos(strtolower($_GET['title']), 'club treasurer') !== false    
    ) {
        $power = true;
    } else {
        $power = false;
    }

    echo json_encode(array('power' => $power));
} else if (isset($_GET['get-data'])) {
    $res = array();
    $types = $con->query("SELECT DISTINCT `activityType` FROM `activities`")->fetch_all(MYSQLI_ASSOC);
    foreach ($types as $type) {
        $type = $type['activityType'];
        $arr2 = array();
        $dataType = $con->query("SELECT SUM(`peopleServed`) AS `people-served`, SUM(`amount`) AS `amount` FROM `activities` WHERE `activityType` = '$type'")->fetch_assoc();
        $subTypes = $con->query("SELECT DISTINCT `activitySubType` FROM `activities` WHERE `activityType` = '$type'")->fetch_all(MYSQLI_ASSOC);
        foreach ($subTypes as $subType) {
            $subType = $subType['activitySubType'];
            $data = $con->query("SELECT DISTINCT `activitySubType`, SUM(`peopleServed`) AS `people-served`, SUM(`amount`) AS `amount` FROM `activities` WHERE `activityType` = '$type' AND `activitySubType` = '$subType'")->fetch_assoc();
            $arr2[] = $data;
            //echo json_encode($data, JSON_PRETTY_PRINT);
        }
        $arr[] = array('Type' => $type, 'Benefeciaries' => $dataType['people-served'], 'Amount Spent' => $dataType['amount'], 'Details' => $arr2);
    }

    echo json_encode($arr, JSON_PRETTY_PRINT);
} elseif (isset($_GET['is-zone-cp'], $_GET['title'])) {
    $power;
    if (
            strpos(strtolower($_GET['title']), 'zone chairperson') !== false
        ) {
            $power = true;
        } else {
            $power = false;
        }
        echo json_encode(array('zone-cp' => $power));
} elseif (isset($_GET['is-region-cp'], $_GET['title'])) {
    $power;
    if (
            strpos(strtolower($_GET['title']), 'region chairperson') !== false
        ) {
            $power = true;
        } else {
            $power = false;
        }
        echo json_encode(array('region-cp' => $power));
} elseif (isset($_GET['get-zone-info'], $_GET['id'])) {
    $id = $con->real_escape_string(strip_tags($_GET['id']));
    
    $user = $con->query("SELECT * FROM `users` WHERE `id` = '$id'")->fetch_assoc();
    
    $regionName = $user['regionName'];
    $zoneName = $user['zoneName'];
    $myZoneClubs = $con->query("SELECT DISTINCT u.`clubName`, u.`clubId`, c.* FROM `users` u, `clubs` c WHERE u.`regionName` = '$regionName' AND u.`zoneName` = '$zoneName' AND u.`clubId` = c.`clubId` ORDER BY u.`clubName`")->fetch_all(MYSQLI_ASSOC);
    
    $res = array();
    
    foreach ($myZoneClubs as $clubs){
        $club_id = $clubs['clubId'];
        $activityCount = $con->query("SELECT COUNT(`activityId`) AS `activity` FROM `activities` WHERE `clubId` = '$club_id'")->fetch_assoc()['activity'];
        $clubs += array('activityCount' => $activityCount);
        $res[] = $clubs;
    }
    
    echo json_encode($res, JSON_PRETTY_PRINT);
} elseif (isset($_GET['get-region-info'], $_GET['id'])) {
    $id = $con->real_escape_string(strip_tags($_GET['id']));
    
    $user = $con->query("SELECT * FROM `users` WHERE `id` = '$id'")->fetch_assoc();
    
    $regionName = $user['regionName'];
    $zoneName = $user['zoneName'];
    $myZoneClubs = $con->query("SELECT DISTINCT u.`clubName`, u.`clubId`, u.`zoneName`, c.* FROM `users` u, `clubs` c WHERE u.`regionName` = '$regionName' AND u.`clubId` = c.`clubId` ORDER BY u.`zoneName`, u.`clubName`")->fetch_all(MYSQLI_ASSOC);
    
    $res = array();
    
    foreach ($myZoneClubs as $clubs){
        $club_id = $clubs['clubId'];
        $activityCount = $con->query("SELECT COUNT(`activityId`) AS `activity` FROM `activities` WHERE `clubId` = '$club_id'")->fetch_assoc()['activity'];
        $clubs += array('activityCount' => $activityCount);
        $res[] = $clubs;
    }
    
    echo json_encode($res, JSON_PRETTY_PRINT);
} elseif (isset($_GET['get-user-info'])) {
    $id = $con->real_escape_string($_GET['user-id']);
    $user = $con->query("SELECT `clubName`, `firstName`, `lastName`, `email`, `city`, `phone` FROM `users` WHERE `id` = '$id'")->fetch_assoc();
    echo json_encode($user, JSON_PRETTY_PRINT);
}

//Close Connection
mysqli_close($con);