<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $link = "https"; 
else
    $link = "http"; 
$link .= "://"; 
$link .= $_SERVER['HTTP_HOST']; 
$link .= $_SERVER['REQUEST_URI'];
if(!isset($_SESSION['userdata']) && !strpos($link, 'login.php')){
	redirect('auth/login.php');
}

if(isset($_SESSION['userdata']) && strpos($link, 'login.php') && $_SESSION['userdata']['user_type'] == 'IT Admin'){
	redirect('admin/index.php');
} 

if(isset($_SESSION['userdata']) && strpos($link, 'login.php') && $_SESSION['userdata']['user_type'] == 'Agent'){
	redirect('agent_user/index.php');
} 
