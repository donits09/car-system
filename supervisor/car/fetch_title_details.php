<?php
header('Content-Type: application/json');
include('../../config.php');

$c_lid = $_GET['c_lid'];

if (!$c_lid) {
    echo json_encode(array('error' => 'Missing c_lid parameter'));
    exit;
}

$get_lid_details_qry = "SELECT c_doc_tct_jun_2020 FROM t_lot_validation WHERE c_lid = ?";
$lid_stmt = odbc_prepare($conn, $get_lid_details_qry);

if ($lid_stmt === false) {
    echo json_encode(array('error' => 'Failed to prepare SQL statement'));
    exit;
}

if (odbc_execute($lid_stmt, array($c_lid))) {
    $lid_details = odbc_fetch_array($lid_stmt);
    if ($lid_details) {
        echo json_encode($lid_details);
    } else {
        echo json_encode(array('c_doc_tct_jun_2020' => ''));
    }
} else {
    echo json_encode(array('error' => 'Failed to execute SQL statement'));
}

?>
