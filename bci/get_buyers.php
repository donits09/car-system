<?php
session_start();
include('../config.php');

if (isset($_POST['account_no'])) {
    $account_no = $_POST['account_no'];

    $query = "SELECT 
                b.c_b1_last_name, 
                b.c_b1_first_name, 
                b.c_b1_middle_name,
                t.*
              FROM t_buyers_account b
              LEFT JOIN t_bci t ON b.c_account_no = t.c_account_no
              WHERE b.c_account_no = ?
              ORDER BY t.c_last_updated DESC";

    $stmt = odbc_prepare($conn, $query);
    $result = odbc_execute($stmt, [$account_no]);

    $records = [];
    while ($row = odbc_fetch_array($stmt)) {
        $records[] = [
            "c_lname" => $row['c_b1_last_name'],
            "c_fname" => $row['c_b1_first_name'],
            "c_mname" => $row['c_b1_middle_name'],
            "c_lno" => $row['c_tel_no'],
            "c_mno" => $row['c_mobile_no'],
            "c_email" => $row['c_email'],
            "c_address" => $row['c_address'],
            "c_prov" => $row['c_city_prov'],
            "c_zipcode" => $row['c_zipcode'],
            "c_rep_name" => $row['c_rep_name'],
            "c_rep_landline" => $row['c_rep_landline'],
            "c_rep_mobile" => $row['c_rep_mobile'],
            "c_rep_email" => $row['c_rep_email'],
            "c_last_updated" => $row['c_last_updated']
        ];
    }

    if (!empty($records)) {
        echo json_encode(["records" => $records]);
    } else {
        echo json_encode(["error" => "No account found"]);
    }
}
?>
