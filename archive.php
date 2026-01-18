<?php
session_start();
include 'config.php';

if (isset($_SESSION['login'])) {
    $id = $_SESSION['user_id'];
    $name = $_SESSION['name'];
    $role = $_SESSION['role'];
} else {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketid = $_POST["id"];
    $stmt = $pdo->prepare('INSERT INTO tickets SELECT * FROM past_tickets WHERE id = ?');
    $stmt->execute([$ticketid]);
    $stmt3 = $pdo->prepare('UPDATE users SET tickets = tickets + 1 WHERE username = ?');
    $stmt3->execute([$name]);
    $stmt2 = $pdo->prepare('DELETE FROM past_tickets WHERE id = ?');
    $stmt2->execute([$ticketid]);
    $stmt3 = $pdo->prepare('UPDATE users SET solved = solved - 1 WHERE username = ?');
    $stmt3->execute([$name]);
    header("Location: archive.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QueueDesk past tickets</title>
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
        <h1><special>QueueDesk Closed Tickets.</special></h1>
        <h1 id="head2"><?php echo 'Welcome <special>' . $role . "</special> " . $name;?></special></h1>
        <div id="navbar">
            <a id="navb" href="dash.php">Open Tickets</a>
            <a id="navb" href="logout.php">Logout</a>
            <a <?php if ($role !== "admin") { echo 'style="visibility: hidden;"'; } ?> id="navb" href="admin.php">Admin Panel</a>
        </div>
        <p>Closed Tickets:</p>
        <table>
        <tr>
            <th>ID</th>
            <th>TIMESTAMP</th>
            <th>CREATOR</th>
            <th>EMAIL</th>
            <th>TYPE</th>
            <th>DETAILS</th>
            <th>URGENCY</th>
            <th>MANAGE</th>
        </tr>
        <?php
        $stmt = $pdo->prepare('SELECT * FROM past_tickets WHERE assignee = ? ORDER BY urgency ASC');
        $stmt->execute([$name]);
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tickets as $ticket) {
            echo("<tr>" . "<td>". $ticket['id'] . "</td><td>". $ticket['timestamp'] . "</td><td>". $ticket['creator'] . "</td><td>". $ticket['email'] . "</td><td>". $ticket['type'] . "</td><td>". $ticket['details'] . "</td><td>". $ticket['urgency'] . "</td><td>". "<form action='' method='POST'><input type='hidden' name='id' value='" . $ticket['id'] .  "'/><input type='submit' value='Open ticket'></form></tr>");
        }
        ?>
    </section>
    <br></br> 
</body>
