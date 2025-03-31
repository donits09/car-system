<?php 
    session_start();
    
    require_once('../../inc/check_session.php');
    check_user_group(2);

    include('../../config.php');

    $c_name = '';
    $c_loc = '';
    $c_or_type = '';
    $c_or_amount = 0;
    $c_or_no = '';
    $c_or_paydate = date('Y-m-d');
    $c_encoded_by = '';
    $c_tran_date = date('Y-m-d H:i:s');
    $c_mop_or = '0';
    $c_bank = '';
    $c_lot = '';
    $c_block = '';
    $c_check_no = '';
    $c_remarks = '';
    $c_vat_sales = 0;
    $c_vat_amount = 0;
    $c_vat_selected = '';
    $c_ewt = 0;
    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_or_query = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                    a.c_or_paydate,a.c_or_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop,a.c_bank, b.c_name, b.c_phase,
                    b.c_block, b.c_lot, a.c_check_no, a.c_remarks, a.c_vat_sales, a.c_vat_amount, a.c_vat_selected, a.c_ewt
                        FROM t_or_payment a
                        LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no WHERE a.id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_or_query);
        odbc_execute($stmt, array($accountId));

        if ($result = odbc_fetch_array($stmt)) {
            $c_name = $result["c_name"];
            $c_phase = $result["c_phase"];
            $c_block = $result["c_block"];
            $c_lot = $result["c_lot"];
            $c_or_type = $result["c_or_type"];
            $c_or_amount = $result["c_or_amount"];
            $c_or_no = $result["c_or_no"];
            $c_or_paydate = $result["c_or_paydate"];
            $c_encoded_by = $result["c_encoded_by"];
            $c_mop_or = $result["c_mop"];
            $c_bank = $result["c_bank"];
            $c_check_no = $result["c_check_no"];
            $c_remarks = $result["c_remarks"];
            $c_vat_sales = $result["c_vat_sales"];
            $c_vat_amount = $result["c_vat_amount"];
            $c_vat_selected = $result["c_vat_selected"];
            $c_ewt = $result["c_ewt"];
        }
    } else if ($c_vat_selected === '' || $c_vat_selected === null) {
        $c_vat_selected = '1'; 
    }
?>
<style>
.bold-text {
    padding: 5px;
    font-size: 11px;
    font-style: italic;
}
.combo-box-menu {
    max-height: 200px; 
    overflow-y: auto;
}
.disabled {
    pointer-events: none;
    opacity: 0;
}
.lbl_rem{
    float:left;
    margin-right:5px;
}
.remarks_ref{
    color:red;
    font-style: italic;
    float:left;
}
#vat_fields input[type="radio"] {
    vertical-align: middle;
    margin-bottom: 5px;
}

#vat_fields label {
    margin-right: 10px;
    vertical-align: middle;
}

</style>
<link rel="stylesheet" href="../../dist/css/manage_car.css">
<body>
<form id="other-or-form">
    <input type="hidden" id="id" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="c_atap_no_or">ATAP No.</label>
                <input type="number" class="form-control" id="c_atap_no_or" name="c_atap_no_or" oninput="checkAtapNo()">
            </div>
        </div>
        <div class="col-sm-3" style="margin-top: 25px; padding-right: 5px;">
            <a id="get_atap_or" class="btn btn-flat btn-secondary" style="width: 100%; color: white;" onclick="toggleCarTypeOR(); disableAtapNo()">
                <span class="fa fa-edit"></span> Get ATAP
            </a>
        </div>
        <div class="col-sm-3" style="margin-top: 25px; padding-left: 5px;">
            <a id="refresh_btn" class="btn btn-flat btn-secondary" style="width: 100%; color: white;" onclick="enableAtapNo()">
                <span class="fa fa-refresh"></span> Refresh
            </a>
        </div>
    </div>
    <script>
    function disableAtapNo() {
        document.getElementById('c_atap_no_or').readOnly = true;
        toggleCarTypeOR(); 
    }

    function enableAtapNo() {
        document.getElementById('c_atap_no_or').readOnly = false;
        const atapNoField = $('#c_atap_no_or');
        const amountField = $('#c_or_amount');
        const statusField = $('#status');
        const remarksField = $('#current_remarks');
        var comboBoxMenu = document.getElementById('comboBoxMenu_or');
        var orTypeInput = document.getElementById('c_or_type');
        var getAtapButton = document.getElementById('get_atap_or');
        remarksField.val('');
        atapNoField.val('');
        amountField.val('');
        statusField.val('');

        comboBoxMenu.classList.remove('disabled');
        orTypeInput.readOnly = false;

        getAtapButton.style.backgroundColor = '';
        getAtapButton.style.borderColor = '';
        getAtapButton.innerHTML = '<span class="fa fa-edit"></span> Get ATAP';
        toggleCarTypeOR(); 
    }
    </script>
    <div class="form-group" id="tran_type_container_or" style="display: none;">
        <label for="c_tran_type_or">Transaction Type/s from client's ATAP</label>
        <div id="tran_type_dropdown" class="dropdown" style="width:100%;">
            <select class="form-control" id="c_tran_type_or" name="c_tran_type_or">
            </select>
        </div>
        <input type="text" class="form-control" id="c_tran_type_single_or" name="c_tran_type_single_or" style="display: none;" readonly>
    </div>
    <div class="form-group">
        <div class="dropdown" id="or_type_container">
            <label for="c_or_type">Transaction Type</label>
           
            <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_or_type" name="c_or_type" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_or_type) ? htmlspecialchars($c_or_type, ENT_QUOTES, 'UTF-8') : ''; ?>">
            <div class="dropdown-menu w-100" id="comboBoxMenu_or" style="max-height: 200px; overflow-y: auto;">
                <?php
                $or_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type WHERE status = 0 AND payment_status = 'O' ORDER BY c_payment_type ASC";
                $type_result = odbc_exec($conn, $or_type_query);

                //                 $car_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type WHERE status = 0 AND payment_status = 'O' ORDER BY c_payment_type ASC";
                //                 $type_result = odbc_exec($conn, $car_type_query);

                while ($row = odbc_fetch_array($type_result)) {
                    echo "<a class='dropdown-item' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
                }
                ?>
            </div>
        </div>
    </div>
    <script>
    function checkAtapNo() {
        var atapNo = document.getElementById('c_atap_no_or').value;
        var comboBoxMenu = document.getElementById('comboBoxMenu_or');
        var orTypeInput = document.getElementById('c_or_type');
        var getAtapButton = document.getElementById('get_atap_or');

        if (atapNo !== '') {
            comboBoxMenu.classList.add('disabled');
            orTypeInput.readOnly = true;
            getAtapButton.style.backgroundColor = 'green';
            getAtapButton.style.borderColor = 'green';
            // getAtapButton.innerHTML = '<span class="fa fa-edit"></span> Click Me!';
        } else {
            comboBoxMenu.classList.remove('disabled');
            orTypeInput.readOnly = false;
            getAtapButton.style.backgroundColor = '';
            getAtapButton.style.borderColor = '';
            getAtapButton.innerHTML = '<span class="fa fa-edit"></span> Get ATAP';
        }
    }
    </script>
    <script>
        $(document).ready(function () {
            $('#c_or_type').on('input', function () {
                var input = $(this).val().toLowerCase();
                var hasVisibleOptions = false;
                $('#comboBoxMenu_or .dropdown-item').each(function () {
                    if ($(this).text().toLowerCase().startsWith(input)) {
                        $(this).show();
                        hasVisibleOptions = true;
                    } else {
                        $(this).hide();
                    }
                });

                if (hasVisibleOptions) {
                    $('#comboBoxMenu_or').show();
                } else {
                    $('#comboBoxMenu_or').hide();
                }
            });

            $('#comboBoxMenu_or').on('click', '.dropdown-item', function () {
                var selectedText = $(this).data('value');
                $('#c_or_type').val(selectedText);
                $('#comboBoxMenu_or').hide();
            });

            $('#c_or_type').on('focus click', function () {
                $('#comboBoxMenu_or').show();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('#comboBoxMenu_or').hide();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function updateAtapId(selectedId) {
                $('#atap_id').val(selectedId);
            }
            function updateAtapVal(selectedValue) {
                $('#atap_val_or').val(selectedValue);
            }
            $('.dropdown-menu a.dropdown-item').on('click', function(e) {
                //e.preventDefault();

                var selectedValue = $(this).data('value'); 
                var selectedId = $(this).data('id'); 

                updateAtapId(selectedId); 
                updateAtapVal(selectedValue); 

                $('#dropdownMenuButton').text(selectedValue);
                $('#c_or_type').val(selectedValue);
            });

            var initialSelectedValue = $('#c_or_type').val();
            if (initialSelectedValue) {
                updateAtapVal(initialSelectedValue);
            }
            $('#c_tran_type').change(function() {
                var selectedOption = $(this).find(':selected');
                var selectedValue = selectedOption.val();
                var amount = selectedOption.data('amount'); 
                var atap_val = selectedOption.text(); 

                updateAtapId(selectedValue);
                updateAtapAmount(amount); 
                updateAtapVal(atap_val); 
            });
        });
    </script>
    <script>
    function toggleCarTypeOR() {
        var atapNo = document.getElementById('c_atap_no_or').value;
        var orTypeContainer = document.getElementById('or_type_container');
        var tranTypeContainer =document.getElementById('tran_type_container_or');

        if (atapNo.trim() === '') {
            orTypeContainer.style.display = 'block';
            tranTypeContainer.style.display = 'none';
        } else {
            tranTypeContainer.style.display = 'block';
            orTypeContainer.style.display = 'none';  
        }
    }
    </script>
    <input type="hidden" class="form-control" id="atap_id_or" name="atap_id_or" readonly>
    <input type="hidden" class="form-control" id="atap_val_or" name="atap_val_or" readonly>
    <hr>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="c_name" name="c_name" value="<?php echo htmlspecialchars($c_name); ?>" oninput="validateAlphaNumericInput(event)" required>
    </div>
    <?php 
        if (!isset($_GET['id']) || $_GET['id'] == null): ?>
            <div class="form-group">
                <label for="current_remarks" class="form-label lbl_rem">
                    ATAP Remarks 
                </label><div class="remarks_ref">(These remarks are for your reference only.)</div>
                <textarea class="form-control txt" rows="2" cols="50" id="current_remarks" name="current_remarks" readOnly><?php echo htmlspecialchars($c_remarks) ?></textarea>
            </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="new_remarks" class="form-label">
            Remarks 
        </label>
        <textarea class="form-control txt" rows="1" cols="50" id="c_remarks" name="c_remarks"><?php echo htmlspecialchars($c_remarks) ?></textarea>
    </div>
    <div class="row align-items-end">
        <div class="col-md-6 form-group">
            <label for="c_phase" class="control-label">Phase</label>
            <select name="c_phase" id="c_phase" class="custom-select form-control" autocomplete="off">
                <option value="" selected>--SELECT--</option>
                <?php
                $sql = "SELECT * FROM t_projects ORDER BY c_acronym";
                $results = odbc_exec($conn, $sql);
                while ($row = odbc_fetch_array($results)) {
                    $selected = ''; 
                    if ($row['c_code'] == $c_phase) {
                        $selected = 'selected'; 
                    }
                    echo '<option value="' . $row['c_code'] . '" ' . $selected . '>' . $row['c_acronym'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label for="c_block" class="control-label">Block</label>
            <input type="number" id="c_block" name="c_block" class="form-control" value="<?php echo htmlspecialchars($c_block); ?>" oninput="validateNumberInput(event)">
        </div>
        <div class="col-md-3 form-group">
            <label for="c_lot" class="control-label">Lot</label>
            <input type="number" id="c_lot" name="c_lot" class="form-control" value="<?php echo htmlspecialchars($c_lot); ?>" oninput="validateNumberInput(event)">
        </div>
    </div>

    <div class="form-group">
        <label for="or_no">OR No.</label>
        <input type="number" class="form-control" id="c_or_no" name="c_or_no" value="<?php echo htmlspecialchars($c_or_no); ?>" maxlength="6" minlength="6" pattern="\d{6}" oninput="validateNumberInput(event)" required>
        <div id="or_no_error"></div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.dropdown-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    var value = this.getAttribute('data-value');
                    document.getElementById('c_or_type').value = value;
                    document.getElementById('comboBoxMenu_or').style.display = 'none';
                });
            });

            document.getElementById('c_or_type').addEventListener('focus', function() {
                document.getElementById('comboBoxMenu_or').style.display = 'block';
            });

            document.getElementById('c_or_type').addEventListener('blur', function() {
                setTimeout(function() {
                    document.getElementById('comboBoxMenu_or').style.display = 'none';
                }, 200);
            });
        });

        function validateForm() {
            var carTypeInput = document.getElementById('c_or_type');
            if (carTypeInput.value.trim() === '') {
                carTypeInput.setCustomValidity('Please select a payment type');
                carTypeInput.reportValidity();
                return false;
            } else {
                carTypeInput.setCustomValidity('');
            }
            return true;
        }

        var form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
    </script>

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label for="amount">Amount</label>
                <input type="text" class="form-control" id="c_or_amount" name="c_or_amount" value="<?php echo number_format(htmlspecialchars($c_or_amount),2); ?>" oninput="validateNumberInputAmt(event); computeVAT();" onclick="clearAmt()" required>
                <div id="or_amt_error"></div>
            </div>
            <div class="col-md-6">
                <label for="c_mop_or">Mode of Payment</label>
                <select class="form-control" id="c_mop_or" name="c_mop_or" required onchange="toggleCheckDropdown()">
                    <option value="1" <?php echo ($c_mop_or == 1) ? 'selected' : ''; ?>>Cash</option>
                    <option value="2" <?php echo ($c_mop_or == 2) ? 'selected' : ''; ?>>Check</option>
                    <option value="3" <?php echo ($c_mop_or == 3) ? 'selected' : ''; ?>>Online</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group" id="checkListOR" style="display: <?php echo ($c_mop_or == 2) ? 'block' : 'none'; ?>;">
        <div class="row">
            <div class="col-md-6">      
                <label for="c_bank_check_or">Check Bank</label>
                <div class="dropdown">
                    <select class="form-control" id="c_bank_check_or" name="c_bank_check_or">
                        <option value="" selected disabled>Select a bank</option>
                        <?php
                        $check_type_query = "SELECT DISTINCT c_bank_type, id FROM t_car_check WHERE status = 0 ORDER BY c_bank_type ASC";
                        $type_result = odbc_exec($conn, $check_type_query);
                        while ($row = odbc_fetch_array($type_result)) {
                            $selected = (isset($c_bank) && $c_bank == $row['c_bank_type']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label for="c_check_no">Check No</label>
                <input type="text" class="form-control" id="c_check_no" name="c_check_no" value="<?php echo htmlspecialchars($c_check_no); ?>">
            </div>
        </div>
    </div>

    <div class="form-group" id="onlineBankListOR" style="display: <?php echo ($c_mop_or == 3) ? 'block' : 'none'; ?>;">
        <div class="row">
            <div class="col-md-6"> 
                <label for="c_bank_online_or">Online Bank</label>
                <div class="dropdown">
                    <select class="form-control" id="c_bank_online_or" name="c_bank_online_or">
                        <option value="" selected disabled>Select a bank</option>
                        <?php
                        $online_bank_query = "SELECT DISTINCT c_bank_type, id FROM t_car_online WHERE status = 0 ORDER BY c_bank_type ASC";
                        $type_result = odbc_exec($conn, $online_bank_query);
                        while ($row = odbc_fetch_array($type_result)) {
                            $selected = (isset($c_bank) && $c_bank == $row['c_bank_type']) ? 'selected' : '';
                            echo "<option value='".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."' $selected>".htmlspecialchars($row['c_bank_type'], ENT_QUOTES, 'UTF-8')."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label for="c_check_no">Ref No</label>
                <input type="text" class="form-control" id="c_ref_no" name="c_ref_no" value="<?php echo htmlspecialchars($c_check_no); ?>">
            </div>
        </div>
    </div>
    
    <div class="form-group" id="vat_fields">
        <input type="radio" id="none_vat" name="tax_option" value="0" onchange="computeVAT()" 
            <?php echo ($c_vat_selected == 0) ? 'checked' : ''; ?>>
        <label for="none_vat">Non VAT</label>

        <input type="radio" id="vat_sales" name="tax_option" value="1" onchange="computeVAT()" 
            <?php echo ($c_vat_selected == 1) ? 'checked' : ''; ?>>
        <label for="vat_sales">VAT Sales(12%)</label>

        <input type="radio" id="tax_holder" name="tax_option" value="2" onchange="computeVAT()" 
            <?php echo ($c_vat_selected == 2) ? 'checked' : ''; ?>>
        <label for="tax_holder">W/ Holding Tax</label>

        <div class="row" id="vat_details">
            <div class="col-md-6 mt-3">
                <label id="vat_sales_label">Vatable Sales: </label>
                <input type="text" class="form-control" id="c_vat_sales" name="c_vat_sales" 
                    value="<?php echo number_format(htmlspecialchars($c_vat_sales), 2,'.', ','); ?>" readonly>
            </div>
            <div class="col-md-6 mt-3">
                <label id="vat_amount_label">VAT Amount: </label>
                <input type="text" class="form-control" id="c_vat_amount" name="c_vat_amount" 
                    value="<?php echo number_format(htmlspecialchars($c_vat_amount),2,'.', ','); ?>" readonly>
            </div>
            <div class="col-md-6 mt-3">
                <label id="vat_amount_label">Less EWT: </label>
                <input type="text" class="form-control" id="c_ewt" name="c_ewt" 
                    value="<?php echo number_format(htmlspecialchars($c_ewt),2,'.', ','); ?>" readonly>
            </div>
            <div class="col-md-6 mt-3">
                <label id="vat_amount_label">NET Sales: </label>
                <input type="text" class="form-control" id="c_net_sales" name="c_net_sales" 
                    value="<?php echo number_format(htmlspecialchars($c_or_amount), 2, '.', ','); ?>" readonly>
            </div>
        </div>
    </div>

    <input type="hidden" id="c_vat_selected" name="c_vat_selected" value="<?php echo htmlspecialchars($c_vat_selected); ?>">

    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label for="pay_date">Pay Date</label>
                <input type="date" class="form-control" id="c_or_paydate" name="c_or_paydate" value="<?php echo htmlspecialchars($c_or_paydate) ?>" min="1990-01-01" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-6">
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
        </div>
    </div>

    <div class="form-group hidden_fields">
        <label for="encoder">Transaction date</label>
        <input type="text" class="form-control" id="c_tran_date" name="c_tran_date" value="<?php echo  htmlspecialchars($c_tran_date) ?>" readonly>
    </div>

    <table style="width:100%;">
        <tr>
            <td style="width:50%; text-align:center;">
                <a href="javascript:void(0);" class="btn btn-success" onclick="openPrintWindow()" style="width:100%;">
                    <span class="fas fa-print"></span> OR Preview
                </a>
            </td>
            <td style="width:50%; text-align:center;">
                <button type="submit" class="btn btn-primary" id="btnsave" style="width:100%;">Save</button>
            </td>
        </tr>
    </table>
</form>
<script src="../../dist/js/of_js/manage_or.js"></script>
<!-- <script>
    function handleModeOfPaymentChange() {
        var mop = document.getElementById('c_mop_or').value;
        document.getElementById('c_bank_online_or').value = '';
        document.getElementById('c_ref_no').value = '';
        document.getElementById('c_bank_check_or').value = '';
        document.getElementById('c_check_no').value = '';

        if (mop == '2') {
            document.getElementById('checkList').style.display = 'block';
            document.getElementById('onlineBankList').style.display = 'none';
        } else if (mop == '3') {
            document.getElementById('onlineBankList').style.display = 'block';
            document.getElementById('checkList').style.display = 'none';
        } else {
            document.getElementById('checkList').style.display = 'none';
            document.getElementById('onlineBankList').style.display = 'none';
        }
    }
</script> -->
<script>
$(document).ready(function() {
    
    $('#other-or-form').on('keydown', function(event) {
        if (event.key === "Enter" || event.keyCode === 13) {
            var target = event.target;
            
            if ($(target).is('textarea')) {
                return true;
            }
            event.preventDefault();
        }
    });

    $('#other-or-form').on('submit', function(e) {
        e.preventDefault(); 
        var orNo = $('#c_or_no').val();
        const orAmount = parseFloat($('#c_or_amount').val().replace(/,/g, ''));
        let valid = true;

        let atapVal = $('#c_tran_type_single_or').val(); 
        if (!atapVal) {
            atapVal = $('#atap_val_or').val(); 
        }else{
            atapVal = $('#c_or_type').val(); 
        }

        // if(atapVal === "STREETLIGHT FEE" || atapVal === "GRASS CUTTING FEE") {
        //     alert('The selected transaction type is Special.');
        //     valid = false;
        // }

        if (orNo.length < 6) {
            $('#or_no_error').text('OR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            valid = false;
        }

        if (orAmount <= 0) {
            $('#or_amt_error').text('Amount must be greater than zero.').addClass('bold-text').css('color', 'red');
            valid = false;
        }

        if (!valid) {
            return;
        }
        start_loader();

        $.ajax({
            url: "../../classes/Master.php?f=save_other_or_payment",
            data: new FormData($(this)[0]),
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
                        $('#createCarModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        location.reload();
                    }, 1000);
                } else if (resp && resp.status === 'failed') {
                    if (resp.msg === "Transaction type or OR type is required.") {
                        alert_toast("Transaction type or OR type is required.", 'error'); 
                    } else if (resp.msg === "OR type does not exist.") {
                        alert_toast("OR type does not exist.", 'error');
                    } else if (resp.err) {
                        alert_toast("An error occurred: " + resp.err, 'error');
                    }
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }
        });
    });

    $('#c_or_no').on('input', function() {
        const orNo = $('#c_or_no').val();
        const orNoError = $('#or_no_error');
        const submitButton = $('#btnsave');

        if (orNo.length < 6) {
            orNoError.text('OR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            submitButton.attr('disabled', true);
        } else if (orNo.length > 6) {
            orNoError.text('OR No. exceeds 6 digits.').addClass('bold-text').css('color', 'blue');
            //submitButton.attr('disabled', true);
        } else {
            $.ajax({
                type: 'POST',
                url: '../../supervisor/other_fees/check_or_no.php',
                data: { c_or_no: orNo },  
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        orNoError.text('OR No. already exists.').addClass('bold-text').css('color', 'red');
                        submitButton.attr('disabled', true);
                    } else {
                        orNoError.text('').removeClass('bold-text');
                        submitButton.attr('disabled', false);
                    }
                },
                error: function() {
                    orNoError.text('Error checking OR No.').addClass('bold-text').css('color', 'red');
                    submitButton.attr('disabled', true);
                }
            });
        }
    });

    $('#other-or-form').on('submit', function(e) {
        if ($('#or_no_error').text().includes('must be 6 digits')) {
            e.preventDefault();
        }
    });
});
</script>
<script>
function clearAmt(){
    var txtamt = document.getElementById('c_or_amount').value;

    if(txtamt == '0.00'){
        document.getElementById('c_or_amount').value='';
    }
}
$(document).ready(function() {
    $('#get_atap_or').on('click', function() {
        const atapNo = $('#c_atap_no_or').val();
        var clearType = $('#c_or_type');
        if (atapNo.length > 0) {
            $('#or_type_container').show();
            $('#tran_type_container_or').hide(); 
            fetchAtapDetails(atapNo);
        } else {
            alert('Please enter an ATAP No. first.');
        }
        clearType.val(''); 
    });

    function fetchAtapDetails(atapNo) {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url; ?>supervisor/other_fees/get_atap_others_details_of.php',
            data: { c_atap_no_or: atapNo },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    if (response.data.status === '1') {
                        $('#or_type_container').show();
                        $('#tran_type_container_or').hide();
                        alert("This ATAP has already been PAID.");
                        clearTxt();
                    } else if (response.data.status === '3') {
                        $('#or_type_container').show();
                        $('#tran_type_container_or').hide();
                        alert('This ATAP has already been CANCELLED');
                        clearTxt();
                    } else if ((response.data.status === '0' || response.data.status === '2') && (response.data.c_account_no !== '' && response.data.c_account_no !== null)) {
                        $('#or_type_container').show();
                        $('#tran_type_container_or').hide();
                        alert('The selected ATAP is a regular account.');
                        clearTxt();
                    }else {
                            populateForm(response.data);
                            fetchTranType(atapNo);

                            $('#or_type_container').hide();
                            $('#tran_type_container_or').css({
                                display: 'block',
                                visibility: 'visible',
                                opacity: 1
                            }).show();

                            setTimeout(function() {
                                if ($('#tran_type_container_or').height() === 0 || $('#tran_type_container_or').width() === 0) {
                                    //console.log('test');
                                }

                                if ($('#tran_type_container_or').is(':hidden') && $('#or_type_container').is(':hidden')) {
                                    alert('No OR transactions remaining for this ATAP #.');
                                    clearTxtNoOr();
                                    $('#btnsave').prop('disabled', true);
                                }

                            }, 100); 
                        }
                    } else {
                    $('#or_type_container').show();
                    $('#tran_type_container_or').hide();
                    alert('No account number found for the given ATAP No.');
                    clearTxt();
                }
            },
            error: function() {
                $('#or_type_container').show();
                $('#tran_type_container_or').hide();
                alert('An error occurred while fetching ATAP details.');
                clearTxt();
            }
        });
    }

    function clearTxtNoOr(){
        const buyerNameField = $('#c_name');
        const vatsales = $('#c_vat_sales');
        const vatamount = $('#c_vat_amount');
        const ewt = $('#c_ewt');
        const netsales = $('#c_net_sales');
        buyerNameField.val('');
        vatsales.val('');
        vatamount.val('');
        ewt.val('');
        netsales.val('');
    }
    function clearTxt(){
        $('#c_atap_no_or').val('');
        $('#c_name').val('').removeClass('glow-effect');
        $('#c_phase').val('').removeClass('glow-effect');
        $('#c_block').val('').removeClass('glow-effect');
        $('#c_lot').val('').removeClass('glow-effect');
        $('#c_or_amount').val('').removeClass('glow-effect');
        $('#current_remarks').val('').removeClass('glow-effect');

        const atapNoField = $('#c_atap_no_or');
        const nameField = $('#c_name');
        const phaseField = $('#c_phase');
        const blockField = $('#c_block');
        const lotField = $('#c_lot');
        const amountField = $('#c_or_amount');
        const remarksField = $('#current_remarks');
        const vatsales = $('#c_vat_sales');
        const vatamount = $('#c_vat_amount');
        const ewt = $('#c_ewt');
        const netsales = $('#c_net_sales');
        var comboBoxMenu = document.getElementById('comboBoxMenu_or');
        var orTypeInput = document.getElementById('c_or_type');
        var getAtapButton = document.getElementById('get_atap_or');

        atapNoField.val('');
        nameField.val('');
        phaseField.val('');
        blockField.val('');
        lotField.val('');
        amountField.val('');
        remarksField.val('');
        vatsales.val('');
        vatamount.val('');
        ewt.val('');
        netsales.val('');
        comboBoxMenu.classList.remove('disabled');
        atapNoField.prop('readonly', false); 
        orTypeInput.readOnly = false;      

        getAtapButton.style.backgroundColor = '';
        getAtapButton.style.borderColor = '';
        getAtapButton.innerHTML = '<span class="fa fa-edit"></span> Get ATAP';
    }

    function populateForm(data) {
        $('#c_name').val(data.c_name).addClass('glow-effect');
        $('#c_phase').val(data.c_phase).addClass('glow-effect');
        $('#c_block').val(data.c_block).addClass('glow-effect');
        $('#c_lot').val(data.c_lot).addClass('glow-effect');
        $('#current_remarks').val(data.current_remarks).addClass('glow-effect');

        const formattedAmount = parseFloat(data.c_or_amount).toFixed(2);
        $('#c_or_amount').val(formattedAmount).addClass('glow-effect');

        computeVAT();

        setTimeout(function() {
            $('#c_name').removeClass('glow-effect');
            $('#c_phase').removeClass('glow-effect');
            $('#c_block').removeClass('glow-effect');
            $('#c_lot').removeClass('glow-effect');
            $('#c_or_amount').removeClass('glow-effect');
            $('#current_remarks').removeClass('glow-effect');
        }, 1000);
    }
});
</script>
<script>
   $(document).ready(function() {
        function updateAtapId(selectedValue) {
            $('#atap_id_or').val(selectedValue);
        }

        function updateAtapAmount(selectedValue) {
            $('#c_or_amount').val(selectedValue);
        }

        function updateAtapVal(selectedValue) {
            $('#atap_val_or').val(selectedValue);
        }
        $('#c_tran_type_or').change(function() {
            var selectedOption = $(this).find(':selected');
            var selectedValue = selectedOption.val();
            var amount = selectedOption.data('amount'); 
            var atap_val_or = selectedOption.text(); 

            updateAtapId(selectedValue);
            updateAtapAmount(amount);
            updateAtapVal(atap_val_or); 
        });
    });
    function fetchTranType(atapNo) {
        $.ajax({
            url: 'fetch_tran_type.php',
            type: 'GET',
            data: { c_atap_no_or: atapNo },
            dataType: 'json',
            success: function(response) {
                var $select = $('#c_tran_type_or');
                var $textbox = $('#c_tran_type_single_or');
                var $atapId = $('#atap_id_or'); 
                var $atapAmount = $('#c_or_amount'); 
                var $atapVal = $('#atap_val_or'); 
                var $atapRemarks = $('#current_remarks'); 
                var $vatsales = $('#c_vat_sales');
                var $vatamount = $('#c_vat_amount');
                var $ewt = $('#c_ewt');
                var $netsales = $('#c_net_sales');
                $select.empty();
                
                if (response.length > 0) {
                    $('#tran_type_container_or').show();
                    if (response.length === 1) {
                        $textbox.val(response[0].text).show();
                        $('#tran_type_dropdown').hide();
                        $atapId.val(response[0].value); 
                        $atapAmount.val(response[0].amount); 
                        $atapVal.val(response[0].text);
                        $atapRemarks.val(response[0].remarks);
                        computeVAT();
                    } else {
                        $textbox.hide();
                        $('#tran_type_dropdown').show();
                        $.each(response, function(index, option) {
                            $select.append($('<option>', {
                                value: option.value,
                                text: option.text,
                                'data-amount': option.amount,
                                'data-atap_val': option.text,
                                'data-remarks': option.remarks
                            }));
                        });
                        $(document).on('change', '#tran_type_dropdown', function() {
                            computeVAT();
                        });
                        $atapId.val(response[0].value);
                        $atapAmount.val(response[0].amount);
                        $atapVal.val(response[0].text);
                        $atapRemarks.val(response[0].remarks);
                        computeVAT();
                    }
                } else {
                    $('#tran_type_container_or').hide();
                    $atapId.val(''); 
                    $atapAmount.val(''); 
                    $atapVal.val('');
                    $atapRemarks.val('');
                    $vatsales.val('');
                    $vatamount.val('');
                    $ewt.val('');
                    $netsales.val('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#tran_type_container_or').hide();
                $('#atap_id_or').val(''); 
                $('#c_or_amount').val(''); 
                $('#atap_val_or').val(''); 
                $('#current_remarks').val(''); 
                $('#c_vat_sales').val(''); 
                $('#c_vat_amount').val(''); 
                $('#c_ewt').val(''); 
                $('#c_net_sales').val(''); 
            }
        });
    }
</script>
<script>
function openPrintWindow() {
    var form = document.getElementById('other-or-form');
    if (!form) {
        console.error('Form not found!');
        return;
    }

    var formData = new FormData(form);
    var queryString = [];
    for (var pair of formData.entries()) {
        queryString.push(encodeURIComponent(pair[0]) + '=' + encodeURIComponent(pair[1]));
    }
    queryString = queryString.join('&');

    console.log('Query String:', queryString);

    var printUrl = '../../print/preview_other_or.php?' + queryString;
    var iframe = document.getElementById('previewORIframe');
    iframe.src = printUrl;

    $('#previewORModal').modal('show');
}

</script>
<script>
    $(document).ready(function() {
      
        function updateAtapVal(selectedValue) {
            $('#atap_val_or').val(selectedValue);
        }
        $('.dropdown-menu a.dropdown-item').on('click', function(e) {
            //e.preventDefault();
            var selectedValue = $(this).data('value');
            updateAtapVal(selectedValue);

            $('#dropdownMenuButton').text(selectedValue);
            $('#c_or_type').val(selectedValue); 
        });

        var initialSelectedValue = $('#c_or_type').val();
        updateAtapVal(initialSelectedValue);
    });

    </script>
    <script>
        function toggleCarType() {
            var atapNo = document.getElementById('c_atap_no_or').value;
            var carTypeContainer = document.getElementById('or_type_container');
            var tranTypeContainer =document.getElementById('tran_type_container_or');

            if (atapNo.trim() === '') {
                carTypeContainer.style.display = 'block';
                tranTypeContainer.style.display = 'none';
            } else {
                tranTypeContainer.style.display = 'block';
                carTypeContainer.style.display = 'none';  
            }
        }
    </script>
    <script>
        document.querySelectorAll('#comboBoxMenu_or .dropdown-item').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                const selectedValue = this.getAttribute('data-value');
                document.getElementById('c_or_type').value = selectedValue;
              
                document.getElementById('c_atap_no_or').readOnly = true;
            });
        });
    </script>
    <script>
        function toggleCheckDropdown() {
            var modeOfPayment = document.getElementById("c_mop_or").value;
            var checkList = document.getElementById("checkListOR");
            var onlineBankList = document.getElementById("onlineBankListOR");
            var cBankCheckInput = document.getElementById("c_bank_check_or");
            var cBankOnlineInput = document.getElementById("c_bank_online_or");
            var cCheckNo = document.getElementById("c_check_no");
            var cRefNo = document.getElementById("c_ref_no");

            if (modeOfPayment == '2') {
                checkList.style.display = "block";
                cCheckNo.style.display = "block";
                onlineBankList.style.display = "none";
                cRefNo.style.display = "none";
                cBankCheckInput.setAttribute("required", "true");
                cBankOnlineInput.removeAttribute("required");
                cBankOnlineInput.value = ""; 
                cRefNo.value = "";
            } else if (modeOfPayment == '3') {
                checkList.style.display = "none";
                cCheckNo.style.display = "none";
                onlineBankList.style.display = "block";
                cRefNo.style.display = "block";
                cBankOnlineInput.setAttribute("required", "true");
                cBankCheckInput.removeAttribute("required");
                cBankCheckInput.value = "";
                cCheckNo.value = "";
            } else {
                checkList.style.display = "none";
                cCheckNo.style.display = "none";
                cRefNo.style.display = "none";
                onlineBankList.style.display = "none";
                cBankCheckInput.removeAttribute("required");
                cBankOnlineInput.removeAttribute("required");
                cBankCheckInput.value = "";
                cBankOnlineInput.value = "";
                cCheckNo.value = "";
                cRefNo.value = "";
            }
        }
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        computeVAT();

        var amountInput = document.getElementById("c_or_amount");
        if (amountInput) {
            amountInput.addEventListener("input", function () {
                computeVAT();
            });
        }
    });

    function computeVAT() {
        var amountInput = document.getElementById("c_or_amount");
        if (!amountInput) return; 

        var amount = parseFloat(amountInput.value.replace(/,/g, '')) || 0;

        var noneVatRadio = document.getElementById("none_vat");
        var vatSalesRadio = document.getElementById("vat_sales");
        var taxHolderRadio = document.getElementById("tax_holder");

        var vatableSales = 0, vatAmount = 0, ewt = 0, netSales = amount, vatSelected = 0;

        if (noneVatRadio && noneVatRadio.checked) {
            vatableSales = 0;
            vatAmount = 0;
            ewt = 0;
            vatSelected = 0;
        } else if (vatSalesRadio && vatSalesRadio.checked) {
            vatableSales = amount / 1.12;
            vatAmount = amount - vatableSales;
            vatSelected = 1;
        } else if (taxHolderRadio && taxHolderRadio.checked) {
            vatableSales = amount / 1.07;
            vatAmount = vatableSales * 0.12;
            ewt = vatableSales * 0.05;
            vatSelected = 2;
        }
        
        netSales = amount;

        var c_vat_sales = document.getElementById("c_vat_sales");
        var c_vat_amount = document.getElementById("c_vat_amount");
        var c_ewt = document.getElementById("c_ewt");
        var c_net_sales = document.getElementById("c_net_sales");
        var c_vat_selected = document.getElementById("c_vat_selected");

        /* if (c_vat_sales) c_vat_sales.value = vatableSales.toFixed(2);
        if (c_vat_amount) c_vat_amount.value = vatAmount.toFixed(2);
        if (c_ewt) c_ewt.value = ewt.toFixed(2);
        if (c_net_sales) c_net_sales.value = netSales.toFixed(2);
        if (c_vat_selected) c_vat_selected.value = vatSelected; */

        function formatNumber(num) {
            return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        if (c_vat_sales) c_vat_sales.value = formatNumber(vatableSales);
        if (c_vat_amount) c_vat_amount.value = formatNumber(vatAmount);
        if (c_ewt) c_ewt.value = formatNumber(ewt);
        if (c_net_sales) c_net_sales.value = formatNumber(netSales);
        if (c_vat_selected) c_vat_selected.value = vatSelected;
    }

    window.onload = function () {
        computeVAT();
    };
</script>