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
        $query = "
            SELECT
                b.c_b1_last_name,
                b.c_b1_first_name,
                b.c_b1_middle_name,
                b.c_address,
                b.c_city_prov,
                b.c_account_no,
                l.c_name  AS log_name,
                l.c_date  AS log_date,
                l.c_time  AS log_time,
                l.c_module AS log_module,
                l.c_notes AS log_notes
            FROM t_buyers_account b
            LEFT JOIN t_log l
            ON l.c_module IN ('Reservation-Payment', 'Payments', 'Payment Details')
            AND l.c_notes LIKE '%' || CAST(b.c_account_no AS VARCHAR(50)) || '%'
            WHERE b.c_account_no = ?
            ORDER BY b.c_account_no, l.c_date DESC, l.c_time DESC
        ";
        $param = [$account_no];

    } elseif (!empty($location)) {
        $query = "
            SELECT
                b.c_b1_last_name,
                b.c_b1_first_name,
                b.c_b1_middle_name,
                b.c_address,
                b.c_city_prov,
                b.c_account_no,
                l.c_name  AS log_name,
                l.c_date  AS log_date,
                l.c_time  AS log_time,
                l.c_module AS log_module,
                l.c_notes AS log_notes
            FROM t_buyers_account b
            LEFT JOIN t_log l
            ON l.c_module IN ('Reservation-Payment', 'Payments', 'Payment Details')
            AND l.c_notes LIKE '%' || CAST(b.c_account_no AS VARCHAR(50)) || '%'
            WHERE LEFT(CAST(b.c_account_no AS VARCHAR(50)), 8) = ?
            ORDER BY b.c_account_no, l.c_date DESC, l.c_time DESC
        ";
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
            "c_lname"   => $row['c_b1_last_name'],
            "c_fname"   => $row['c_b1_first_name'],
            "c_mname"   => $row['c_b1_middle_name'],
            "c_address" => $row['c_address'],
            "c_prov"    => $row['c_city_prov'],
            "c_accno"   => $row['c_account_no'],
            "log_name"  => $row['log_name'],
            "log_date"  => $row['log_date'],
            "log_time"  => $row['log_time'],
            "log_module"=> $row['log_module'],
            "log_notes" => $row['log_notes'],
        ];
    }

    if (!empty($records)) {
        echo json_encode(["records" => $records]);
    } else {
        echo json_encode(["error" => "No account found"]);
    }
}
?>
