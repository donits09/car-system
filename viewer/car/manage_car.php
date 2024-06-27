<?php 
session_start();

/* if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != 4) {
    require_once('../logout.php');
    exit();
} */

require_once('../../inc/check_session.php');
check_user_group(4);

include('../../config.php');

$c_account_no = null;
$c_car_type = '';
$c_car_amount = 0;
$c_car_no = '';
$c_car_paydate = date('Y-m-d');
$c_encoded_by = '';
$c_tran_date = date('Y-m-d H:i:s');
$c_mop = '';

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $get_car_query = "SELECT * FROM t_car_payment WHERE id = ?";
    $accountId = $_GET['id'];
    $stmt = odbc_prepare($conn, $get_car_query);
    odbc_execute($stmt, array($accountId));

    if ($result = odbc_fetch_array($stmt)) {
        $c_account_no = $result["c_account_no"];
        $c_car_type = $result["c_car_type"];
        $c_car_amount = $result["c_car_amount"];
        $c_car_no = $result["c_car_no"];
        $c_car_paydate = $result["c_car_paydate"];
        $c_encoded_by = $result["c_encoded_by"];
        $c_mop = $result["c_mop"];
    }
} else if (isset($_GET['c_account_no']) && $_GET['c_account_no'] > 0) {
    $c_account_no = $_GET['c_account_no'];
}
?>
<style>
.bold-text {
    padding: 5px;
    font-size: 11px;
    font-style: italic;
}
</style>
<link rel="stylesheet" href="../../dist/css/manage_car.css">
<form id="car-form" method="post" action="">
    <?php
    $readonly = isset($c_account_no) && !empty($c_account_no) ? 'readonly' : '';
    ?>
    <input type="hidden" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="form-group">
        <label for="account_no">Account No.</label>
        <input type="text" class="form-control" id="c_account_no" name="c_account_no" value="<?php echo htmlspecialchars($c_account_no) ?>" <?php echo $readonly; ?> oninput="validateNumberInput(event)" required>
    </div>
    <div class="form-group">
        <label for="car_no">CAR No.</label>
        <input type="number" class="form-control" id="c_car_no" name="c_car_no" value="<?php echo htmlspecialchars($c_car_no); ?>" maxlength="6" minlength="6" pattern="\d{6}" oninput="validateNumberInput(event)" required>
        <div id="car_no_error" class="text-danger"></div>
    </div>
    <div class="form-group">
        <label for="c_car_type">Payment Type</label>
        <div class="dropdown">
            <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_car_type" name="c_car_type" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
            <div class="dropdown-menu w-100" id="comboBoxMenu">
                <?php
                $car_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type WHERE status = 0 ORDER BY id ASC";
                $type_result = odbc_exec($conn, $car_type_query);
                while ($row = odbc_fetch_array($type_result)) {
                    $selected = (isset($c_car_type) && $c_car_type == $row['c_payment_type']) ? 'active' : '';
                    echo "<a class='dropdown-item $selected' href='#' data-value='".htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8')."'>".htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8')."</a>";
                }
                ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="buyer_name" name="buyer_name" oninput="validateAlphaNumericInput(event)" readonly>
    </div>
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo number_format(htmlspecialchars($c_car_amount),2); ?>" oninput="validateNumberInputAmt(event)" required>
        <div id="car_amt_error" class="text-danger"></div>
    </div>

    <div class="form-group">
        <label for="c_mop">Mode of Payment</label>
        <select class="form-control" id="c_mop" name="c_mop" required>
            <option value="1" <?php echo ($c_mop == 1) ? 'selected' : ''; ?>>Cash</option>
            <option value="2" <?php echo ($c_mop == 2) ? 'selected' : ''; ?>>Check</option>
        </select>
    </div>
    <div class="form-group">
        <label for="pay_date">Pay Date</label>
        <input type="date" class="form-control" id="c_car_paydate" name="c_car_paydate" value="<?php echo htmlspecialchars($c_car_paydate) ?>" required>
    </div>
    <div class="form-group">
        <label for="encoder">Encoded by</label>
        <input type="text" class="hidden_fields" id="c_encoded_by" name="c_encoded_by" value="<?php echo  $_SESSION['username'] ?>" readonly>
        <?php
        $c_encoded_by = $_SESSION['username'];
        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
        $results = odbc_exec($conn, $get_encoder_details_qry);

        if ($encoder = odbc_fetch_array($results)) {
            $realname = $encoder["c_realname"];
        }
        ?>
        <input type="text" class="form-control" value="<?php echo $realname ?>" readonly>
    </div>
    <div class="form-group hidden_fields">
        <label for="encoder">Transaction date</label>
        <input type="text" class="form-control" id="c_tran_date" name="c_tran_date" value="<?php echo  htmlspecialchars($c_tran_date) ?>" readonly>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<script src="../../dist/js/manage_car.js"></script>
<script>
$(document).ready(function() {
    $('#car-form').submit(function(e) {
        e.preventDefault();

        const buyerName = $('#buyer_name').val();
        if (!buyerName || buyerName === 'Unknown') {
            alert('Name field is required.');
            return;
        }

        // if (confirm("Are you sure you want to save this car payment?")) {
            var _this = $(this);

            start_loader();

            $.ajax({
                url: "../../classes/Master.php?f=save_car_payment",
                data: new FormData(_this[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                dataType: 'json',
                error: function(err) {
                    console.log(err);
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                },
                success: function(resp) {
                    console.log(resp); 
                    if (resp && resp.status === 'success') {
                        alert_toast(resp.msg, 'success');
                       setTimeout(function() {
                          updateCarList();
                        }, 2000);
                    } else if (resp && resp.status === 'failed' && resp.err) {
                        alert_toast("An error occurred: " + resp.err, 'error');
                    } else {
                        alert_toast("An unexpected error occurred", 'error');
                    }
                    end_loader();
                }
            });
       // }
    });

});

</script>
<script>
$(document).ready(function() {
    function fetchBuyerDetails(accountNo) {
        const buyerNameField = $('#buyer_name');

        if (accountNo.length > 0) {
            $.ajax({
                type: 'POST',
                url: '../../admin/car/get_buyer_details.php',
                data: { account_no: accountNo },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        buyerNameField.val(response.name);
                        buyerNameField.removeAttr('required');
                    } else {
                        buyerNameField.val('Unknown');
                        buyerNameField.attr('required', 'required');
                    }
                }
            });
        } else {
            buyerNameField.val('');
            buyerNameField.attr('required', 'required');
        }
    }

    const accountNo = $('#c_account_no').val();
    fetchBuyerDetails(accountNo);

    $('#c_account_no').on('input', function() {
        const accountNo = $(this).val();
        fetchBuyerDetails(accountNo);
    });

    $('#c_car_no').on('input', function() {
        const carNo = $(this).val();
        
        if (carNo.length < 6) {
            $('#car_no_error').text('CAR No. must be 6 digits.').addClass('bold-text');
            $('#car-form button[type="submit"]').attr('disabled', true);
        } else {
            $.ajax({
                type: 'POST',
                url: '../../admin/car/check_car_no.php',
                data: { car_no: carNo },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        $('#car_no_error').text('CAR No. already exists.').addClass('bold-text');
                        $('#car-form button[type="submit"]').attr('disabled', true);
                    } else {
                        $('#car_no_error').text('').removeClass('bold-text');
                        if (carAmount === 0) {
                            $('#car-form button[type="submit"]').attr('disabled', true);
                        } else {
                            $('#car-form button[type="submit"]').attr('disabled', false);
                        }
                    }
                }
            });
        }

    });
    $('#c_car_amount').on('input', function() {
        const carAmount = parseInt($('#c_car_amount').val());
        if (carAmount === 0) {
            $('#car-form button[type="submit"]').attr('disabled', true);
        }else{
            $('#car-form button[type="submit"]').attr('disabled', false);
        }
    });
    
    $('#car-form').on('submit', function(e) {
        if ($('#car_no_error').text().length > 0) {
            e.preventDefault();
        }
    });
});
</script>