<?php
session_start();
include('../config.php');

if (isset($_POST['account_no'])) {
    $account_no = $_POST['account_no'];

    $check_bci = "SELECT * FROM t_bci WHERE c_account_no = ?";
    $stmt_check = odbc_prepare($conn, $check_bci);
    odbc_execute($stmt_check, array($account_no));
    $row = odbc_fetch_array($stmt_check);

    if ($row) {
        $query = "SELECT 
                    b.c_b1_last_name, 
                    b.c_b1_first_name, 
                    b.c_b1_middle_name,
                    t.c_tel_no,
                    t.c_mobile_no,
                    t.c_email,
                    t.c_address,
                    t.c_city_prov,
                    t.c_zipcode,
                    t.c_account_no,
                    t.c_tin,
                    t.c_rep_name,
                    t.c_rep_landline,
                    t.c_rep_mobile,
                    t.c_rep_email,
                    t.c_last_updated
                FROM t_buyers_account b
                LEFT JOIN t_bci t ON b.c_account_no = t.c_account_no
                WHERE b.c_account_no = ?
                ORDER BY t.c_last_updated DESC";
    } else {
        $query = "SELECT 
                    b.c_b1_last_name, 
                    b.c_b1_first_name, 
                    b.c_b1_middle_name,
                    b.c_tel_no,
                    b.c_mobile_no,
                    b.c_email,
                    b.c_address,
                    b.c_city_prov,
                    b.c_zip_code AS c_zipcode,
                    b.c_account_no,
                    b.c_tin,
                    NULL AS c_rep_name,
                    NULL AS c_rep_landline,
                    NULL AS c_rep_mobile,
                    NULL AS c_rep_email,
                    NULL AS c_last_updated
                FROM t_buyers_account b
                WHERE b.c_account_no = ?";
    }

    $stmt = odbc_prepare($conn, $query);
    
    if (!$stmt) {
        echo json_encode(["error" => "Query preparation failed"]);
        exit;
    }

    $result = odbc_execute($stmt, array($account_no));

    if (!$result) {
        echo json_encode(["error" => "Query execution failed"]);
        exit;
    }

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
            "c_last_updated" => $row['c_last_updated'],
            "c_accno" => $row['c_account_no'],
            "c_tin" => $row['c_tin']
        ];
    }

    if (!empty($records)) {
        echo json_encode(["records" => $records]);
    } else {
        echo json_encode(["error" => "No account found"]);
    }
}
?>
