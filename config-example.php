<?php

date_default_timezone_set("America/New_York");

session_start();

$server = "localhost";
$user = "root";
$pword = "your_db_password";
$db = "johndo";

$conn = mysqli_connect($server, $user, $pword, $db);

if (!$conn) {
    die("Fatal error: connection to database failed: " . mysqli_connect_error() . "Try reloading the page in a few moments. If the issue persists, contact us.");
}

?>
