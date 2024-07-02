<?php
function update_e_status() {
    global $conn;

    $previous_date = date('Y-m-d', strtotime('-1 day'));
    echo "Previous date: " . $previous_date . "<br>"; 

    $total_cash = 0;
    $total_check = 0;
    $total = 0;

    $car_cash_query = "SELECT SUM(c_car_amount) AS cash_total FROM t_car_payment WHERE c_mop = 1 AND status = 0 AND DATE(c_tran_date) = ?";
    $stmt_cash = odbc_prepare($conn, $car_cash_query);
    if ($stmt_cash && odbc_execute($stmt_cash, array($previous_date))) {
        $row_cash = odbc_fetch_array($stmt_cash);
        if ($row_cash) {
            $total_cash = $row_cash['cash_total'];
        }
    }

    $car_check_query = "SELECT SUM(c_car_amount) AS check_total FROM t_car_payment WHERE c_mop = 2 AND status = 0 AND DATE(c_tran_date) = ?";
    $stmt_check = odbc_prepare($conn, $car_check_query);
    if ($stmt_check && odbc_execute($stmt_check, array($previous_date))) {
        $row_check = odbc_fetch_array($stmt_check);
        if ($row_check) {
            $total_check = $row_check['check_total'];
        }
    }

    $total = $total_cash + $total_check;

    $insert_query = "INSERT INTO t_summary_reports (tran_date, total_cash, total_check, total, status) VALUES (?, ?, ?, ?, 1)";
    $stmt_insert = odbc_prepare($conn, $insert_query);
    $insert_success = odbc_execute($stmt_insert, array($previous_date, $total_cash, $total_check, $total));

    $update_query = "UPDATE t_car_payment SET e_status = 1 WHERE DATE(c_tran_date) = ?";
    $stmt_update = odbc_prepare($conn, $update_query);
    $update_success = odbc_execute($stmt_update, array($previous_date));

    if ($insert_success && $update_success) {
        return ['status' => 'success', 'msg' => "e_status updated successfully for date: $previous_date."];
    } else {
        return ['status' => 'failed', 'err' => odbc_errormsg($conn)];
    }
}
?>



<?php
require_once('auto_locker.php'); 

$lock_file = 'auto_locker.txt'; 

$current_time = date('H:i');
$current_date = date('Y-m-d');

if ($current_time >= '10:00') {
    if (file_exists($lock_file)) {
        $last_run_date = file_get_contents($lock_file);
    } else {
        $last_run_date = '';
    }

    if ($last_run_date !== $current_date) {
        $result = update_e_status();
        if ($result['status'] === 'success') {
            file_put_contents($lock_file, $current_date);
            echo "<script>alert('Summary report locked successfully for the previous day.');</script>";
        } else {
            echo "<script>alert('Error: " . $result['err'] . "');</script>";
        }
    } else {
        echo "<script>console.log('Function already executed today.');</script>";
    }
} else {
    echo "<script>console.log('Current time is before 10:00 AM.');</script>";
}
?>