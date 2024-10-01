<?php
header('Content-Type: application/json');
include('../../config.php');

if (isset($_GET['c_atap_no'])) {
    $c_atap_no = $_GET['c_atap_no'];

    $get_atap_query = "SELECT id, c_tran_type, c_atap_amount FROM t_atap_items WHERE c_atap_no = ? and atap_status = 0";
    $stmt = odbc_prepare($conn, $get_atap_query);
    odbc_execute($stmt, array($c_atap_no));

    $options = array();
    while ($row = odbc_fetch_array($stmt)) {
        $tran_type_id = $row['id'];
        $tran_type = htmlspecialchars($row['c_tran_type'], ENT_QUOTES, 'UTF-8');
        
        $tran_amount = number_format($row['c_atap_amount'], 2, '.', '');

        $options[] = array(
            'value' => $tran_type_id,
            'text' => $tran_type, 
            'amount' => $tran_amount, 
        );
    }

    echo json_encode($options);
}
?>
