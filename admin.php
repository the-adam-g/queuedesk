<?php
session_start();
include "config.php";
if (isset($_SESSION['login'])) {
    $id = $_SESSION['user_id'];
    $name = $_SESSION['name'];
    $role = $_SESSION['role'];
} else {
    header('Location: index.php');
    exit();
}
if ($role !== "admin"){
    header('Location: index.php');
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        if(!hash_equals($_SESSION['csrf'], $_POST['csrf'])) { 
            die ("<link rel='stylesheet' href='light.css'> <h1>Queue<special>Desk</special> <special style='color:red;'>Error Handler</special></h1><h1>Fatal request - CSRF Violation <br><special style='color:red;'> Error code: (#b1)</special></h1><a id='navb' style='max-width: 10%;' href='dash.php'>Return</a>");
        }
        $userid = $_POST["id"];
        $stmt6 = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt6->execute([$userid]);
        $searchrole = $stmt6->fetchColumn(); 
        if ($searchrole !== 'admin') {
            $stmt1 = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt1->execute([$userid]);
            header("Location: admin.php");
        } else {
            die ("<link rel='stylesheet' href='light.css'> <h1>Queue<special>Desk</special> <special style='color:red;'>Error Handler</special></h1><h1>Fatal request - Forbidden Action <br><special style='color:red;'> Error code: (#b2)</special></h1><a id='navb' style='max-width: 10%;' href='dash.php'>Return</a>");
        }
        exit;
    }
    if (isset($_POST['assign'])) {
        if(!hash_equals($_SESSION['csrf'], $_POST['csrf'])) { 
            die ("<link rel='stylesheet' href='light.css'> <h1>Queue<special>Desk</special> <special style='color:red;'>Error Handler</special></h1><h1>Fatal request - CSRF Violation <br><special style='color:red;'> Error code: (#b1)</special></h1><a id='navb' style='max-width: 10%;' href='dash.php'>Return</a>");
        }
        $newname = $_POST["users"];
        $ticketid = $_POST["id"];
        $stmt3 = $pdo->prepare('UPDATE tickets SET assignee = ? WHERE id = ?');
        $stmt3->execute([$newname, $ticketid]);
        header("Location: admin.php");
        exit;
    }
    if (isset($_POST['close'])) {
        if(!hash_equals($_SESSION['csrf'], $_POST['csrf'])) { 
            die ("<link rel='stylesheet' href='light.css'> <h1>Queue<special>Desk</special> <special style='color:red;'>Error Handler</special></h1><h1>Fatal request - CSRF Violation <br><special style='color:red;'> Error code: (#b1)</special></h1><a id='navb' style='max-width: 10%;' href='dash.php'>Return</a>");
        }
        $ticketid = $_POST["id"];
        $stmt = $pdo->prepare('INSERT INTO past_tickets SELECT * FROM tickets WHERE id = ?');
        $stmt->execute([$ticketid]);
        $stmt3 = $pdo->prepare('UPDATE users SET solved = solved + 1 WHERE username = ?');
        $stmt3->execute([$name]);
        $stmt2 = $pdo->prepare('DELETE FROM tickets WHERE id = ?');
        $stmt2->execute([$ticketid]);
        header("Location: admin.php");
        exit;
    }
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
if ($page < 1) {
    header('Location: admin.php?page=1');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QueueDesk Admin Panel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="light.css">
    <meta property="og:title" content="QueueDesk">
    <meta property="og:description" content="Admin Panel">
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
        <h1><special>QueueDesk admin panel.</special></h1>
        <h1 id="head2"><?php echo 'Welcome <special>' . $role . "</special> " . $name;?></special></h1>
        <div id="navbar">
            <a id="navb" href="dash.php">Open Tickets</a>
            <a id="navb" href="logout.php">Logout</a>
            <?php echo '<a id="navb" href="visual.php?id=' . $id . '">Visual</a>'; ?>
        </div>
        <p>Summary</p>
        <div id="boxes">
            <div id="ibox">
                <h1>Tickets closed:</h1>
                <?php 
                $countstmt = $pdo->prepare("SELECT COUNT(*) FROM past_tickets");
                $countstmt->execute();
                $count = $countstmt->fetchColumn();
                echo "<h1>" . $count . "</h1>";
                ?>
            </div>
            <div <?php $countstmt = $pdo->prepare("SELECT COUNT(*) FROM tickets"); $countstmt->execute(); $count = $countstmt->fetchColumn(); if ($count > 0) { echo 'id="rbox"'; } else { echo 'id="gbox"'; }?>  onclick="window.location='#ot';">
                <h1>Tickets Open:</h1>
                <?php 
                echo "<h1>" . $count . "</h1>";
                ?>
            </div>
            <div id="ibox">
                <h1>Most closed:</h1>
                <?php 
                $countstmt = $pdo->prepare("SELECT username, solved FROM users ORDER BY solved DESC LIMIT 1");
                $countstmt->execute();
                $row = $countstmt->fetch(PDO::FETCH_ASSOC);
                echo "<h1>" . $row['username'] . ": " . $row['solved'] . "</h1>";
                ?>
            </div>
        </div>
        <p>Admins:</p>
        <table>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>TIMESTAMP</th>
            <th>CURRENT TICKETS</th>
            <th>CLOSED TICKETS</th>
        </tr>
        <?php
        $stmt = $pdo->prepare('SELECT * FROM users WHERE role = "admin"');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            echo("<tr>" . "<td>". $user['id'] . "</td><td>". htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . "</td><td>". $user['timestamp'] . "</td><td>". htmlspecialchars($user['tickets'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($user['solved'], ENT_QUOTES, 'UTF-8') . "</td></tr>");
        }
        ?>
        </table>
        <p>Technicians:</p>
        <table>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>TIMESTAMP</th>
            <th>CURRENT TICKETS</th>
            <th>CLOSED TICKETS</th>
            <th>MANAGE</th>
        </tr>
        <?php
        $stmt = $pdo->prepare('SELECT * FROM users WHERE role = "technician"');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            echo("<tr>" . "<td>". $user['id'] . "</td><td>". htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . "</td><td>". $user['timestamp'] . "</td><td>". htmlspecialchars($user['tickets'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($user['solved'], ENT_QUOTES, 'UTF-8') . "</td><td>". "<form action='' method='POST'><input type='hidden' name='id' value='" . (int)$user['id'] .  "'/><input type='hidden' name='csrf' value=" . $_SESSION['csrf'] . "><input type='submit' name='delete' value='Delete user'></form></tr>");
        }
        ?>
        </table>
    </section>
    <br> 
    <section id="messages">
        <h1><special>Migrate</special> Tickets</h1>
        <table>
        <tr>
            <th>ID</th>
            <th>TIMESTAMP</th>
            <th>CREATOR</th>
            <th>ASSIGNEE</th>
            <th>TYPE</th>
            <th>DETAILS</th>
            <th>URGENCY</th>
            <th>REASSIGN</th>
        </tr>
        <?php
        $stmt2 = $pdo->prepare('SELECT * FROM users');
        $stmt2->execute();
        $allusers = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $selections = '';
        foreach ($allusers as $auser) { 
            $selections = $selections . '<option value=' . htmlspecialchars($auser['username'], ENT_QUOTES, 'UTF-8') . '>' . htmlspecialchars($auser['username'], ENT_QUOTES, 'UTF-8') . '</option>'; 
        }
        $stmt = $pdo->prepare('SELECT * FROM tickets ORDER BY timestamp DESC');
        $stmt->execute();
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tickets as $ticket) {
            echo("<tr>" . "<td>". $ticket['id'] . "</td><td>". $ticket['timestamp'] . "</td><td>". htmlspecialchars($ticket['creator'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['assignee'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['type'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['details'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['urgency'], ENT_QUOTES, 'UTF-8') . "</td><td>". "<form action='' method='POST'><select name='users' id='users'>" . $selections . "</select><input type='hidden' name='csrf' value=" . $_SESSION['csrf'] . "><input type='hidden' name='id' value='" . (int)$ticket['id'] .  "'/><input type='submit' name='assign' value='Reassign'></form></tr>");
        }
        ?>
        </table>
    </section>
    <br>
    <section id="ot">
        <h1><special>Open</special> Tickets</h1>
        <div id="navbar">
            <a id="navb" href="admin.php?page=1">First page</a>
            <a id="navb" href="admin.php?page=<?php echo ($page - 1); ?>">Prior page</a>
            <a id="navb" href="admin.php?page=<?php echo ($page + 1); ?>">Next page</a>
        </div>
        <p><?php echo "Page: <special>" . $page;?></special></p>
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
            $offset = ($page - 1) * OFFSET;
            $stmt3 = $pdo->prepare('SELECT * FROM tickets ORDER BY timestamp DESC limit :rlimit OFFSET :offset');
            $stmt3->bindValue(':rlimit', LIMIT, PDO::PARAM_INT);
            $stmt3->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt3->execute();
            $tickets = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            foreach ($tickets as $ticket) {
                echo("<tr>" . "<td>". $ticket['id'] . "</td><td>". $ticket['timestamp'] . "</td><td>". htmlspecialchars($ticket['creator'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['email'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['type'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['details'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['urgency'], ENT_QUOTES, 'UTF-8') . "</td><td>". "<form action='' method='POST'><input type='hidden' name='csrf' value=" . $_SESSION['csrf'] . "><input type='hidden' name='id' value='" . (int)$ticket['id'] .  "'/><input type='submit' name='close' value='Close ticket'></form></tr>");
            }
            ?>
        </table>
    </section>
    <br> 
    <section id="messages">
        <h1><special>Closed</special> Tickets</h1>
        <div id="navbar">
            <a id="navb" href="admin.php?page=1">First page</a>
            <a id="navb" href="admin.php?page=<?php echo ($page - 1); ?>">Prior page</a>
            <a id="navb" href="admin.php?page=<?php echo ($page + 1); ?>">Next page</a>
        </div>
        <p><?php echo "Page: <special>" . $page;?></special></p>
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
            $offset = ($page - 1) * OFFSET;
            $stmt3 = $pdo->prepare('SELECT * FROM past_tickets ORDER BY timestamp DESC limit :rlimit OFFSET :offset');
            $stmt3->bindValue(':rlimit', LIMIT, PDO::PARAM_INT);
            $stmt3->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt3->execute();
            $tickets = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            foreach ($tickets as $ticket) {
                echo("<tr>" . "<td>". $ticket['id'] . "</td><td>". $ticket['timestamp'] . "</td><td>". htmlspecialchars($ticket['creator'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['email'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['type'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['details'], ENT_QUOTES, 'UTF-8') . "</td><td>". htmlspecialchars($ticket['urgency'], ENT_QUOTES, 'UTF-8') . "</td><td>". "<form action='' method='POST'><input type='hidden' name='csrf' value=" . $_SESSION['csrf'] . "><input type='hidden' name='id' value='" . (int)$ticket['id'] .  "'/><input type='submit' name='close' value='Close ticket'></form></tr>");
            }
            ?>
        </table>
    </section>
    <br> 
    <section id="messages">
        <h1><special>Export</special> Your Data As <special>.CSV</special></h1>
        <?php
        $stmt4 = $pdo->prepare('SELECT username FROM users');
        $stmt4->execute();
        $allusers = $stmt4->fetchAll(PDO::FETCH_ASSOC);
        $userselections = '';
        foreach ($allusers as $tuser) { 
            $userselections = $userselections . '<option value="' . htmlspecialchars($tuser['username'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($tuser['username'], ENT_QUOTES, 'UTF-8') . '</option>';
        }
        echo "<form action='export.php' method='POST'>";        
        echo "<select name='target' id='target'>" . $userselections . "</select>";
        $stmt5 = $pdo->prepare('SHOW TABLES');
        $stmt5->execute();
        $tables = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        $tableselections = '';
        foreach ($tables as $table) { 
            $tablename = array_values($table)[0];
            $tableselections = $tableselections . '<option value="' . htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        echo "<select name='data' id='data'>" . $tableselections . "</select>";
        echo "<input type='hidden' name='csrf' value=" . $_SESSION['csrf'] . ">";
        echo "<input type='submit' name='export' value='Export data'></input>";
        echo "</form>";
        ?>
    </section>
    <br> 
</body>
