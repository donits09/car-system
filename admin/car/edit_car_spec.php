<?php
session_start();
require_once('../../inc/check_session.php');
check_user_group(1);
$c_encoded_by = '';
include('../../config.php');

if (isset($_GET['car_no']) && !empty($_GET['car_no'])) {
    $totalAmount = 0;
    $carNo = $_GET['car_no'];
    $get_car_query = "SELECT a.c_account_no, a.c_car_no, a.c_car_type, a.c_car_amount, 
                             a.c_car_paydate, a.c_encoded_by, a.c_tran_date, a.c_tran_updated, 
                             a.c_mop, a.c_bank, b.c_name, b.c_phase, b.c_block, b.c_lot, 
                             a.c_check_no, a.c_remarks
                      FROM t_car_payment a
                      LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no 
                      WHERE a.c_car_no = ?";
    $stmt = odbc_prepare($conn, $get_car_query);
    odbc_execute($stmt, array($carNo));
    $carData = [];

    while ($firstEntry = odbc_fetch_array($stmt)) {
        $carData[] = $firstEntry;
    }

    if (!empty($carData)) {
        $firstEntry = $carData[0];
?>
<form id="car-form" method="post" action="">
<div class="container-fluid">
    <div class="callout callout-primary">
        <table class="table table-bordered" style="text-align:left;">
            <tbody>
                <tr>
                    <th style="width: 30%;">Account No.:</th>
                    <td>
                        <input type="text" name="c_account_no" id="c_account_no" value="<?php echo !empty($firstEntry['c_account_no']) ? htmlspecialchars($firstEntry['c_account_no']) : ''; ?>" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th>Car No.:</th>
                    <td>
                        <input type="text" name="c_car_no_prev" id="c_car_no_prev" value="<?php echo htmlspecialchars($firstEntry['c_car_no']); ?>" class="form-control" style="background-color:red;">
                        <input type="text" name="c_car_no" id="c_car_no" value="<?php echo htmlspecialchars($firstEntry['c_car_no']); ?>" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th style="width: 30%;">Name:</th>
                    <td class="text-center">
                    <?php
                        $c_buyer_acc = !empty($firstEntry['c_account_no']) ? $firstEntry['c_account_no'] : '';
                        if (!empty($c_buyer_acc)) {
                            $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
                            $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
                            if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                                $buyer_details = odbc_fetch_array($buyer_stmt);
                                if ($buyer_details) {
                                    echo htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]);
                                } else {
                                    echo "-";
                                }
                            } else {
                                echo "-";
                            }
                        } else {
                            echo htmlspecialchars($firstEntry['c_name']);
                        }
                    ?>
                    </td>
                </tr>
                <tr>
                    <th style="width: 30%;">Location:</th>
                    <td>
                        <?php
                        $c_account_no = $firstEntry['c_account_no'];
                        try {
                            if (!empty($c_account_no)) {
                                $c_phase = substr($c_account_no, 0, 3);
                                $c_block = ltrim(substr($c_account_no, 3, 3), '0');
                                $c_lot = substr($c_account_no, 6, 2);

                                $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                                $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                                if (odbc_execute($phase_stmt, array($c_phase))) {
                                    $phase_details = odbc_fetch_array($phase_stmt);
                                    if ($phase_details) {
                                        echo htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                                    } else {
                                        echo "-----";
                                    }
                                } else {
                                    echo "-----";
                                }
                            } else {
                                echo "-------------";
                            }
                        } catch (Exception $e) {
                            echo "-----";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td><input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo number_format(htmlspecialchars($firstEntry['c_car_amount']), 2); ?>" oninput="validateNumberInputAmt(event)" onclick="clearAmt()" required>
                    <div id="car_amt_error"></div>
                    </td>
                    <th>Mode of Payment</th>
                    <td>
                        <select class="form-control" id="c_mop" name="c_mop" required onchange="handleModeofPaymentChange()">
                            <option value="1" <?php echo ($firstEntry['c_mop'] == 1) ? 'selected' : ''; ?>>Cash</option>
                            <option value="2" <?php echo ($firstEntry['c_mop'] == 2) ? 'selected' : ''; ?>>Check</option>
                            <option value="3" <?php echo ($firstEntry['c_mop'] == 3) ? 'selected' : ''; ?>>Online</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <div class="form-group" id="checkList" style="display: <?php echo ($firstEntry['c_mop'] == 2) ? 'block' : 'none'; ?>;">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="c_bank_check">Check Bank</label>
                                <div class="dropdown">
                                    <select class="form-control" id="c_bank_check" name="c_bank_check">
                                        <?php
                                        $check_type_query = "SELECT DISTINCT c_bank_type, id FROM t_car_check WHERE status = 0 ORDER BY id ASC";
                                        $type_result = odbc_exec($conn, $check_type_query);
                                        while ($row = odbc_fetch_array($type_result)) {
                                            $selected = (isset($firstEntry['c_bank']) && $firstEntry['c_bank'] == $row['c_bank_type']) ? 'selected' : '';
                                            echo "<option value='".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="c_check_no">Check No</label>
                                <input type="text" class="form-control" id="c_check_no" name="c_check_no" value="<?php echo htmlspecialchars($firstEntry['c_check_no']); ?>">
                            </div>
                        </div>
                    </div>
                </tr>
                <tr>
                    <div class="form-group" id="onlineBankList" style="display: <?php echo ($firstEntry['c_mop'] == 3) ? 'block' : 'none'; ?>;">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="c_bank_online">Online Bank</label>
                                <div class="dropdown">
                                    <select class="form-control" id="c_bank_online" name="c_bank_online">
                                        <?php
                                        $online_bank_query = "SELECT DISTINCT c_bank_type, id FROM t_car_online WHERE status = 0 ORDER BY id ASC";
                                        $type_result = odbc_exec($conn, $online_bank_query);
                                        while ($row = odbc_fetch_array($type_result)) {
                                            $selected = (isset($firstEntry['c_bank']) && $firstEntry['c_bank'] == $row['c_bank_type']) ? 'selected' : '';
                                            echo "<option value='".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="c_ref_no">Ref No</label>
                                <input type="text" class="form-control" id="c_ref_no" name="c_ref_no" value="<?php echo htmlspecialchars($firstEntry['c_check_no']); ?>">
                            </div>
                        </div>
                    </div>
                </tr>
                <tr>
                    <th>Transaction Date:</th>
                    <td><input type="date" name="c_tran_date" id="c_tran_date" value="<?php echo htmlspecialchars((new DateTime($firstEntry['c_tran_date']))->format('Y-m-d')); ?>" class="form-control"></td>
                </tr>
                <tr>
                    <th>Remarks:</th>
                    <td><input type="text" name="c_remarks" id="c_remarks" value="<?php echo htmlspecialchars($firstEntry['c_remarks']); ?>" class="form-control" style="max-width: 200px;"></td>
                </tr>
                <tr>
                    <th>Pay Date:</th>
                    <td><input type="date" name="c_car_paydate" id="c_car_paydate" value="<?php echo htmlspecialchars($firstEntry['c_car_paydate']); ?>" class="form-control"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
    } else {
        echo "No data found for the given car number.";
    }
}
?>
<button type="submit" class="btn btn-primary" id="btnsave">Save</button>
</form>
<script>
$('#car-form').submit(function(e) {
    e.preventDefault(); 
    start_loader();
    $('#car-form button[type="submit"]').attr('disabled', true);

    $.ajax({
        url: "save_car_mod.php", 
        data: new FormData($(this)[0]), 
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        dataType: 'json', 
        error: function(err) {
            console.log(err); 
            alert_toast("An error occurred. Please try again.", 'error');
            end_loader(); 
            $('#car-form button[type="submit"]').attr('disabled', false);
        },
        success: function(resp) {
            console.log(resp); 
            if (resp && resp.status === 'success') {
                alert_toast(resp.msg, 'success');
                setTimeout(function() {
                    $('#createCarModal').modal('hide'); 
                    $('body').removeClass('modal-open'); 
                    $('.modal-backdrop').remove(); 

                    if (typeof updateCarList === 'function') {
                        updateCarList(); 
                    }
                }, 1000);
            } else if (resp && resp.status === 'failed') {
                alert_toast("Error: " + resp.err, 'error');
            } else {
                alert_toast("An unexpected error occurred.", 'error');
            }
            end_loader(); 
            $('#car-form button[type="submit"]').attr('disabled', false); 
        }
    });
});
</script>
<script>
    function handleModeofPaymentChange() {
        var mop = document.getElementById('c_mop').value;
        document.getElementById('checkList').style.display = (mop == 2) ? 'block' : 'none';
        document.getElementById('onlineBankList').style.display = (mop == 3) ? 'block' : 'none';
    }
    handleModeofPaymentChange();
</script>
