<?php 
    session_start();
    
    require_once('../../inc/check_session.php');
    check_user_group(3);

    include('../../config.php');

    $c_name = '';
    $c_loc = '';
    $c_car_type = '';
    $c_car_amount = 0;
    $c_car_no = '';
    $c_car_paydate = date('Y-m-d');
    $c_encoded_by = '';
    $c_tran_date = date('Y-m-d H:i:s');
    $c_mop = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_car_query = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                    a.c_car_paydate,a.c_car_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop, b.c_name, b.c_phase,
                    b.c_block, b.c_lot
                        FROM t_car_payment a
                        LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no WHERE a.id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_car_query);
        odbc_execute($stmt, array($accountId));

        if ($result = odbc_fetch_array($stmt)) {
            $c_name = $result["c_name"];
            $c_phase = $result["c_phase"];
            $c_block = $result["c_block"];
            $c_lot = $result["c_lot"];
            $c_car_type = $result["c_car_type"];
            $c_car_amount = $result["c_car_amount"];
            $c_car_no = $result["c_car_no"];
            $c_car_paydate = $result["c_car_paydate"];
            $c_mop = $result["c_mop"];
            $c_encoded_by = $result["c_encoded_by"];
        }
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
<form id="other-car-form">
    <input type="hidden" id="id" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="c_name" name="c_name" value="<?php echo htmlspecialchars($c_name); ?>" oninput="validateAlphaNumericInput(event)" required>
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
        <label for="c_car_type">Payment Type</label>
        <div class="dropdown">
            <input type="text" class="form-control" id="c_car_type" name="c_car_type" placeholder="Type or select an option" autocomplete="off" oninput="validateAlphaNumericInput(event)" value="<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.dropdown-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    var value = this.getAttribute('data-value');
                    document.getElementById('c_car_type').value = value;
                    document.getElementById('comboBoxMenu').style.display = 'none';
                });
            });

            document.getElementById('c_car_type').addEventListener('focus', function() {
                document.getElementById('comboBoxMenu').style.display = 'block';
            });

            document.getElementById('c_car_type').addEventListener('blur', function() {
                setTimeout(function() {
                    document.getElementById('comboBoxMenu').style.display = 'none';
                }, 200);
            });
        });

        function validateForm() {
            var carTypeInput = document.getElementById('c_car_type');
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
        <label for="amount">Amount</label>
        <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo number_format(htmlspecialchars($c_car_amount),2); ?>" oninput="validateNumberInputAmt(event)" required>
        <div id="car_amt_error"></div>
    </div>
    <div class="form-group">
        <label for="car_no">CAR No.</label>
        <input type="number" class="form-control" id="c_car_no" name="c_car_no" value="<?php echo htmlspecialchars($c_car_no); ?>" maxlength="6" minlength="6" pattern="\d{6}" oninput="validateNumberInput(event)" required>
        <div id="car_no_error"></div>
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
        <input type="date" class="form-control" id="c_car_paydate" name="c_car_paydate" value="<?php echo htmlspecialchars($c_car_paydate) ?>" min="1990-01-01" max="<?php echo date('Y-m-d'); ?>"  required>
    </div>
    <div class="form-group">
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
    <div class="form-group hidden_fields">
        <label for="encoder">Transaction date</label>
        <input type="text" class="form-control" id="c_tran_date" name="c_tran_date" value="<?php echo  htmlspecialchars($c_tran_date) ?>" readonly>
    </div>
   
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<script src="../../dist/js/manage_car.js"></script>
<script>
$(document).ready(function() {
    $('#other-car-form').on('submit', function(e) {
        e.preventDefault(); 
        var carNo = $('#c_car_no').val();
        const carAmount = parseFloat($('#c_car_amount').val().replace(/,/g, ''));
        let valid = true;
        if (carNo.length < 6) {
            $('#car_no_error').text('CAR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
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
            url: "../../classes/Master.php?f=save_other_car_payment",
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

    $('#c_car_no').on('input', function() {
        const carNo = $(this).val();

        if (carNo.length < 6) {
            $('#car_no_error').text('CAR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            $('#other-car-form button[type="submit"]').attr('disabled', true);
        } else if (carNo.length > 6) {
            $('#car_no_error').text('CAR No. exceeds 6 digits.').addClass('bold-text').css('color', 'blue');
            $('#other-car-form button[type="submit"]').attr('disabled', false);
        } else {
            $.ajax({
                type: 'POST',
                url: '../../admin/car/check_car_no.php',
                data: { car_no: carNo },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        $('#car_no_error').text('CAR No. already exists.').addClass('bold-text').css('color', 'red');
                        $('#other-car-form button[type="submit"]').attr('disabled', true);
                    } else {
                        $('#car_no_error').text('').removeClass('bold-text');
                        $('#other-car-form button[type="submit"]').attr('disabled', false);
                    }
                }
            });
        }
    });

    $('#other-car-form').on('submit', function(e) {
        if ($('#car_no_error').text().includes('must be 6 digits')) {
            e.preventDefault();
        }
    });
});
</script>