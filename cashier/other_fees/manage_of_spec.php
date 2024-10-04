<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(3);

include('../../config.php');

$c_account_no = null;
$c_or_type = '';
$c_or_amount = 0;
$c_or_no = '';
$c_or_paydate = date('Y-m-d');
$c_encoded_by = '';
$c_tran_date = date('Y-m-d H:i:s');
$c_mop = '0';
$c_bank = '';
$c_check_no = '';
$c_remarks = '';
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $get_or_query = "SELECT * FROM t_or_payment WHERE id = ?";
    $accountId = $_GET['id'];
    $stmt = odbc_prepare($conn, $get_or_query);
    odbc_execute($stmt, array($accountId));

    if ($result = odbc_fetch_array($stmt)) {
        $c_account_no = $result["c_account_no"];
        $c_or_type = $result["c_or_type"];
        $c_or_amount = $result["c_or_amount"];
        $c_or_no = $result["c_or_no"];
        $c_or_paydate = $result["c_or_paydate"];
        $c_encoded_by = $result["c_encoded_by"];
        $c_mop = $result["c_mop"];  
        $c_bank = $result["c_bank"];
        $c_check_no = $result["c_check_no"];
        $c_remarks = $result["c_remarks"];
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
.combo-box-menu {
    max-height: 200px; 
    overflow-y: auto;
}

</style>
<link rel="stylesheet" href="../../dist/css/manage_car.css">
<form id="or-form" method="post" action="">
    <?php
    $readonly = isset($c_account_no) && !empty($c_account_no) ? 'readonly' : '';
    ?>
    <input type="hidden" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label for="c_atap_no">ATAP No.</label>
                <input type="number" class="form-control" id="c_atap_no" name="c_atap_no">
            </div>
        </div>
        <div class="col-sm-4" style="margin-top: 25px;">
            <a id="get_atap" class="btn btn-flat btn-primary" style="width: 100%; color: white;" onclick="toggleCarType()">
                <span class="fa fa-edit"></span> Get ATAP
            </a>
        </div>
    </div>
    <div class="form-group" id="tran_type_container" style="display: none;">
        <label for="c_tran_type">Transaction Type/s from client's ATAP</label>
        <div id="tran_type_dropdown" class="dropdown" style="width:100%;">
            <select class="form-control" id="c_tran_type" name="c_tran_type">
            </select>
        </div>
        <input type="text" class="form-control" id="c_tran_type_single" name="c_tran_type_single" style="display: none;" readonly>
    </div>
    <div class="form-group">
        <div class="dropdown" id="or_type_container">
            <label for="c_or_type">Transaction Type</label>
           
            <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_or_type" name="c_or_type" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_or_type) ? htmlspecialchars($c_or_type, ENT_QUOTES, 'UTF-8') : ''; ?>">
            <div class="dropdown-menu w-100" id="comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
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
        $(document).ready(function () {
            $('#c_or_type').on('input', function () {
                var input = $(this).val().toLowerCase();
                var hasVisibleOptions = false;
                $('#comboBoxMenu .dropdown-item').each(function () {
                    if ($(this).text().toLowerCase().startsWith(input)) {
                        $(this).show();
                        hasVisibleOptions = true;
                    } else {
                        $(this).hide();
                    }
                });

                if (hasVisibleOptions) {
                    $('#comboBoxMenu').show();
                } else {
                    $('#comboBoxMenu').hide();
                }
            });

            $('#comboBoxMenu').on('click', '.dropdown-item', function () {
                var selectedText = $(this).data('value');
                $('#c_or_type').val(selectedText);
                $('#comboBoxMenu').hide();
            });

            $('#c_or_type').on('focus click', function () {
                $('#comboBoxMenu').show();
            });

            $(document).on('click', function (e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('#comboBoxMenu').hide();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
      
        function updateAtapVal(selectedValue) {
            $('#atap_val').val(selectedValue);
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
    var atapNo = document.getElementById('c_atap_no').value;
    var orTypeContainer = document.getElementById('or_type_container');
    var tranTypeContainer =document.getElementById('tran_type_container');

    if (atapNo.trim() === '') {
        orTypeContainer.style.display = 'block';
        tranTypeContainer.style.display = 'none';
    } else {
        tranTypeContainer.style.display = 'block';
        orTypeContainer.style.display = 'none';  
    }
}
    </script>
    <input type="hidden" class="form-control" id="atap_id" name="atap_id" readonly>
    <input type="hidden" class="form-control" id="atap_val" name="atap_val" readonly>

    <hr>
    <div class="form-group">
        <label for="account_no">Account No.</label>
        <input type="text" class="form-control" id="c_account_no" name="c_account_no" value="<?php echo htmlspecialchars($c_account_no) ?>" <?php echo $readonly; ?> oninput="validateNumberInput(event)" required>
    </div>
    <div class="form-group">
        <label for="or_no">OR No.</label>
        <input type="number" class="form-control" id="c_or_no" name="c_or_no" value="<?php echo htmlspecialchars($c_or_no); ?>" maxlength="6" minlength="6" pattern="\d{6}" oninput="validateNumberInput(event)" required>
        <div id="or_no_error"></div>
    </div>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="buyer_name" name="buyer_name" oninput="validateAlphaNumericInput(event)" readonly>
    </div>
    <div class="form-group">
        <label for="remarks" class="form-label">
            Remarks 
        </label>
        <textarea class="form-control txt" rows="2" cols="50" id="c_remarks" name="c_remarks"><?php echo htmlspecialchars($c_remarks) ?></textarea>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label for="amount">Amount</label>
                <input type="text" class="form-control" id="c_or_amount" name="c_or_amount" value="<?php echo number_format(htmlspecialchars($c_or_amount),2); ?>" oninput="validateNumberInputAmt(event)" onclick="clearAmt()" required>
                <div id="or_amt_error"></div>
            </div>
            <div class="col-md-6">
                <label for="c_mop">Mode of Payment</label>
                <select class="form-control" id="c_mop" name="c_mop" required onchange="toggleCheckDropdown()">
                    <option value="1" <?php echo ($c_mop == 1) ? 'selected' : ''; ?>>Cash</option>
                    <option value="2" <?php echo ($c_mop == 2) ? 'selected' : ''; ?>>Check</option>
                    <option value="3" <?php echo ($c_mop == 3) ? 'selected' : ''; ?>>Online</option>
                </select>
            </div>  
        </div>  
    </div>  

    <div class="form-group" id="checkList" style="display: <?php echo ($c_mop == 2) ? 'block' : 'none'; ?>;">
        <div class="row">
            <div class="col-md-6">      
                <label for="c_bank_check">Check Bank</label>
                <div class="dropdown">
                    <select class="form-control" id="c_bank_check" name="c_bank_check" required>
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

    <div class="form-group" id="onlineBankList" style="display: <?php echo ($c_mop == 3) ? 'block' : 'none'; ?>;">
        <div class="row">
            <div class="col-md-6"> 
                <label for="c_bank_online">Online Bank</label>
                <div class="dropdown">
                    <select class="form-control" id="c_bank_online" name="c_bank_online" required>
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
    <div class="mb-3">
        <a href="javascript:void(0);" class="btn btn-success" onclick="openPrintWindow()">
            <span class="fas fa-print"></span> OR Preview
        </a>
    </div>
    <button type="submit" class="btn btn-primary" id="btnsave">Save</button>
</form>
<script src="../../dist/js/of_js/manage_or.js"></script>
<script>
$(document).ready(function() {
    $('#or-form').submit(function(e) {
        e.preventDefault();

        const buyerName = $('#buyer_name').val();
        const orNo = $('#c_or_no').val();
        const orAmount = parseFloat($('#c_or_amount').val().replace(/,/g, ''));

        let valid = true;

        let atapVal = $('#c_tran_type_single').val(); 
        if (!atapVal) {
            atapVal = $('#atap_val').val(); 
        }else{
            atapVal = $('#c_or_type').val(); 
        }

        if(atapVal === "STREETLIGHT FEE" || atapVal === "GRASS CUTTING FEE") {
            alert('The selected transaction type is Special.');
            valid = false;
        }

        if (orNo.length < 6) {
            $('#or_no_error').text('OR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            valid = false;
        }

        if (orAmount <= 0) {
            $('#or_amt_error').text('Amount must be greater than zero.').addClass('bold-text').css('color', 'red');
            valid = false;
        }

        if (!buyerName || buyerName === 'Unknown') {
            alert('Name field is required.');
            valid = false;
        }
        if (!valid) {
            return;
        }
        start_loader();

        $.ajax({
            url: "../../classes/Master.php?f=save_or_payment",
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
                        $('#createOrModal').modal('hide'); 
                        $('body').removeClass('modal-open'); 
                        $('.modal-backdrop').remove(); 
                        updateORList();
                    }, 1000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }
        });
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

    $('#c_or_no').on('input', function() {
        const orNo = $('#c_or_no').val();
        const orNoError = $('#or_no_error');
        const submitButton = $('#btnsave');

        if (orNo.length < 6) {
            orNoError.text('OR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            submitButton.attr('disabled', true);
        } else if (orNo.length > 6) {
            orNoError.text('OR No. exceeds 6 digits.').addClass('bold-text').css('color', 'blue');
            submitButton.attr('disabled', true);
        } else {
            $.ajax({
                type: 'POST',
                url: '../../cashier/other_fees/check_or_no.php',
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

    $('#or-form').on('submit', function(e) {
        if ($('#or_no_error').text().includes('must be 6 digits')) {
            e.preventDefault();
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $('#get_atap').on('click', function() {
        const atapNo = $('#c_atap_no').val();
        var clearType = $('#c_or_type');
        if (atapNo.length > 0) {
            $('#or_type_container').show();
            $('#tran_type_container').hide(); 
            fetchAtapDetails(atapNo);
        } else {
            alert('Please enter an ATAP No. first.');
        }
        clearType.val(''); 
    });

    function fetchAtapDetails(atapNo) {
        $.ajax({
            type: 'POST',
            url: '../../cashier/other_fees/get_atap_details_of.php',
            data: { c_atap_no: atapNo },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    if (response.data && response.data.c_account_no) {
                        const currentAccountNo = $('#c_account_no').val();
                        // const appStats = $('#approval_status').val();
                        // if (response.data.approval_status === '0') {
                        //     $('#or_type_container').show();
                        //     $('#tran_type_container').hide();
                        //     alert('The selected ATAP requires approval.');
                        //     clearTxt();
                        // }else if (response.data.approval_status === '3') {
                        //     $('#or_type_container').show();
                        //     $('#tran_type_container').hide();
                        //     alert('The selected ATAP was disapproved.');
                        //     clearTxt();
                        // }else 
                        if (response.data.c_account_no !== currentAccountNo) {
                            $('#or_type_container').show();
                            $('#tran_type_container').hide();
                            alert('The account number of the selected ATAP No. does not match.');
                            clearTxt();
                        } else if (response.data.status === '1') {
                            $('#or_type_container').show();
                            $('#tran_type_container').hide();
                            alert("This ATAP has already been PAID.");
                            clearTxt();
                        } else if (response.data.status === '3') {
                            $('#or_type_container').show();
                            $('#tran_type_container').hide();
                            alert('This ATAP has already been CANCELLED');
                            clearTxt();
                        } else {
                            populateForm(response.data);
                            fetchBuyerDetails(response.data.c_account_no);
                            fetchTranType(atapNo);
                            $('#or_type_container').hide();
                            $('#tran_type_container').show();
                        }
                    } else {
                        $('#or_type_container').show();
                        $('#tran_type_container').hide();
                        alert('No account number found for the given ATAP No.');
                        clearTxt();
                    }
                } else {
                    $('#or_type_container').show();
                    $('#tran_type_container').hide();
                    alert('No ATAP details found for the given ATAP No.');
                    clearTxt();
                }
            },
            error: function() {
                $('#or_type_container').show();
                $('#tran_type_container').hide();
                alert('An error occurred while fetching ATAP details.');
                clearTxt();
            }
        });
    }


    function clearTxt(){
        const atapNoField = $('#c_atap_no');
        const amountField = $('#c_or_amount');
        const statusField = $('#status');
        //const accField = $('#c_account_no');

        atapNoField.val('');
        amountField.val('');
        //accField.val('');
        statusField.val('');
    }

    function populateForm(data) {
        const buyerNameField = $('#buyer_name');
        const amountField = $('#c_or_amount');
        const accField = $('#c_account_no');
        const statusField = $('#status');

        if (data.status === '1') {
            statusField.val('PAID');
        } else if (data.status === '2') {
            statusField.val('CANCELLED');
        } else {
            statusField.val('PENDING');
        }

        const formattedAmount = parseFloat(data.c_or_amount).toFixed(2);

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

function updateCarList() {
    const username = $('#username').val(); 
    const accountNo = $('#buyer_acc_no').val();

    fetch(`car_list.php?username=${username}&buyer_acc_no=${accountNo}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('car-list-body').innerHTML = data;
            calculateTotalAmount(); 
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}
function updateORList() {
    const username = $('#username').val(); 
    const accountNo = $('#buyer_acc_no').val();

    fetch(`<?php echo base_url; ?>admin/other_fees/fetch_or_list.php?username=${username}&buyer_acc_no=${accountNo}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('or-list-body').innerHTML = data;
            calculateTotalORAmount(); 
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}
</script>
<script>
   $(document).ready(function() {
        function updateAtapId(selectedValue) {
            $('#atap_id').val(selectedValue);
        }

        function updateAtapAmount(selectedValue) {
            $('#c_or_amount').val(selectedValue);
        }

        function updateAtapVal(selectedValue) {
            $('#atap_val').val(selectedValue);
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


    function fetchTranType(atapNo) {
        $.ajax({
            url: '<?php echo base_url; ?>cashier/car/fetch_tran_type.php',
            type: 'GET',
            data: { c_atap_no: atapNo },
            dataType: 'json',
            success: function(response) {
                var $select = $('#c_tran_type');
                var $textbox = $('#c_tran_type_single');
                var $atapId = $('#atap_id'); 
                var $atapAmount = $('#c_or_amount'); 
                var $atapVal = $('#atap_val'); 
                $select.empty();
                
                if (response.length > 0) {
                    $('#tran_type_container').show();
                    if (response.length === 1) {
                        $textbox.val(response[0].text).show();
                        $('#tran_type_dropdown').hide();
                        $atapId.val(response[0].value); 
                        $atapAmount.val(response[0].amount); 
                        $atapVal.val(response[0].text);
                    } else {
                        $textbox.hide();
                        $('#tran_type_dropdown').show();
                        $.each(response, function(index, option) {
                            $select.append($('<option>', {
                                value: option.value,
                                text: option.text,
                                'data-amount': option.amount,
                                'data-atap_val': option.text
                            }));
                        });
                        $atapId.val(response[0].value);
                        $atapAmount.val(response[0].amount);
                        $atapVal.val(response[0].text);
                    }
                } else {
                    $('#tran_type_container').hide();
                    $atapId.val(''); 
                    $atapAmount.val(''); 
                    $atapVal.val('');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#tran_type_container').hide();
                $('#atap_id').val(''); 
                $('#c_or_amount').val(''); 
                $('#atap_val').val(''); 
            }
        });
    }
</script>
<script>
    function openPrintWindow() {
        var form = document.getElementById('or-form');
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

        var printUrl = '../../print/preview_or.php?' + queryString;
        var iframe = document.getElementById('previewORIframe');
        iframe.src = printUrl;

        $('#previewORModal').modal('show');
    }
</script>
