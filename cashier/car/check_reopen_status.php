<?php
header('Content-Type: application/json');
include('../../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accountNo = $_POST['account_no'];

    $query = "SELECT c_reopen FROM t_buyers_account WHERE c_account_no = ?";
    $stmt = odbc_prepare($conn, $query);
    
    if (odbc_execute($stmt, array($accountNo))) {
        $result = odbc_fetch_array($stmt);

        if ($result && $result['c_reopen'] == 1) {
            echo json_encode("1");  
        } else {
            echo json_encode("0");  
        }
    } else {
        echo json_encode("0");  
    }
} else {
    echo json_encode("0"); 
}
?>
