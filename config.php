<?php
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');


$dsn = "PostgreSQL30"; // Replace with your DSN name
$user = "postgres";    // Replace with your database username
$pass = "admin12345";    // Replace with your database password

$conn = odbc_connect($dsn, $user, $pass);

if (!$conn) {
    die("Connection failed: " . odbc_errormsg());
}


function redirect($url) {
    header("Location: $url");
    exit();
}
?>


