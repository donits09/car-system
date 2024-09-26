<?php
require_once('../../config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array('status' => '', 'msg' => '', 'err' => '');
    try {
        $c_account_no = !empty($_POST['c_account_no']) ? trim($_POST['c_account_no']) : null;
        $c_car_no = !empty($_POST['c_car_no']) ? trim($_POST['c_car_no']) : null;
        $c_car_no_prev = !empty($_POST['c_car_no_prev']) ? trim($_POST['c_car_no_prev']) : null; 
        $c_car_amount = !empty($_POST['c_car_amount']) ? floatval($_POST['c_car_amount']) : 0;
        $c_mop = !empty($_POST['c_mop']) ? intval($_POST['c_mop']) : null;
        $c_bank_check = isset($_POST['c_bank_check']) ? trim($_POST['c_bank_check']) : null;
        $c_check_no = isset($_POST['c_check_no']) ? trim($_POST['c_check_no']) : null;
        $c_bank_online = isset($_POST['c_bank_online']) ? trim($_POST['c_bank_online']) : null;
        $c_ref_no = isset($_POST['c_ref_no']) ? trim($_POST['c_ref_no']) : null;
        $c_remarks = !empty($_POST['c_remarks']) ? trim($_POST['c_remarks']) : null;
        $c_tran_date = !empty($_POST['c_tran_date']) ? trim($_POST['c_tran_date']) : null;
        $c_car_paydate = !empty($_POST['c_car_paydate']) ? trim($_POST['c_car_paydate']) : null;
        if (empty($c_car_no)) {
            throw new Exception('Car number is required.');
        }
        if (empty($c_car_no_prev)) {
            throw new Exception('Previous car number is required.');
        }
        $c_bank = null;
        if ($c_mop == 2) { 
            $c_bank = $c_bank_check;
        } elseif ($c_mop == 3) { 
            $c_bank = $c_bank_online;
            $c_check_no = $c_ref_no; 
        }
        $update_query = "UPDATE t_car_payment 
                         SET c_account_no = '$c_account_no', 
                             c_car_amount = $c_car_amount, 
                             c_mop = $c_mop, 
                             c_bank = '$c_bank', 
                             c_check_no = '$c_check_no', 
                             c_remarks = '$c_remarks', 
                             c_tran_date = '$c_tran_date', 
                             c_car_paydate = '$c_car_paydate', 
                             c_car_no = '$c_car_no' 
                         WHERE c_car_no = '$c_car_no_prev'"; 
        $result = odbc_exec($conn, $update_query);

        if (!$result) {
            throw new Exception('Error updating car payment.');
        }
        $response['status'] = 'success';
        $response['msg'] = 'Car payment updated successfully.';

    } catch (Exception $e) {
        $response['status'] = 'failed';
        $response['err'] = $e->getMessage();
    }
    echo json_encode($response);
}
?>
