<?php
include('../config.php');

$accountNo = isset($_GET['acc-no']) ? $_GET['acc-no'] : 0;

$data = [];
if ($accountNo > 0) {
    $query = "SELECT * FROM t_car_payment WHERE c_account_no = ?";
    $stmt = odbc_prepare($conn, $query);
    odbc_execute($stmt, array($accountNo));

    while ($row = odbc_fetch_array($stmt)) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
