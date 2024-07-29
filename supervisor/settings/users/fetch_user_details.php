<?php
session_start();

require_once('../../../config.php');

$user = $_SESSION['username'];

$get_user_details = "SELECT id, c_employee_code, c_realname, c_department, c_password, c_position FROM t_car_users WHERE c_employee_code = ?";
$user_stmt = odbc_prepare($conn, $get_user_details);

    if ($user_stmt === false) {
        echo json_encode(["error" => "Failed to prepare query"]);
        exit;
    }

    if (odbc_execute($user_stmt, array($user))) {
        $user_details = odbc_fetch_array($user_stmt);
        if ($user_details) {
            echo json_encode($user_details);
        } else {
            echo json_encode(["error" => "No user details found"]);
        }
    } else {
        echo json_encode(["error" => "Failed to execute query"]);
    }
?>