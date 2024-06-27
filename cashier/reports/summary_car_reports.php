<?php
session_start();
/* if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != 3) {
    require_once('../logout.php');
    exit();
} */

require_once('../../inc/check_session.php');
check_user_group(3);

include('../../config.php');
include('../../inc/navbar.php');
include('../../inc/header.php');
$current_date = date('Y-m-d');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="<?php echo base_url; ?>dist/css/jquery-ui.css" rel="stylesheet">
    <script src="<?php echo base_url; ?>dist/js/jquery-3.5.1.min.js"></script>
    <script src="<?php echo base_url; ?>dist/js/jquery-ui.min.js"></script> -->


    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/car_reports.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
    <style>
        .form-control.tbl-input{
            background-color: transparent;
            border: none;
            text-align: center;
            cursor: default;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="card mt-3">
            <div class="main_header">
                <div id="header">ASIAN LAND STRATEGIES CORPORATION</div>
                <div id="subheader">SUMMARY REPORT</div>
                <!-- <div id="current_date"><?php echo date("Y-m-d"); ?></div> -->
            </div>
            <hr>
            <!-- <div class="sub_container">
                <div class="date_container">
                    <b>Search by Transaction Date</b><hr>
                    <div class="pd-20">
                        <label for="start_date">Start Date:</label>
                        <input type="text" id="start_date" class="form-control datepicker" value="<?php echo date('m/d/Y'); ?>" />
                        <label for="end_date" class="mt-2">End Date:</label>
                        <input type="text" id="end_date" class="form-control datepicker" value="<?php echo date('m/d/Y'); ?>" />
                        <button id="filter" class="btn btn-primary mt-2"><span class="fa fa-filter"></span> Filter</button>
                        <button id="reset" class="btn btn-secondary mt-2"><span class="fa fa-refresh"></span> Reset</button>
                    </div>
                </div>
                <div class="btn_container">
                    <button id="export_pdf" class="btn btn-danger mt-2" href="javascript:void(0)"><span class="fa fa-download"></span> Export as PDF</button>
                    <button id="export_csv" class="btn btn-flat btn-success mt-2" href="javascript:void(0)"><span class="fa fa-download"></span> Export as CSV</button>
                </div>
            </div> -->
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
<!-- <script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'mm/dd/yy',
            autoclose: true,
            todayHighlight: true
        });

        $('#filter').click(function() {
            let startDate = parseDate($('#start_date').val());
            let endDate = parseDate($('#end_date').val());
            let rows = $('#summary-type-body tr');

            rows.each(function() {
                let dateText = $(this).find('.tran-date').text().trim();
                let payDate = parseYMDDate(dateText);

                if ((isNaN(startDate.getTime()) || payDate >= startDate) && (isNaN(endDate.getTime()) || payDate <= endDate)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('#reset').click(function() {
            $('#start_date').val(formatDate(new Date()));
            $('#end_date').val(formatDate(new Date()));
            $('#summary-type-body tr').show();
        });

        function parseDate(dateString) {
            let parts = dateString.split('/');
            return new Date(parts[2], parts[0] - 1, parts[1]);
        }

        function parseYMDDate(dateString) {
            let parts = dateString.split('-');
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        function formatDate(date) {
            let month = ('0' + (date.getMonth() + 1)).slice(-2);
            let day = ('0' + date.getDate()).slice(-2);
            let year = date.getFullYear();
            return month + '/' + day + '/' + year;
        }
    });
</script> -->

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

        function unlock_summary(sum_id, sum_trandate, sum_car_no) {
            start_loader();
            $.ajax({
                url: "../../classes/Master.php?f=unlock_trans",
                method: "POST",
                data: { id: sum_id, tran_date: sum_trandate, car_no: sum_car_no },
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
