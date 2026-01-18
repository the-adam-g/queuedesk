<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dash.php');
    exit();
} else {
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QueueDesk</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="light.css">
    <meta property="og:title" content="QueueDesk">
    <meta property="og:description" content="QueueDesk. IT made simple.">
    <meta property="og:image" content="idkyet">
    <meta property="og:url" content="http://librebook.co.uk/QueueDesk/">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400..900&display=swap" rel="stylesheet">
</head>
<body>
    <section id="head">
        <h1>Queue<special>Desk - IT made simple.</special></h1>
    </section>
    <section id="messages">
        <h1>What is Queue<special>Desk</special> ?</h1>
        <hr>
        <p>Queue<special>Desk</special> is an intuitive ticket management software designed to be intuitive and easy to use for new and experienced IT technicians</p>
    </section>
    <br></br> 
    <section id="messages">
        <a href="login.php"><p>Login/Register as staff</p></a>
        <p></p>
        <a href="ticket.php"><p>Create a ticket</p></a>
    </section>
    <br><br>
    <div id="creditbar">
        <p>Queue<special>Desk</special> was created by Adam Gillion</p>
    </div>
</body>
