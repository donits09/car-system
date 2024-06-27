<?php
header('Content-Type: application/json');
include('../../config.php');

if (isset($_POST['account_no'])) {
    $account_no = $_POST['account_no'];
    $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
    $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
    if (odbc_execute($buyer_stmt, array($account_no))) {
        $buyer_details = odbc_fetch_array($buyer_stmt);
        if ($buyer_details) {
            echo json_encode(['status' => 'success', 'name' => $buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No buyer found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Query execution failed']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No account number provided']);
}
?>
