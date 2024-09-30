<?php
header('Content-Type: application/json');
include('../../config.php');
$data = json_decode(file_get_contents('php://input'), true);

$c_tran_type = pg_escape_string($data['c_tran_type']);
$c_tran_type_single = pg_escape_string($data['c_tran_type_single']);
$c_car_type = pg_escape_string($data['c_car_type']);

// Prepare an array of the types to check
$transaction_types = array_filter([$c_tran_type, $c_tran_type_single, $c_car_type]);

foreach ($transaction_types as $tran_type) {
    // Query to check if transaction type exists in t_car_type
    $check_type_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE c_payment_type = '$tran_type'";
    $check_type_result = odbc_exec($conn, $check_type_query);
    $type_exists = odbc_fetch_array($check_type_result)['count'];

    if ($type_exists == 0) {
        $resp['status'] = 'failed';
        $resp['msg'] = "Transaction type '$tran_type' does not exist.";
        echo json_encode($resp);
        exit; // Ensure we exit to stop further processing
    }
}

// If all transaction types exist
$resp['status'] = 'success';
echo json_encode($resp);
?>
