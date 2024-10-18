<?php
#session_start();
$_SESSION = array();
session_destroy();

header("Location: " . $base_url . "../auth/login.php");
exit();
?>
