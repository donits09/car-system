
<?php
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');


/* define('base_url','http://192.168.0.75/car/'); */

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

##### JUDZ CONNECTION ######
$dbhost = 'localhost';
$dbport = '5432'; 
$dbname = 'CAR_TESTDB';
$dbuser = 'postgres';
$dbpass = 'admin12345';

$cnx  = pg_connect("host=$dbhost port=$dbport dbname=$dbname user=$dbuser password=$dbpass");
if (!$cnx) {
    die("Error connecting to PostgreSQL database: " . pg_last_error());
}
