<?php
    function check_user_group($required_group) {
        if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != $required_group) {
            require_once('auto_logout.php');
            exit();
        }
    }
?>