<?php
session_start();
require_once('../../inc/check_session.php');
check_user_group(2);
include('../../config.php');
include('../../inc/navbar.php');
include('../../inc/header.php');
$current_date = date('Y-m-d');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css"> -->
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/car_reports.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
    <style>
        .form-control.tbl-input{
            background-color: transparent;
            border: none;
            text-align: center;
            cursor: default;
        }
        *{
            font-size:12px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card mt-3">
            <div class="main_header" style="padding:10px;">
                <div id="header">ASIAN LAND STRATEGIES CORPORATION</div>
                <div id="subheader">SUMMARY REPORT</div>
            </div>
            <hr>
            <hr>
            <div class="table-container">
                <table class="table table-bordered table-striped" id="car-table">
                    <thead>
                        <tr>
                            <th>Transaction Date</th>
                            <th>Total Cash</th>
                            <th>Total Check</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="summary-type-body">
                        <?php
                        $car_list_query = "SELECT DISTINCT DATE(c_tran_date) AS c_tran_date_without_time FROM t_car_payment ORDER BY c_tran_date_without_time DESC;";
                        $stmt = odbc_prepare($conn, $car_list_query);
                        if ($stmt && odbc_execute($stmt)) {
                            while ($row_date = odbc_fetch_array($stmt)):
                                $trandate = $row_date['c_tran_date_without_time'];

                                $car_count_date = "SELECT COUNT(*) AS count FROM t_summary_reports WHERE DATE(tran_date) = ? AND status = 1";
                                $stmt_count = odbc_prepare($conn, $car_count_date);

                                if (!$stmt_count) {
                                    die("Failed to prepare SQL: " . odbc_errormsg());
                                }
                                if (odbc_execute($stmt_count, array($trandate))) {
                                    $result_count = odbc_fetch_array($stmt_count);
                                    $isLocked = ($result_count['count'] > 0);
                                } else {
                                    die("Query execution failed: " . odbc_errormsg());
                                }
                                $car_cash_query = "SELECT SUM(c_car_amount) AS cash_total FROM t_car_payment WHERE c_mop = 1 AND status = 0 AND DATE(c_tran_date) = ?";
                                $stmt_cash = odbc_prepare($conn, $car_cash_query);
                                $cash_amt = 0;

                                if ($stmt_cash && odbc_execute($stmt_cash, array($trandate))) {
                                    $row_cash = odbc_fetch_array($stmt_cash);
                                    if ($row_cash) {
                                        $cash_amt = $row_cash['cash_total'];
                                    }
                                } else {
                                    echo "Error executing cash query.";
                                }

                                $car_check_query = "SELECT SUM(c_car_amount) AS check_total FROM t_car_payment WHERE c_mop = 2 AND status = 0 AND DATE(c_tran_date) = ?";
                                $stmt_check = odbc_prepare($conn, $car_check_query);
                                $check_amt = 0;

                                if ($stmt_check && odbc_execute($stmt_check, array($trandate))) {
                                    $row_check = odbc_fetch_array($stmt_check);
                                    if ($row_check) {
                                        $check_amt = $row_check['check_total'];
                                    }
                                } else {
                                    echo "Error executing check query.";
                                }

                                $total = $cash_amt + $check_amt;
                        ?>
                                <tr>
                                    <td class="text-center tran-date"><?php echo htmlspecialchars($trandate); ?></td>
                                    <td class="text-center"><input type="text" class="form-control tbl-input" id="cash_total" name="cash_total" value="<?php echo number_format($cash_amt, 2); ?>" readonly></td>
                                    <td class="text-center"><input type="text" class="form-control tbl-input" id="check_total" name="check_total" value="<?php echo number_format($check_amt, 2); ?>" readonly></td>
                                    <td class="text-center"><input type="text" class="form-control tbl-input" id="total" name="total" value="<?php echo number_format($total, 2); ?>" readonly></td>
                                    <td><?php echo $isLocked ? "LOCKED" : "-----------"; ?></td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" <?php echo $isLocked ? 'disabled' : ''; ?>>
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <?php if (!$isLocked): ?>
                                                    <a class="dropdown-item lock_data" href="javascript:void(0)" data-date="<?php echo htmlspecialchars($trandate); ?>" data-cash="<?php echo htmlspecialchars($cash_amt); ?>" data-check="<?php echo htmlspecialchars($check_amt); ?>" data-total="<?php echo htmlspecialchars($total); ?>">
                                                        <span class="fa fa-lock text-primary"></span> Lock
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            endwhile;
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No data available or error executing query.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="confirm_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm_modal_label">Confirmation</h5>
                <button onclick="closeModal()" class="btn customized-modal" data-dismiss="modal">x</button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="../../dist/js/table.js"></script>
<script>
$(document).ready( function () {
    $('#car-table').DataTable();
    } );
    $(document).ready(function() {
        $(document).on('click', '.lock_data', function() {
            var sum_trandate = $(this).data('date');
            var sum_cash = $(this).data('cash');
            var sum_check = $(this).data('check');
            var sum_total = $(this).data('total');
            _conf("Are you sure you want to lock this summary report?", lock_summary, [sum_trandate, sum_cash, sum_check, sum_total]);
        });
        window._conf = function(msg, func, params) {
            $('#confirm_modal .modal-body').html(msg);
            $('#confirm_modal #confirm').off('click').on('click', function() {
                func.apply(this, params);
            });
            $('#confirm_modal').modal('show');
        };
        function lock_summary(sum_trandate, sum_cash, sum_check, sum_total) {
            start_loader();
            $.ajax({
                url: "../../classes/Master.php?f=save_locked_trans",
                method: "POST",
                data: { tran_date: sum_trandate, total_cash: sum_cash, total_check: sum_check, total: sum_total },
                dataType: "json",
                error: function(err) {
                    console.log("AJAX error: ", err);
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (resp && resp.status === 'success') {
                        alert_toast(resp.msg, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else if (resp && resp.status === 'failed' && resp.err) {
                        alert_toast("An error occurred: " + resp.err, 'error');
                    } else {
                        alert_toast("An unexpected error occurred", 'error');
                    }
                    end_loader();
                }
            });
        }
    });
</script>
<?php include('../../inc/footer.php'); ?>
