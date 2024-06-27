<?php
header('Content-Type: application/json');
include('../../config.php');
$phase = $_GET['phase'];

$get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
$phase_stmt = odbc_prepare($conn, $get_phase_details_qry);


if (odbc_execute($phase_stmt, array($phase))) {
    $phase_details = odbc_fetch_array($phase_stmt);
    if ($phase_details) {
     
        echo json_encode($phase_details);
    } else {
        echo json_encode(array('c_acronym' => ''));
    }
} else {
    echo json_encode(array('c_acronym' => ''));
}
?>
