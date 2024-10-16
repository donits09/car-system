<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(1);

include('../../config.php');

$c_account_no = null;
$c_car_type = '';
$c_car_amount = 0;
$c_car_no = '';
$c_car_paydate = date('Y-m-d');
$c_encoded_by = '';
$c_tran_date = date('Y-m-d H:i:s');
$c_mop = '0';
$c_bank = '';
$c_check_no = '';
$c_remarks = '';

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
        <div class="dropdown" id="car_type_container">
            <label for="c_car_type">Transaction Type</label>
            <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_car_type" name="c_car_type" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>">
            <div class="dropdown-menu w-100" id="comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
                <?php
                $car_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type WHERE status = 0 ORDER BY c_payment_type ASC";
                $type_result = odbc_exec($conn, $car_type_query);
                while ($row = odbc_fetch_array($type_result)) {
                    echo "<a class='dropdown-item' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#c_car_type').on('input', function () {
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
                $('#c_car_type').val(selectedText);
                $('#comboBoxMenu').hide();
            });

            $('#c_car_type').on('focus click', function () {
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
            $('#c_car_type').val(selectedValue); 
        });

        var initialSelectedValue = $('#c_car_type').val();
        updateAtapVal(initialSelectedValue);
    });

    </script>

    <script>
    function toggleCarType() {
        var atapNo = document.getElementById('c_atap_no').value;
        var carTypeContainer = document.getElementById('car_type_container');
        var tranTypeContainer =document.getElementById('tran_type_container');

        if (atapNo.trim() === '') {
            carTypeContainer.style.display = 'block';
            tranTypeContainer.style.display = 'none';
        } else {
            tranTypeContainer.style.display = 'block';
            carTypeContainer.style.display = 'none';  
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
        <label for="car_no">CAR No.</label>
        <input type="number" class="form-control" id="c_car_no" name="c_car_no" value="<?php echo htmlspecialchars($c_car_no); ?>" maxlength="6" minlength="6" pattern="\d{6}" oninput="validateNumberInput(event)" required>
        <div id="car_no_error"></div>
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
                <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo number_format(htmlspecialchars($c_car_amount),2); ?>" oninput="validateNumberInputAmt(event)" onclick="clearAmt()" required>
                <div id="car_amt_error"></div>
            </div>
            <div class="col-md-6">
                <label for="c_mop">Mode of Payment</label>
                <select class="form-control" id="c_mop" name="c_mop" required onchange="handleModeofPaymentChange()">
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
                    <select class="form-control" id="c_bank_check" name="c_bank_check">
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
                    <select class="form-control" id="c_bank_online" name="c_bank_online">
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
                <input type="date" class="form-control" id="c_car_paydate" name="c_car_paydate" value="<?php echo htmlspecialchars($c_car_paydate) ?>" min="1990-01-01" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="encoder">Encoded by</label>
                <input type="text" class="hidden_fields" id="c_encoded_by" name="c_encoded_by" value="<?php echo $_SESSION['username'] ?>" readonly>
                <?php
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $c_encoded_by == $c_encoded_by;
                } else {
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
            <span class="fas fa-print"></span> CAR Preview
        </a>
    </div>

    <button type="submit" class="btn btn-primary" id="btnsave">Save</button>
</form>
<script src="../../dist/js/all_car_list.js"></script>
<script>
    function handleModeOfPaymentChange() {
        var mop = document.getElementById('c_mop').value;
        document.getElementById('c_bank_online').value = '';
        document.getElementById('c_ref_no').value = '';
        document.getElementById('c_bank_check').value = '';
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
</script>
<script>
    function handleModeOfPaymentChange() {
        var mop = document.getElementById('c_mop').value;
        document.getElementById('c_bank_online').value = '';
        document.getElementById('c_ref_no').value = '';
        document.getElementById('c_bank_check').value = '';
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
</script>
<script>
 $(document).ready(function() {
    $('#get_atap').on('click', function() {
        const atapNo = $('#c_atap_no').val();
        var clearType = $('#c_car_type');
        if (atapNo.length > 0) {
            $('#car_type_container').show();
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
            url: '../atap/get_atap_details.php',
            data: { c_atap_no: atapNo },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    if (response.data && response.data.c_account_no) {
                        const currentAccountNo = $('#c_account_no').val();
                        // const appStats = $('#approval_status').val();
                        // if (response.data.approval_status === '0') {
                        //     $('#car_type_container').show();
                        //     $('#tran_type_container').hide();
                        //     alert('The selected ATAP requires approval.');
                        //     clearTxt();
                        // }else if (response.data.approval_status === '3') {
                        //     $('#car_type_container').show();
                        //     $('#tran_type_container').hide();
                        //     alert('The selected ATAP was disapproved.');
                        //     clearTxt();
                        // }else 
                        if (response.data.status === '1') {
                            $('#car_type_container').show();
                            $('#tran_type_container').hide();
                            alert("This ATAP has already been PAID.");
                            clearTxt();
                        } else if (response.data.status === '3') {
                            $('#car_type_container').show();
                            $('#tran_type_container').hide();
                            alert('This ATAP has already been CANCELLED');
                            clearTxt();
                        } else {
                            populateForm(response.data);
                            fetchBuyerDetails(response.data.c_account_no);
                            fetchTranType(atapNo);
                            $('#car_type_container').hide();
                            $('#tran_type_container').show();
                        }
                    } else {
                        $('#car_type_container').show();
                        $('#tran_type_container').hide();
                        alert('No account number found for the given ATAP No.');
                        clearTxt();
                    }
                } else {
                    $('#car_type_container').show();
                    $('#tran_type_container').hide();
                    alert('No ATAP details found for the given ATAP No.');
                    clearTxt();
                }
            },
            error: function() {
                $('#car_type_container').show();
                $('#tran_type_container').hide();
                alert('An error occurred while fetching ATAP details.');
                clearTxt();
            }
        });
    }

    function clearTxt(){
        const atapNoField = $('#c_atap_no');
        const buyerNameField = $('#buyer_name');
        const amountField = $('#c_car_amount');
        const accField = $('#c_account_no');
        const statusField = $('#status');

        atapNoField.val('');
        buyerNameField.val('');
        amountField.val('');
        accField.val('');
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
<script>
    $(document).ready(function() {
        function updateAtapId(selectedValue) {
            $('#atap_id').val(selectedValue);
        }

        function updateAtapAmount(selectedValue) {
            $('#c_car_amount').val(selectedValue);
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
            url: 'fetch_tran_type.php',
            type: 'GET',
            data: { c_atap_no: atapNo },
            dataType: 'json',
            success: function(response) {
                var $select = $('#c_tran_type');
                var $textbox = $('#c_tran_type_single');
                var $atapId = $('#atap_id'); 
                var $atapAmount = $('#c_car_amount'); 
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
                $('#c_car_amount').val(''); 
                $('#atap_val').val(''); 
            }
        });
    }
</script>
<script>
    function openPrintWindow() {
        var form = document.getElementById('car-form');
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

        var printUrl = '../../print/preview_car.php?' + queryString;

        var iframe = document.getElementById('previewCarIframe');
        iframe.src = printUrl;

        $('#previewCarModal').modal('show');
    }
</script>

</body>