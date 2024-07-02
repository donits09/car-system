<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(3);

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
<body>
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
        <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo number_format(htmlspecialchars($c_car_amount),2); ?>" oninput="validateNumberInputAmt(event)" required>
        <div id="car_amt_error"></div>
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
        <input type="text" class="form-control" id="c_car_paydate" name="c_car_paydate" value="<?php echo htmlspecialchars($c_car_paydate) ?>" required>
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
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<script src="../../dist/js/all_car_list.js"></script>
</body>