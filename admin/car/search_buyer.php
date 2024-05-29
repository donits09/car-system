<?php
header('Content-Type: application/json');
include('../../config.php');
$response = array('status' => 'error', 'data' => null);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['acc_no'])) {
        $acc_no = $_GET['acc_no'];
        $get_details_query = "SELECT * FROM t_buyers_account WHERE c_account_no = ?";
        $stmt = odbc_prepare($conn, $get_details_query);
        odbc_execute($stmt, array($acc_no));
        if ($row = odbc_fetch_array($stmt)) {
            $response['status'] = 'success';
            $response['data'] = $row;
        }
    } elseif (isset($_GET['last_name'])) {
        $last_name = $_GET['last_name'];
        // $get_details_query = "SELECT * FROM t_buyers_account WHERE c_b1_last_name = ?";
        $get_details_query = "SELECT * FROM t_buyers_account WHERE LOWER(c_b1_last_name) = LOWER(?)";
        $stmt = odbc_prepare($conn, $get_details_query);
        odbc_execute($stmt, array($last_name));
        
        $rows = [];
        while ($row = odbc_fetch_array($stmt)) {
            $rows[] = $row;
        }
        if (!empty($rows)) {
            $response['status'] = 'success';
            $response['data'] = $rows;
        }
    } elseif (isset($_GET['loc'])) {
        $loc = $_GET['loc'];

        $get_details_query = "SELECT * FROM t_buyers_account WHERE c_account_no::text ILIKE ?";
        $stmt = odbc_prepare($conn, $get_details_query);

        odbc_execute($stmt, array($loc . '%'));
      
        $rows = [];
        while ($row = odbc_fetch_array($stmt)) {
            $rows[] = $row;
        }
        
        if (!empty($rows)) {
            $response['status'] = 'success';
            $response['data'] = $rows;
        }
    }
}

echo json_encode($response);
?>
