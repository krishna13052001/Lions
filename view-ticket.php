<?php

include_once './creds.php';
$con = new mysqli($host, $user, $pass, $dbname);
if (!isset($_GET['t'])) {
    header("Location: ./event.php");
} else {
    $tId = $con->real_escape_string(strip_tags($_GET['t']));
    $detail = $con->query("SELECT * FROM `booked-tickets` WHERE `id` = '$tId'");
    if ($detail->num_rows !== 1) {
        echo "<script>alert(`No Ticket found!`); window.location.href=`./index.php`;</script>";
    } else {
        $detail = $detail->fetch_assoc();
        $ticket = $con->query("SELECT t.*, e.`eventTitle`, e.`description`, e.`date`, e.`district`, c.`clubName` AS `eventClubName` FROM `booked-tickets` t INNER JOIN `events` e ON e.`eventId` = t.`eventId` INNER JOIN `clubs` c ON e.`clubId` = c.`clubId` WHERE t.`id` = '$tId'")->fetch_assoc();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once './meta.php' ?>
    <link rel="stylesheet" href="./bootstrap-4.1.3-dist/css/bootstrap.min.css" />
    <script src="./bootstrap-4.1.3-dist/js/jquery-3.3.1.min.js"></script>
    <script src="./bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .ticket-bg {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100%;
        width: 100%;
        min-height: 100vh;
        padding: 1rem;
    }

    .ticket {
        width: 100%;
    }

    .ticket .col-md-3 {
        background: #003795;
        border-right: 0.5rem solid #FFCF03;
        padding: 1rem;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .ticket .col-md-3 img {
        width: 7rem;
        display: block;
        margin: auto;
    }

    .ticket .col-md-7 {
        padding: 1rem;
        color: #003795;
        background: whitesmoke;
        position: relative;
    }

    .ticket .col-md-7::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('./img/logo.png') no-repeat center center/contain;
        opacity: 0.15;
    }

    .ticket .col-md-2 {
        background: #003795;
        padding: 1rem;
        border-left: 0.5rem solid #FFCF03;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: #FFCF03;
    }

    .end {
        display: flex;
        justify-content: space-between;
    }
    
    .buttons{
        width: 80%;
        margin: 0.5rem auto;
        display: flex;
        justify-content: space-between;
    }

    .col-md-2 .fa {
        font-size: 5rem;
    }

    @media (max-width: 767px) {

        .col-md-3,
        .col-md-2 {
            border: none !important;
        }

        .col-md-7 {
            border-top: 0.5rem solid #FFCF03;
            border-bottom: 0.5rem solid #FFCF03;
        }
    }
    </style>
    <title>Ticket</title>
</head>

<body>
    <div class="container">
        <div class="ticket-bg">
            <div class="col-12">
                <h2 class="text-center">Event Registration Successful <i class="fa fa-check" style="color: teal;"></i></h2>
                <hr>
            </div>
            <div class="ticket" id="ticket">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="./img/logo2.png" alt="">
                        </div>
                        <div class="col-md-7">
                            <h2 class="text-center"><?= $ticket['eventTitle'] ?></h2>
                            <h6 class="text-center">"Club: <?= $ticket['eventClubName'] ?>"</h6>
                            <p class="text-center">
                                <small>
                                    <em><?= $ticket['description'] ?></em>
                                </small>
                                <br>
                                <br>
                                <strong>Purchased by: <em><?= ucfirst($ticket['firstName']) ?>
                                        <?= ucfirst($ticket['lastName']) ?></em></strong>
                            </p>
                            <div class="end">
                                <small><strong><?= date('d-m-Y', strtotime($ticket['date'])) ?></strong><br>Event date</small>
                                <small><strong><?= $ticket['district'] ?></strong><br>Venue</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <i class="fa fa-star"></i>
                            <small><strong><?= $ticket['id'] ?></strong></small>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="buttons">
                <a class="btn btn-danger" href="event.php"><i class="fa fa-arrow-left"></i> Back</a>
                <button class="btn btn-primary" id="printBtn">Print <i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>
</body>
<script>
    const ticket = document.querySelector('#ticket');
    const printBtn = document.querySelector('#printBtn');
    
    printBtn.addEventListener('click', () => {
        let mywindow = window.open('', 'PRINT');
    
        mywindow.document.write('<html><head><title>' + document.title + '</title>');
        mywindow.document.write(`
        <link rel="stylesheet" href="./bootstrap-4.1.3-dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
        .ticket-bg {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100%;
            width: 100%;
            min-height: 100vh;
            padding: 1rem;
        }
    
        .ticket {
            width: 100%;
        }
    
        .ticket .col-md-3 {
            background: #003795;
            border-right: 0.5rem solid #FFCF03;
            padding: 1rem;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    
        .ticket .col-md-3 img {
            width: 7rem;
            display: block;
            margin: auto;
        }
    
        .ticket .col-md-7 {
            padding: 1rem;
            color: #003795;
            background: whitesmoke;
            position: relative;
        }
    
        .ticket .col-md-7::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('./img/logo.png') no-repeat center center/contain;
            opacity: 0.15;
        }
    
        .ticket .col-md-2 {
            background: #003795;
            padding: 1rem;
            border-left: 0.5rem solid #FFCF03;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: #FFCF03;
        }
    
        .end {
            display: flex;
            justify-content: space-between;
        }
        
        .buttons{
            width: 80%;
            margin: 0.5rem auto;
            display: flex;
            justify-content: space-between;
        }
    
        .col-md-2 .fa {
            font-size: 5rem;
        }
    
        @media (max-width: 767px) {
    
            .col-md-3,
            .col-md-2 {
                border: none !important;
            }
    
            .col-md-7 {
                border-top: 0.5rem solid #FFCF03;
                border-bottom: 0.5rem solid #FFCF03;
            }
        }
        </style>
        `)
        mywindow.document.write('</head><body >');
        mywindow.document.write(`<div class="ticket-bg"><div class="ticket">${ticket.innerHTML}</div></div>`);
        mywindow.document.write('</body></html>');
    
        mywindow.document.close();
        mywindow.focus();
    
        mywindow.print();
        // mywindow.close();
    })
</script>
</html>