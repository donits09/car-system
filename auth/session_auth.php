<?php
session_start();

function initialize_session($username, $user_group) {
    $_SESSION['username'] = $username;
    $_SESSION['user_group'] = $user_group;
}

function check_session() {
    if (isset($_SESSION['username']) && isset($_SESSION['user_group'])) {
        $c_group = $_SESSION['user_group'];
        if ($c_group == 1) {
            header('Location: ../admin/car/index.php');
            exit();
        } elseif ($c_group == 2) {

            header('Location: ../admin/user/index.php');

            exit();
        }
        elseif ($c_group == 3) {

            header('Location: ../admin2/index.php');

            exit();
        }
    }
}
?>
