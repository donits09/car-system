<?php
session_start();

require_once('../../inc/check_session.php');
check_user_group(1);

include('../../config.php');

$atap_remarks = '';
$c_atap_no = null;
$c_phase = '';
$c_block = '';
$c_lot = '';
$c_name = '';
$c_encoded_by = '';
$c_tran_date = date('Y-m-d H:i:s');
$transaction_types = []; 
$payment_status = null;

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $get_atap_query = "SELECT 
        a.id, 
        a.c_account_no, 
        a.c_atap_no, 
        a.c_encoded_by,
        a.c_tran_date, 
        a.c_tran_updated, 
        a.atap_remarks, 
        a.status, 
        b.c_name, 
        b.c_phase,
        b.c_block, 
        b.c_lot, 
        SUM(c.c_atap_amount) AS total_amount
    FROM 
        t_atap a
    LEFT JOIN 
        t_other_atap b ON a.c_atap_no = b.c_atap_no 
    LEFT JOIN 
        t_atap_items c ON a.c_atap_no = c.c_atap_no
    WHERE 
        a.id = ?
    GROUP BY 
        a.id, 
        a.c_account_no, 
        a.c_atap_no, 
        a.c_encoded_by,
        a.c_tran_date, 
        a.c_tran_updated, 
        a.atap_remarks, 
        a.status, 
        b.c_name, 
        b.c_phase,
        b.c_block, 
        b.c_lot";
    $atapId = $_GET['id'];
    $atapNo = $_GET['no'];
    $stmt = odbc_prepare($conn, $get_atap_query);
    odbc_execute($stmt, array($atapId));

    if ($result = odbc_fetch_array($stmt)) {
        $c_atap_no = $result["c_atap_no"];
        $c_phase = $result["c_phase"];
        $c_block = $result["c_block"];
        $c_lot = $result["c_lot"];
        $c_name = $result["c_name"];
        $c_encoded_by = $result["c_encoded_by"];
        $c_tran_date = $result["c_tran_date"];
        $atap_remarks = $result["atap_remarks"];
    }

    $get_transaction_types_query = "SELECT * FROM t_atap_items WHERE c_atap_no = ?";
    $stmt_types = odbc_prepare($conn, $get_transaction_types_query);
    odbc_execute($stmt_types, array($atapNo));

    while ($row = odbc_fetch_array($stmt_types)) {
        $transaction_types[] = $row;
    }
} else if (isset($_GET['c_atap_no']) && $_GET['c_atap_no'] > 0) {
    $c_atap_no = $_GET['c_atap_no'];
}
?>
<style>
.bold-text {
    padding: 5px;
    font-size: 11px;
    font-style: italic;
}
.transaction-type {
    text-align: center;
}

.transaction-amount, .total-amount {
    text-align: right;
}

.total-amount {
    text-align: right;
}
#add-row{
    border-radius: 0px;
}
.remove-row{
    border-radius: 0px;
    text-align: center;
}
#btnsave{
    width: 100% !important;
}
</style>
<link rel="stylesheet" href="../../dist/css/manage_atap.css">
<body onload="">
    <form id="atap-form" method="post" action="">
        <input type="hidden" name="id" value="<?php echo isset($atapId) ? $atapId : '' ?>">
        <input type="hidden" id="c_atap_no" name="c_atap_no" value="<?php echo isset($c_atap_no) ? $c_atap_no : '' ?>">
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
                <input type="number" id="c_block" name="c_block" class="form-control" value="<?php echo htmlspecialchars($c_block); ?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="c_lot" class="control-label">Lot</label>
                <input type="number" id="c_lot" name="c_lot" class="form-control" value="<?php echo htmlspecialchars($c_lot); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="c_name" name="c_name" value="<?php echo htmlspecialchars($c_name) ?>" required>
        </div>
        <div class="form-group">
            <label for="remarks" class="form-label">
                Remarks 
            </label>
            <textarea class="form-control txt" rows="2" cols="50" id="atap_remarks" name="atap_remarks" required><?php echo htmlspecialchars($atap_remarks) ?></textarea>
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
        <div class="form-group">
            <table class="table table-striped" id="transaction-table">
                <thead>
                    <th>Transaction Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Action</th>
                </thead>
                <tbody>
                    <?php if (isset($transaction_types) && !empty($transaction_types)) : ?>
                        <?php foreach ($transaction_types as $key => $type) : ?>
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_car_type" name="c_car_type" value="<?php echo htmlspecialchars($type['c_tran_type']); ?>">
                                        <div class="dropdown-menu w-100" id="comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
                                            <?php
                                            $car_type_query = "SELECT DISTINCT c_payment_type, id, payment_status FROM t_car_type WHERE status = 0 ORDER BY id ASC";
                                            $type_result = odbc_exec($conn, $car_type_query);
                                            while ($row = odbc_fetch_array($type_result)) {
                                                echo "<a class='dropdown-item $selected' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
                                            }
                                            ?>
                                        </div>
                                        <input type="hidden" name="transaction_type[]" value="<?php echo htmlspecialchars($type['c_tran_type']); ?>">
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
                                </td>
                                <td>
                                    <span class="payment-status">
                                        <?php
                                        $c_payment = $type['c_tran_type'];
                                        $get_pstatus_qry = "SELECT * FROM t_car_type WHERE c_payment_type = '$c_payment'";
                                        $results = odbc_exec($conn, $get_pstatus_qry);

                                        if ($p_status = odbc_fetch_array($results)) {
                                            $pstatus = trim($p_status["payment_status"]); 
                                            $statusText = ''; 

                                            switch ($pstatus) {
                                                case 'C':
                                                    $statusText = '<span class="badge badge-secondary">CAR</span>';
                                                    break;
                                                case 'ST':
                                                    $statusText = '<span class="badge badge-secondary">Special</span>';
                                                    break;
                                                case 'O':
                                                    $statusText = '<span class="badge badge-secondary">OR</span>';
                                                    break;
                                                default:
                                                    $statusText = '<span class="badge badge-secondary">Other</span>';
                                                    break;
                                            }

                                            echo $statusText;
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <input type="number" name="transaction_amount[]" class="form-control transaction-amount" step="0.01" value="<?php echo htmlspecialchars(number_format((float)$type['c_atap_amount'], 2, '.', '')); ?>" required>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td>
                                <div class="dropdown">
                                    <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_car_type" name="c_car_type" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>">
                                    <div class="dropdown-menu w-100" id="comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
                                        <?php
                                        $car_type_query = "SELECT DISTINCT c_payment_type, id, payment_status FROM t_car_type WHERE status = 0 ORDER BY id ASC";
                                        $type_result = odbc_exec($conn, $car_type_query);
                                        while ($row = odbc_fetch_array($type_result)) {
                                            echo "<a class='dropdown-item $selected' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
                                        }
                                        ?>
                                    </div>
                                    <input type="hidden" name="transaction_type[]">
                                    <input type="hidden" name="payment_status[]">
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
                            </td>
                            <td>
                                <span class="payment-status-text"></span>
                            </td>
                            <td><input type="number" name="transaction_amount[]" class="form-control transaction-amount" step="0.01"></td>
                            <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <script>
                    document.querySelectorAll('.dropdown-item').forEach(function(item) {
                        item.addEventListener('click', function(e) {
                            //e.preventDefault();

                            var dropdownButton = this.closest('.dropdown').querySelector('.dropdown-toggle');
                            dropdownButton.textContent = this.textContent;

                            var hiddenInput = this.closest('.dropdown').querySelector('input[type="hidden"]');
                            hiddenInput.value = this.getAttribute('data-value');

                            var paymentStatusSpan = this.closest('tr').querySelector('.payment-status');
                            var status = this.getAttribute('data-status').trim(); 
                            var statusText = '';

                            console.log('Selected status:', status);

                            switch (status) {
                                case 'C':
                                    statusText = '<span class="badge badge-secondary">CAR</span>';
                                    break;
                                case 'ST':
                                    statusText = '<span class="badge badge-secondary">Special</span>';
                                    break;
                                case 'O':
                                    statusText = '<span class="badge badge-secondary">OR</span>';
                                    break;
                                default:
                                    statusText = '<span class="badge badge-secondary">Other</span>';
                                    break;
                            }
                            paymentStatusSpan.innerHTML = statusText;
                        });
                    });
                </script>
                <script>
                    $(document).ready(function() {
                        $('.dropdown-menu a').click(function(event) {
                            //event.preventDefault();
                            var $dropdown = $(this).closest('.dropdown');
                            var $button = $dropdown.find('.dropdown-toggle');
                            var $hiddenInput = $dropdown.find('input[name="transaction_type[]"]');
                            var $hiddenStatusInput = $dropdown.find('input[name="payment_status[]"]');
                            var $statusText = $dropdown.closest('tr').find('.payment-status-text');
                            var paymentStatus = $(this).data('status');

                            console.log("Selected Payment Status:", paymentStatus);

                            $button.text($(this).text());
                            $hiddenInput.val($(this).data('value'));
                            $hiddenStatusInput.val(paymentStatus);

                            if (paymentStatus.trim() == 'C') {
                                $statusText.html('<span class="badge badge-secondary">CAR</span>');
                            } else if (paymentStatus.trim() == 'S') {
                                $statusText.html('<span class="badge badge-secondary">Special</span>');
                            } else if (paymentStatus.trim() == 'O') {
                                $statusText.html('<span class="badge badge-secondary">OR</span>');
                            } else {
                                $statusText.html('<span class="badge badge-secondary">Other</span>');
                            }

                            $dropdown.find('.dropdown-item').removeClass('active');
                            $(this).addClass('active');
                        });
                    });
                </script>
                <tfoot>
                    <tr>
                        <th colspan="1" style="text-align:right;">
                            <button type="button" class="btn btn-sm btn-info" id="add-row"><i class="fas fa-add"></i> Add Row</button> Total:
                        </th>
                        <th></th>
                        <th id="total-amount" class="total-amount">0.00</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

        </div>
        <button type="submit" class="btn btn-primary" id="btnsave">Save</button>
    </form>
</body>
<script>
    function calculateTotal() {
        let total = 0;
        var inputs = document.querySelectorAll('.transaction-amount');
        for (var i = 0; i < inputs.length; i++) {
            let value = parseFloat(inputs[i].value.replace(/,/g, ''));
            if (!isNaN(value)) {
                total += value;
            }
        }

        function formatNumberWithCommas(number) {
            var parts = number.toFixed(2).split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return parts.join('.');
        }

        console.log('Total:', total);
        document.getElementById('total-amount').textContent = formatNumberWithCommas(total);
    }

    document.addEventListener('DOMContentLoaded', function() {
        var inputs = document.querySelectorAll('.transaction-amount');
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].addEventListener('input', calculateTotal);
        }

        calculateTotal();
    });
</script>
<script>
    var dropdownOptions = `
        <?php
        $car_type_query = "SELECT DISTINCT c_payment_type, id, payment_status FROM t_car_type WHERE status = 0 ORDER BY id ASC";
        $type_result = odbc_exec($conn, $car_type_query);
        while ($row = odbc_fetch_array($type_result)) {
            echo "<a class='dropdown-item $selected' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
        }
        ?>
    `;
</script>
<script>
    $(document).ready(function() {
        function formatNumber(number) {
            return number.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function calculateTotal() {
            let total = 0;
            $('#transaction-table .transaction-amount').each(function() {
                let amount = parseFloat($(this).val().replace(/,/g, '')) || 0;
                total += amount;
            });
            $('#total-amount').text(formatNumber(total.toFixed(2)));
        }

        function checkRemoveButton() {
            let rowCount = $('#transaction-table tbody tr').length;
            $('#transaction-table .remove-row').prop('disabled', rowCount <= 1);
        }

        function initializeDropdown() {
            $('.dropdown-menu .dropdown-item').off('click').on('click', function(event) {
                //event.preventDefault();
                var $dropdown = $(this).closest('.dropdown');
                var $input = $dropdown.find('input[name="c_car_type"]');
                var $hiddenInput = $dropdown.find('input[name="transaction_type[]"]');
                var $hiddenStatusInput = $dropdown.find('input[name="payment_status[]"]');
                var $statusText = $dropdown.closest('tr').find('.payment-status-text');
                var paymentStatus = $(this).data('status');
                $input.val($(this).data('value'));
                $hiddenInput.val($(this).data('value'));
                $hiddenStatusInput.val(paymentStatus);

                if (paymentStatus.trim() == 'C') {
                    $statusText.html('<span class="badge badge-secondary">CAR</span>');
                } else if (paymentStatus.trim() == 'S') {
                    $statusText.html('<span class="badge badge-secondary">Special</span>');
                } else if (paymentStatus.trim() == 'O') {
                    $statusText.html('<span class="badge badge-secondary">OR</span>');
                } else {
                    $statusText.html('<span class="badge badge-secondary">Other</span>');
                }

                $dropdown.find('.dropdown-item').removeClass('active');
                $(this).addClass('active');

                $dropdown.find('.dropdown-menu').hide();
            });

            $('.dropdown input[name="c_car_type"]').off('input').on('input', function () {
                var input = $(this).val().toLowerCase();
                var $menu = $(this).siblings('.dropdown-menu');
                var hasVisibleOptions = false;
                $menu.find('.dropdown-item').each(function () {
                    if ($(this).text().toLowerCase().startsWith(input)) {
                        $(this).show();
                        hasVisibleOptions = true;
                    } else {
                        $(this).hide();
                    }
                });

                $menu.toggle(hasVisibleOptions);
            });

            $('.dropdown input[name="c_car_type"]').off('focus click').on('focus click', function () {
                var $menu = $(this).siblings('.dropdown-menu');
                $menu.show();
            });

            // $(document).off('click.dropdown').on('click.dropdown', function (e) {
            //     if (!$(e.target).closest('.dropdown').length) {
            //         $('.dropdown-menu').hide();
            //     }
            // });

            function positionDropdown() {
                $('.dropdown').each(function () {
                    var $input = $(this).find('input[name="c_car_type"]');
                    var $menu = $(this).find('.dropdown-menu');
                    var inputOffset = $input.offset();
                    $menu.css({
                        top: inputOffset.top + $input.outerHeight(),
                        left: inputOffset.left,
                        width: $input.outerWidth()
                    });
                });
            }

            $(window).on('resize', positionDropdown);
            positionDropdown();
        }

        $('#transaction-table').on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateTotal();
            checkRemoveButton();
        });

        $('#add-row').on('click', function() {
            let newRow = `<tr>
                <td>
                    <div class="dropdown">
                        <input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" name="c_car_type" placeholder="Type or select an option" autocomplete="off">
                        <div class="dropdown-menu w-100" style="max-height: 200px; overflow-y: auto;">
                            ${dropdownOptions}
                        </div>
                        <input type="hidden" name="transaction_type[]">
                        <input type="hidden" name="payment_status[]">
                    </div>
                </td>
                <td>
                    <span class="payment-status-text"></span>
                </td>
                <td><input type="number" name="transaction_amount[]" class="form-control transaction-amount" step="0.01" required></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>`;
            $('#transaction-table tbody').append(newRow);
            initializeDropdown();
            calculateTotal();
            checkRemoveButton();
        });

        $('#transaction-table').on('input', '.transaction-amount', function() {
            calculateTotal();
        });

        $('#atap-form').submit(function(e) {
            e.preventDefault();

            if ($(this).data('formSubmitting')) return;

            $(this).data('formSubmitting', true);

            start_loader();

            $.ajax({
                url: "../../classes/Master.php?f=save_other_atap_payment",
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
                    $('#atap-form').data('formSubmitting', false);
                },
                success: function(resp) {
                    console.log(resp);
                    if (resp && resp.status === 'success') {
                        alert_toast(resp.msg, 'success');
                        setTimeout(function() {
                            $('#createAtapModal').modal('hide');
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
                    $('#atap-form').data('formSubmitting', false);
                }
            });
        });

        initializeDropdown();
        calculateTotal();
        checkRemoveButton();
    });
    $(document).ready(function() {
        calculateTotal();
    });
</script>
