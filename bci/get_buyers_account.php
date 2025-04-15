<?php
session_start();
include('../config.php');

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (isset($_POST['account_no']) || isset($_POST['location'])) {
    $account_no = trim($_POST['account_no']);
    $location = trim($_POST['location']);

    if (!empty($account_no)) {
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
                    b.c_type,
                    t.c_rep_name,
                    t.c_rep_landline,
                    t.c_rep_mobile,
                    t.c_rep_email,
                    CURRENT_DATE AS c_last_updated 
                FROM t_buyers_account b
                LEFT JOIN (
                    SELECT DISTINCT ON (c_account_no) *
                    FROM t_bci
                    ORDER BY c_account_no, c_last_updated DESC
                ) t ON b.c_account_no = t.c_account_no
                WHERE b.c_account_no = ?";
        $param = [$account_no];

    } elseif (!empty($location)) {
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
                    b.c_type,
                    t.c_rep_name,
                    t.c_rep_landline,
                    t.c_rep_mobile,
                    t.c_rep_email,
                    CURRENT_DATE AS c_last_updated 
                FROM t_buyers_account b
                LEFT JOIN (
                    SELECT DISTINCT ON (c_account_no) *
                    FROM t_bci
                    ORDER BY c_account_no, c_last_updated DESC
                ) t ON b.c_account_no = t.c_account_no
                WHERE LEFT(CAST(b.c_account_no AS VARCHAR), 8) = ?";
        $param = [$location];

    } else {
        echo json_encode(["error" => "No valid input provided"]);
        exit;
    }

    $stmt = odbc_prepare($conn, $query);

    if (!$stmt) {
        echo json_encode(["error" => "Query preparation failed"]);
        exit;
    }

    $result = odbc_execute($stmt, $param);

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
            "c_type" => $row['c_type']
        ];
    }

    if (!empty($records)) {
        echo json_encode(["records" => $records]);
    } else {
        echo json_encode(["error" => "No account found"]);
    }
}
?>
