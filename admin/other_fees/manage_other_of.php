<?php 
    session_start();
    
    require_once('../../inc/check_session.php');
    check_user_group(1);

    include('../../config.php');

    $c_name = '';
    $c_loc = '';
    $c_or_type = '';
    $c_or_amount = 0;
    $c_or_no = '';
    $c_or_paydate = date('Y-m-d');
    $c_encoded_by = '';
    $c_tran_date = date('Y-m-d H:i:s');
    $c_mop = '0';
    $c_bank = '';
    $c_lot = '';
    $c_block = '';
    $c_check_no = '';
    $c_remarks = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_or_query = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                    a.c_or_paydate,a.c_or_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop,a.c_bank, b.c_name, b.c_phase,
                    b.c_block, b.c_lot, a.c_check_no, a.c_remarks
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
            $c_mop = $result["c_mop"];
            $c_bank = $result["c_bank"];
            $c_check_no = $result["c_check_no"];
            $c_remarks = $result["c_remarks"];
        }
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
<form id="other-or-form">
    <input type="hidden" id="id" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label for="c_atap_no">ATAP No.</label>
                <input type="text" class="form-control" id="c_atap_no" name="c_atap_no">
            </div>
        </div>
        <div class="col-sm-4" style="margin-top: 25px;">
            <a id="get_atap" class="btn btn-flat btn-primary" style="width: 100%; color: white;" onclick="toggleCarType()">
                <span class="fa fa-edit"></span> Get ATAP
            </a>
        </div>
    </div>
    <div class="form-group">
        <div class="dropdown" id="car_type_container">
            <label for="c_or_type">Transaction Type</label>
           
            <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_or_type" name="c_or_type" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_or_type) ? htmlspecialchars($c_or_type, ENT_QUOTES, 'UTF-8') : ''; ?>">
            <div class="dropdown-menu w-100" id="comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
                <?php
                $or_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type WHERE status = 0 ORDER BY c_payment_type ASC";
                $type_result = odbc_exec($conn, $or_type_query);
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

   
    <div class="form-group" id="tran_type_container" style="display: none;">
        <label for="c_tran_type">Transaction Type/s from client's ATAP</label>
        <div id="tran_type_dropdown" class="dropdown" style="width:100%;">
            <select class="form-control" id="c_tran_type" name="c_tran_type">
            </select>
        </div>
        <input type="text" class="form-control" id="c_tran_type_single" style="display: none;" readonly>
    </div>
    <input type="hidden" class="form-control" id="atap_id" name="atap_id" readonly>
    <input type="hidden" class="form-control" id="atap_val" name="atap_val" readonly>

    <hr>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="c_name" name="c_name" value="<?php echo htmlspecialchars($c_name); ?>" oninput="validateAlphaNumericInput(event)" required>
    </div>
    <div class="form-group">
        <label for="remarks" class="form-label">
            Remarks 
        </label>
        <textarea class="form-control txt" rows="2" cols="50" id="c_remarks" name="c_remarks"><?php echo htmlspecialchars($c_remarks) ?></textarea>
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
                    document.getElementById('comboBoxMenu').style.display = 'none';
                });
            });

            document.getElementById('c_or_type').addEventListener('focus', function() {
                document.getElementById('comboBoxMenu').style.display = 'block';
            });

            document.getElementById('c_or_type').addEventListener('blur', function() {
                setTimeout(function() {
                    document.getElementById('comboBoxMenu').style.display = 'none';
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
                <input type="text" class="form-control" id="c_or_amount" name="c_or_amount" value="<?php echo number_format(htmlspecialchars($c_or_amount),2); ?>" oninput="validateNumberInputAmt(event)" onclick="clearAmt()" required>
                <div id="car_amt_error"></div>
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
                <input type="text" id="c_encoded_by" class="hidden_fields" name="c_encoded_by" value="<?php echo $_SESSION['username'] ?>" readonly>
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
<!-- <script>
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
</script> -->
<script>
$(document).ready(function() {
    $('#other-or-form').on('submit', function(e) {
        e.preventDefault(); 
        var orNo = $('#c_or_no').val();
        const carAmount = parseFloat($('#c_or_amount').val().replace(/,/g, ''));
        let valid = true;
        if (orNo.length < 6) {
            $('#or_no_error').text('OR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            valid = false;
        }

        if (carAmount <= 0) {
            $('#car_amt_error').text('Amount must be greater than zero.').addClass('bold-text').css('color', 'red');
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
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }
        });
    });

    $('#c_or_no').on('input', function() {
        const orNo = $(this).val();
        const orNoError = $('#or_no_error');
        const submitButton = $('#btnsave');

        if (orNo.length < 6) {
            $('#or_no_error').text('OR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            $('#other-or-form button[type="submit"]').attr('disabled', true);
        } else if (orNo.length > 6) {
            $('#or_no_error').text('OR No. exceeds 6 digits.').addClass('bold-text').css('color', 'blue');
            $('#other-or-form button[type="submit"]').attr('disabled', false);
        } else {
            $.ajax({
                type: 'POST',
                url: 'check_or_no.php',
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
$(document).ready(function() {
    $('#get_atap').on('click', function() {
        const atapNo = $('#c_atap_no').val();
        var clearType = $('#c_or_type');
        if (atapNo.length > 0) {
            $('#car_type_container').show();
            $('#tran_type_container').hide(); 
            fetchAtapDetails(atapNo);
        } else {
            alert('Please enter an ATAP No. first.');
        }
        clearType.val(''); 
    });

    // function fetchAtapDetails(atapNo) {
    //     $.ajax({
    //         type: 'POST',
    //         url: 'get_atap_details_of.php',
    //         data: { c_atap_no: atapNo },
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.status === 'success') {
    //                 if (response.data.status === '1') {
    //                     $('#car_type_container').show();
    //                     $('#tran_type_container').hide();
    //                     alert("This ATAP has already been PAID.");
    //                     clearTxt();
    //                 } else if (response.data.status === '3') {
    //                     $('#car_type_container').show();
    //                     $('#tran_type_container').hide();
    //                     alert('This ATAP has already been CANCELLED');
    //                     clearTxt();
    //                 } else if ((response.data.status === '0' || response.data.status === '2') && (response.data.c_account_no !== '' && response.data.c_account_no !== null)) {
    //                     $('#car_type_container').show();
    //                     $('#tran_type_container').hide();
    //                     alert('The selected ATAP is a regular account.');
    //                     clearTxt();
    //                 }else {
    //                     populateForm(response.data);
    //                     fetchTranType(atapNo);
    //                     $('#car_type_container').hide();
    //                     $('#tran_type_container').show();
    //                 }
    //             } else {
    //                 $('#car_type_container').show();
    //                 $('#tran_type_container').hide();
    //                 alert('No account number found for the given ATAP No.');
    //                 clearTxt();
    //             }
    //         },
    //         error: function() {
    //             $('#car_type_container').show();
    //             $('#tran_type_container').hide();
    //             alert('An error occurred while fetching ATAP details.');
    //             clearTxt();
    //         }
    //     });
    // }

    function fetchAtapDetails(atapNo) {
        $.ajax({
            type: 'POST',
            url: 'get_atap_others_details_of.php',
            data: { c_atap_no: atapNo },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const appStats = $('#approval_status').val();
                    if (response.data.approval_status === '0') {
                            $('#car_type_container').show();
                            $('#tran_type_container').hide();
                            alert('The selected ATAP requires approval.');
                            clearTxt();
                    }else if (response.data.approval_status === '3') {
                        $('#car_type_container').show();
                        $('#tran_type_container').hide();
                        alert('The selected ATAP was disapproved.');
                        clearTxt();
                    }else if (response.data.status === '1') {
                        $('#car_type_container').show();
                        $('#tran_type_container').hide();
                        alert("This ATAP has already been PAID.");
                        clearTxt();
                    } else if (response.data.status === '3') {
                        $('#car_type_container').show();
                        $('#tran_type_container').hide();
                        alert('This ATAP has already been CANCELLED');
                        clearTxt();
                    } else if ((response.data.status === '0' || response.data.status === '2') && (response.data.c_account_no !== '' && response.data.c_account_no !== null)) {
                        $('#car_type_container').show();
                        $('#tran_type_container').hide();
                        alert('The selected ATAP is a regular account.');
                        clearTxt();
                    }else {
                        populateForm(response.data);
                        //fetchBuyerDetails(response.data.c_account_no);
                        fetchTranType(atapNo);
                        $('#car_type_container').hide();
                        $('#tran_type_container').show();
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
        $('#c_atap_no').val('');
        $('#c_name').val('').removeClass('glow-effect');
        $('#c_phase').val('').removeClass('glow-effect');
        $('#c_block').val('').removeClass('glow-effect');
        $('#c_lot').val('').removeClass('glow-effect');
        $('#c_or_amount').val('').removeClass('glow-effect');
    }

    function populateForm(data) {
        $('#c_name').val(data.c_name).addClass('glow-effect');
        $('#c_phase').val(data.c_phase).addClass('glow-effect');
        $('#c_block').val(data.c_block).addClass('glow-effect');
        $('#c_lot').val(data.c_lot).addClass('glow-effect');

        const formattedAmount = parseFloat(data.c_or_amount).toFixed(2);
        $('#c_or_amount').val(formattedAmount).addClass('glow-effect');

        setTimeout(function() {
            $('#c_name').removeClass('glow-effect');
            $('#c_phase').removeClass('glow-effect');
            $('#c_block').removeClass('glow-effect');
            $('#c_lot').removeClass('glow-effect');
            $('#c_or_amount').removeClass('glow-effect');
        }, 1000);
    }
});
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
            url: 'fetch_tran_type.php',
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