<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(2);

include('../../config.php');

$c_account_no = null;
$c_car_type = '';
$c_car_amount = 0;
$c_car_no = '';
$c_car_paydate = date('Y-m-d');
$c_encoded_by = '';
$c_tran_date = date('Y-m-d H:i:s');
$c_mop = '';
$c_bank = '';

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
        $c_bank = $result["c_bank"];
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
<body>
<form id="car-form" method="post" action="">
    <?php
    $readonly = isset($c_account_no) && !empty($c_account_no) ? 'readonly' : '';
    ?>
    <input type="hidden" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label for="c_atap_no">ATAP No.</label>
                <input type="text" class="form-control" id="c_atap_no" name="c_atap_no">
            </div>
        </div>
        <div class="col-sm-4" style="margin-top: 25px;">
            <a id="get_atap" class="btn btn-flat btn-primary" style="width: 100%; color: white;">
                <span class="fa fa-edit"></span> Get ATAP
            </a>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <label for="account_no">Account No.</label>
        <input type="text" class="form-control" id="c_account_no" name="c_account_no" value="<?php echo htmlspecialchars($c_account_no) ?>" <?php echo $readonly; ?> oninput="validateNumberInput(event)" required>
    </div>
    <div class="form-group">
        <label for="car_no">CAR No.</label>
        <input type="number" class="form-control" id="c_car_no" name="c_car_no" value="<?php echo htmlspecialchars($c_car_no); ?>" maxlength="6" minlength="6" pattern="\d{6}" oninput="validateNumberInput(event)" required>
        <div id="car_no_error"></div>
    </div>
    <div class="form-group">
        <label for="c_car_type">Payment Type</label>
        <div class="dropdown">
            <input type="text" class="form-control" id="c_car_type" name="c_car_type" oninput="validateAlphaNumericInput(event)" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
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
        <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo number_format(htmlspecialchars($c_car_amount),2); ?>" oninput="validateNumberInputAmt(event)" onclick="clearAmt()" required>
        <div id="car_amt_error"></div>
    </div>
    <div class="form-group">
        <label for="c_mop">Mode of Payment</label>
        <select class="form-control" id="c_mop" name="c_mop" required onchange="toggleCheckDropdown()">
            <option value="1" <?php echo ($c_mop == 1) ? 'selected' : ''; ?>>Cash</option>
            <option value="2" <?php echo ($c_mop == 2) ? 'selected' : ''; ?>>Check</option>
            <option value="3" <?php echo ($c_mop == 3) ? 'selected' : ''; ?>>Online</option>
        </select>
    </div>  

    <div class="form-group" id="checkList" style="display: <?php echo ($c_mop == 2) ? 'block' : 'none'; ?>;">
        <label for="c_bank_check">Select Check Bank</label>
        <div class="dropdown">
            <select class="form-control" id="c_bank_check" name="c_bank_check" required>
                <?php
                $check_type_query = "SELECT DISTINCT c_bank_type, id FROM t_car_check WHERE status = 0 ORDER BY id ASC";
                $type_result = odbc_exec($conn, $check_type_query);
                while ($row = odbc_fetch_array($type_result)) {
                    $selected = (isset($c_bank) && $c_bank == $row['c_bank_type']) ? 'selected' : '';
                    echo "<option value='".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="form-group" id="onlineBankList" style="display: <?php echo ($c_mop == 3) ? 'block' : 'none'; ?>;">
        <label for="c_bank_online">Select Online Bank</label>
        <div class="dropdown">
            <select class="form-control" id="c_bank_online" name="c_bank_online" required>
                <?php
                $online_bank_query = "SELECT DISTINCT c_bank_type, id FROM t_car_online WHERE status = 0 ORDER BY id ASC";
                $type_result = odbc_exec($conn, $online_bank_query);
                while ($row = odbc_fetch_array($type_result)) {
                    $selected = (isset($c_bank) && $c_bank == $row['c_bank_type']) ? 'selected' : '';
                    echo "<option value='".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="pay_date">Pay Date</label>
        <input type="date" class="form-control" id="c_car_paydate" name="c_car_paydate" value="<?php echo htmlspecialchars($c_car_paydate) ?>" min="1990-01-01" max="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <div class="form-group">
        <label for="encoder">Encoded by</label>
        <input type="text" class="hidden_fields" id="c_encoded_by" name="c_encoded_by" value="<?php echo  $_SESSION['username'] ?>" readonly>
        <?php
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            $c_encoded_by == $c_encoded_by;
        }else{
            $c_encoded_by = $_SESSION['username'];
        }
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
    <button type="submit" class="btn btn-primary" id="btnsave">Save</button>
</form>
<script src="../../dist/js/all_car_list.js"></script>
<script>
 $(document).ready(function() {
    $('#get_atap').on('click', function() {
        const atapNo = $('#c_atap_no').val();
        
        if (atapNo.length > 0) {
            fetchAtapDetails(atapNo);
        } else {
            alert('Please enter an ATAP No. first.');
        }
    });

    function fetchAtapDetails(atapNo) {
        $.ajax({
            type: 'POST',
            url: '../atap/get_atap_details.php',
            data: { c_atap_no: atapNo },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    if (response.data && response.data.c_account_no) {
                        if (response.data.status === '1') {
                            alert("This ATAP has already been PAID.");
                        } else if (response.data.status === '2') {
                            alert('This ATAP has already been CANCELLED');
                        } else {
                            populateForm(response.data);
                            fetchBuyerDetails(response.data.c_account_no);
                        }
                    } else {
                        alert('No account number found for the given ATAP No.');
                        clearTxt();
                    }
                } else {
                    alert('No ATAP details found for the given ATAP No.');
                    clearTxt();
                }
            },
            error: function() {
                alert('An error occurred while fetching ATAP details.');
            }
        });
    }

    function clearTxt(){
        const atapNoField = $('#c_atap_no');
        // const buyerNameField = $('#buyer_name');
        const amountField = $('#c_car_amount');
        // const accField = $('#c_account_no');
        const statusField = $('#status');

        atapNoField.val('');
        // buyerNameField.val('');
        amountField.val('');
        // accField.val('');
        statusField.val('');

    }

    function populateForm(data) {
        const buyerNameField = $('#buyer_name');
        const amountField = $('#c_car_amount');
        const accField = $('#c_account_no');
        const statusField = $('#status');
     
        if (data.status === '1') {
            statusField.val('PAID');
        } else if (data.status === '2') {
            statusField.val('CANCELLED');
        } else {
            statusField.val('PENDING');
        }

        const formattedAmount = parseFloat(data.c_car_amount).toFixed(2);

        buyerNameField.val(data.c_name).addClass('glow-effect');
        amountField.val(formattedAmount).addClass('glow-effect');
        accField.val(data.c_account_no).addClass('glow-effect');
        statusField.addClass('glow-effect');

        setTimeout(function() {
            buyerNameField.removeClass('glow-effect');
            amountField.removeClass('glow-effect');
            accField.removeClass('glow-effect');
            statusField.removeClass('glow-effect');
        }, 1000);

        $('#c_account_no').trigger('input');
    }


    $('#c_account_no').on('input', function() {
        const accountNo = $(this).val();
        fetchBuyerDetails(accountNo);
    });

    function fetchBuyerDetails(accountNo) {
        const buyerNameField = $('#buyer_name');

        if (accountNo.length > 0) {
            $.ajax({
                type: 'POST',
                url: '../../cashier/car/get_buyer_details.php',
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
                },
                error: function() {
                    buyerNameField.val('Unknown');
                    buyerNameField.attr('required', 'required');
                    alert('An error occurred while fetching buyer details.');
                }
            });
        } else {
            buyerNameField.val('');
            buyerNameField.attr('required', 'required');
        }
    }
});

</script>
</body>