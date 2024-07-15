<?php
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');

define('base_url','http://localhost/car/');
global $dsn, $user, $pass;
$dsn = "PostgreSQL30"; 
$user = "postgres";    
$pass = "admin12345";    

$conn = odbc_connect($dsn, $user, $pass);

if (!$conn) {
    die("Connection failed: " . odbc_errormsg());
}
function redirect($url) {
    header("Location: $url");
    exit();
}

?>