<?php

$db_host = '';
$db_name = '';
$db_user = '';
$db_password = '';

define('ADMIN_UNIVERSAL', password_hash('Super_Admin258$!', PASSWORD_DEFAULT));
define('ADMIN_PASS', password_hash('274Auth__Admin$!', PASSWORD_DEFAULT)); //default passwords, it is recommended you change these
define('ALLOW_REGISTRATION', true);
define('MAX_ATTEMPTS', 5);
define('LOCK_TIME', 300); // 5 mins by default
define('INACTIVITY_LIMIT', 1800); // 30 mins by default
define('OFFSET', 10); //how large the offset is in the pages of tickets in admin panel
define('LIMIT', 10); //how many tickets are loaded in the pages

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
