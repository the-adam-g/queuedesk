<?php
session_start();
include "config.php";
$uac = $_GET['uac'];
if (!$uac || !preg_match('/^[a-f0-9]{32}$/', $uac)) {
    header("Location: index.php");
}
$myticket = "/myticket.php?uac=" . urlencode($uac);
$uac = htmlspecialchars($uac, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QueueDesk dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="light.css">
    <meta property="og:title" content="QueueDesk">
    <meta property="og:description" content="Create a QueueDesk ticket">
    <meta property="og:image" content="idkyet">
    <meta property="og:url" content="http://librebook.co.uk/QueueDesk/">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400..900&display=swap" rel="stylesheet">
</head>
<body>
    <section id="head">
        <h1>Queue<special>Desk</special></h1>
    </section>
    <section id="messages">
        <h1>Your Queue<special>Desk</special> ticket has been successfully created!</h1>
        <p>Thank you for using Queue<special>Desk</special></p>
        <p>Queue<special>Desk</special> advises that you do not share your UAC and is not responsible for any repurcussions of a shared UAC.</p>
        <?php 
        echo "<h1>Your Unique Access Code is: " . htmlspecialchars($uac, ENT_QUOTES, 'UTF-8');
        echo "<h1>You can view the progress of your ticket at the following URL: </h1>";
        echo "<h1><a href='/myticket.php?uac=" . urlencode($uac) . "'>" . $_SERVER['SERVER_NAME'], $myticket . "</a></h1>";
        ?>
    </section>
    <br> 
</body>
