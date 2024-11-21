<?php
$host = 'localhost';
$dbname = 'CAR_TESTDB_TESTING';
$username_db = 'postgres';
$password_db = 'admin12345';

$pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username_db, $password_db); 

?> 