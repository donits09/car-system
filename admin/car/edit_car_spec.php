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
<div class="container-fluid">
    <div class="callout callout-primary">
        <table class="table table-bordered" style="text-align:left;">
            <tbody>
                <tr>
                    <th style="width: 30%;">Account No.:</th>
                    <td>
                        <input type="text" name="c_account_no" value="<?php echo !empty($firstEntry['c_account_no']) ? htmlspecialchars($firstEntry['c_account_no']) : ''; ?>" class="form-control">
                    </td>
                </tr>
                <tr>
                    <th>Car No.:</th>
                    <td><input type="text" name="c_car_no" value="<?php echo htmlspecialchars($firstEntry['c_car_no']); ?>" class="form-control"></td>
                </tr>
                <tr>
                    <th>Payment Type & Amount Breakdown:</th>
                    <td>
                        <table class="table table-sm" id="transaction-table">
                            <thead>
                                <tr>
                                    <th>Transaction Name</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($carData) && !empty($carData)) : ?>
                                    <?php foreach ($carData as $car) : ?>
                                        <?php
                                            $carTypes = explode(',', $car['c_car_type']);
                                            $carAmounts = explode(',', $car['c_car_amount']);
                                        ?>
                                        <?php foreach ($carTypes as $index => $type) : ?>
                                            <tr>
                                                <td>
                                                    <div class="dropdown">
                                                        <input type="text" class="form-control c_car_type" oninput="validateAlphaNumericInput(event)" name="c_car_type[]" value="<?php echo htmlspecialchars(trim($type)); ?>">
                                                        <div class="dropdown-menu w-100 comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
                                                            <?php
                                                            $car_type_query = "SELECT DISTINCT c_payment_type, id, payment_status FROM t_car_type WHERE status = 0 ORDER BY id ASC";
                                                            $type_result = odbc_exec($conn, $car_type_query);
                                                            while ($row = odbc_fetch_array($type_result)) {
                                                                echo "<a class='dropdown-item' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
                                                            }
                                                            ?>
                                                        </div>
                                                        <input type="hidden" name="transaction_type[]" value="<?php echo htmlspecialchars($type); ?>">
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="payment-status">
                                                        <?php
                                                        $c_payment = $type;
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
                                                    <input type="number" name="car_amount[]" class="form-control transaction-amount" step="0.01" value="<?php echo number_format((float)$carAmounts[$index], 2, '.', ''); ?>" required>
                                                </td>
                                                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <input type="text" class="form-control c_car_type" oninput="validateAlphaNumericInput(event)" name="c_car_type[]" placeholder="Type or select an option" autocomplete="off">
                                                <div class="dropdown-menu w-100 comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
                                                    <?php
                                                    $car_type_query = "SELECT DISTINCT c_payment_type, id, payment_status FROM t_car_type WHERE status = 0 ORDER BY id ASC";
                                                    $type_result = odbc_exec($conn, $car_type_query);
                                                    while ($row = odbc_fetch_array($type_result)) {
                                                        echo "<a class='dropdown-item' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
                                                    }
                                                    ?>
                                                </div>
                                                <input type="hidden" name="transaction_type[]">
                                                <input type="hidden" name="payment_status[]">
                                            </div>
                                        </td>
                                        <td><span class="payment-status-text"></span></td>
                                        <td><input type="number" name="transaction_amount[]" class="form-control transaction-amount" step="0.01"></td>
                                        <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
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
                    <th>Mode of Payment:</th>
                    <td>
                        <select name="c_mop" class="form-control">
                            <option value="1" <?php echo ($firstEntry['c_mop'] == 1) ? 'selected' : ''; ?>>Cash</option>
                            <option value="2" <?php echo ($firstEntry['c_mop'] == 2) ? 'selected' : ''; ?>>Check</option>
                            <option value="3" <?php echo ($firstEntry['c_mop'] == 3) ? 'selected' : ''; ?>>Online</option>
                        </select>
                    </td>
                </tr>
                <?php if ($firstEntry['c_mop'] == 2 || $firstEntry['c_mop'] == 3): ?>
                <tr>
                    <th>Issuance Bank:</th>
                    <td><input type="text" name="c_bank" value="<?php echo htmlspecialchars($firstEntry['c_bank']); ?>" class="form-control"></td>
                </tr>
                <tr>
                    <th><?php echo $firstEntry['c_mop'] == 2 ? 'Check No' : 'Reference No'; ?>:</th>
                    <td><input type="text" name="c_check_no" value="<?php echo htmlspecialchars($firstEntry['c_check_no']); ?>" class="form-control"></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Transaction Date:</th>
                    <td><input type="date" name="c_tran_date" value="<?php echo htmlspecialchars((new DateTime($firstEntry['c_tran_date']))->format('Y-m-d')); ?>" class="form-control"></td>
                </tr>
                <tr>
                    <th>Remarks:</th>
                    <td><input type="text" name="c_remarks" value="<?php echo htmlspecialchars($firstEntry['c_remarks']); ?>" class="form-control" style="max-width: 200px;"></td>
                </tr>
                <tr>
                    <th>Pay Date:</th>
                    <td><input type="date" name="c_car_paydate" value="<?php echo htmlspecialchars($firstEntry['c_car_paydate']); ?>" class="form-control"></td>
                </tr>
                <tr>
                    <th>Encoded by:</th>
                    <?php
                        $c_encoded_by =  $firstEntry['c_encoded_by']; 
                        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                        $results = odbc_exec($conn, $get_encoder_details_qry);

                        if ($encoder = odbc_fetch_array($results)) {
                            $realname = $encoder["c_realname"];
                        }
                    ?>
                    <td><?php echo $realname; ?></td>
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
<script>
$(document).ready(function() {
    const dropdownOptions = `<?php
    $car_type_query = "SELECT DISTINCT c_payment_type, id, payment_status FROM t_car_type WHERE status = 0 ORDER BY id ASC";
    $type_result = odbc_exec($conn, $car_type_query);
    while ($row = odbc_fetch_array($type_result)) {
        echo "<a class='dropdown-item' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "' data-status='" . htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
    }
    ?>`;

    function initializeDropdown() {
        $(document).on('click', '.dropdown-menu a', function() {
            const $dropdown = $(this).closest('.dropdown');
            const $input = $dropdown.find('input[name="c_car_type"]');
            const $hiddenInput = $dropdown.find('input[name="transaction_type[]"]');
            const $hiddenStatusInput = $dropdown.find('input[name="payment_status[]"]');
            const $statusText = $dropdown.closest('tr').find('.payment-status-text');
            const paymentStatus = $(this).data('status');
            $input.val($(this).data('value'));
            $hiddenInput.val($(this).data('value'));
            $hiddenStatusInput.val(paymentStatus);

            const statusText = {
                'C': '<span class="badge badge-secondary">CAR</span>',
                'ST': '<span class="badge badge-secondary">Special</span>',
                'O': '<span class="badge badge-secondary">OR</span>',
                '': '<span class="badge badge-secondary">Other</span>'
            }[paymentStatus.trim()] || '<span class="badge badge-secondary">Other</span>';
            $statusText.html(statusText);

            $dropdown.find('.dropdown-item').removeClass('active');
            $(this).addClass('active');
            $dropdown.find('.dropdown-menu').hide();
        });

        $(document).on('input', '.dropdown input[name="c_car_type"]', function () {
            const input = $(this).val().toLowerCase();
            const $menu = $(this).siblings('.dropdown-menu');
            $menu.find('.dropdown-item').each(function () {
                $(this).toggle($(this).text().toLowerCase().startsWith(input));
            });
            $menu.toggle($menu.find('.dropdown-item:visible').length > 0);
        });

        $(document).on('focus click', '.dropdown input[name="c_car_type"]', function () {
            $(this).siblings('.dropdown-menu').show();
        });
    }

    $('#add-row').on('click', function() {
        $('#transaction-table tbody').append(`
            <tr>
                <td>
                    <div class="dropdown">
                        <input type="text" class="form-control c_car_type" name="c_car_type[]" placeholder="Type or select an option" autocomplete="off">
                        <div class="dropdown-menu w-100 comboBoxMenu" style="max-height: 200px; overflow-y: auto;">
                            ${dropdownOptions}
                        </div>
                        <input type="hidden" name="transaction_type[]">
                        <input type="hidden" name="payment_status[]">
                    </div>
                </td>
                <td><span class="payment-status-text"></span></td>
                <td><input type="number" name="transaction_amount[]" class="form-control transaction-amount" step="0.01" required></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>`);
        initializeDropdown();  
        calculateTotal();
        checkRemoveButton();
    });

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        initializeDropdown();
        calculateTotal();
        checkRemoveButton();
    });

    function checkRemoveButton() {
        $('#transaction-table .remove-row').prop('disabled', $('#transaction-table tbody tr').length <= 1);
    }

    function calculateTotal() {
        let total = 0;
        $('.transaction-amount').each(function() {
            const amount = parseFloat($(this).val());
            if (!isNaN(amount)) total += amount;
        });
        $('#total-amount').text(total.toFixed(2));
    }

    initializeDropdown(); 
});
</script>
