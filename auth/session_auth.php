<?php
require_once('../config.php');
session_start();
require_once('../classes/Master.php');


function initialize_session($username, $user_group) {
    $_SESSION['username'] = $username;
    $_SESSION['user_group'] = $user_group;

    $master = new Master();
    $module = "Car Log In";
    $notes = "USER LOGGED - $username";
    $master->car_logs($module, $notes);
}

function check_session() {
    if (isset($_SESSION['username']) && isset($_SESSION['user_group'])) {
        $c_group = $_SESSION['user_group'];
        if ($c_group == 1) {
            header('Location: ../admin/car/index.php');
            exit();
        } elseif ($c_group == 2) {
            header('Location: ../supervisor/car/index.php');
            exit();
        } elseif ($c_group == 3) {
            header('Location: ../cashier/car/index.php');
            exit();
        } else{
            header('Location: ../admin2/index.php');
            exit();
        } elseif ($c_group == 4) {
            header('Location: ../viewer/car/index.php');
            exit();
        }
    }
}
?>
