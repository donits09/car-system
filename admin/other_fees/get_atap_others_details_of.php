<?php
include('../../config.php');

if (isset($_POST['c_atap_no']) && !empty($_POST['c_atap_no'])) {
    $c_atap_no = $_POST['c_atap_no'];
    $query = "SELECT 
                a.id, 
                a.c_account_no, 
                a.c_atap_no, 
                a.c_encoded_by,
                a.c_tran_date, 
                a.c_tran_updated, 
                a.atap_remarks, 
                a.status, 
                b.c_name, 
                b.c_phase,
                b.c_block, 
                b.c_lot, 
                a.approval_status,
                SUM(c.c_atap_amount) AS total_amount
            FROM 
                t_atap a
            LEFT JOIN 
                t_other_atap b ON a.c_atap_no = b.c_atap_no 
            LEFT JOIN 
                t_atap_items c ON a.c_atap_no = c.c_atap_no
            WHERE a.c_atap_no = ?
            GROUP BY 
                a.id, 
                a.c_account_no, 
                a.c_atap_no, 
                a.c_encoded_by,
                a.c_tran_date, 
                a.c_tran_updated, 
                a.atap_remarks, 
                a.status, 
                b.c_name, 
                b.c_phase,
                b.c_block, 
                b.c_lot,
                a.approval_status
            ORDER BY 
                a.c_tran_updated DESC";

    $stmt = odbc_prepare($conn, $query);
    odbc_execute($stmt, array($c_atap_no));

    if ($result = odbc_fetch_array($stmt)) {
        $data = [
            'c_account_no' => $result['c_account_no'],
            'c_name' => $result['c_name'],
            'c_phase' => $result['c_phase'],
            'c_block' => $result['c_block'],
            'c_lot' => $result['c_lot'],
            'c_or_amount' => $result['total_amount'],
            'status' => $result['status'],
            'approval_status' => $result['approval_status']
        ];

        echo json_encode(['status' => 'success', 'data' => $data, 'message' => 'ATAP details found']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No ATAP details found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ATAP No.']);
}
?>
