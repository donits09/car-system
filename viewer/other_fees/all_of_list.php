<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(4);

include('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     
?>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css">
<style>
    .green-row {
        background-color: #cbd2d9 !important;
        /* color:white !important; */
        font-weight: bold !important;
        font-style: italic;
    }
    .btn.btn-flat.btn-default.btn-sm.dropdown-toggle.dropdown-icon {
        margin: 0; 
        padding: 5px 10px; 
        width: auto; 
    }
</style>
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <div class="pd-20">
        <h2 class="text-blue h4">Other Fees - Full List</h2>
        <hr>
        </div>
            <div class="table-container">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Account No.</th>
                        <th>OR No.</th>
                        <th>Transaction Type</th>
                        <th>Remarks</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Amount</th>
                        <th>Pay Date</th>
                        <th>Encoder</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="car-type-body">
                        <?php
                        $username = $_SESSION['username'];

                        $or_list = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                    a.c_or_paydate,a.c_or_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop,a.c_bank, b.c_name, b.c_phase,
                    b.c_block, b.c_lot, a.e_status, a.c_remarks
                        FROM t_or_payment a
                        LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no WHERE status != 1
                        ORDER BY a.c_tran_updated DESC";
                        $stmt = odbc_prepare($conn, $or_list);

                        $result = odbc_execute($stmt, array($username));

                        if ($result === false) {
                            echo "<tr><td colspan='13' class='text-center'>No data available or error executing query.</td></tr>";
                        } else {
                            $i = 1;
                            while ($row = odbc_fetch_array($stmt)) {
                                $row_class = $row['e_status'] == 1 ? 'green-row' : '';
                        ?>
                                <tr class="<?php echo $row_class; ?>">
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars(!empty($row['c_account_no']) ? $row['c_account_no'] : '----------'); ?>
                                    </td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_or_no']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_or_type']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_remarks']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';

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
                                            echo htmlspecialchars($row['c_name']);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $c_account_no = $row['c_account_no'];

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
                                                $c_phase = $row['c_phase'];
                                                $c_block = $row['c_block'];
                                                $c_lot = $row['c_lot'];

                                                if (empty($c_phase) && empty($c_block) && empty($c_lot)) {
                                                    echo "-------------";
                                                } else {
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
                                                }
                                            }
                                        } catch (Exception $e) {
                                            echo "-----";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo number_format($row['c_or_amount'], 2); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_or_paydate']); ?></td>
                                    <td class="text-center">
                                        <?php
                                        $c_encoded_by = $row['c_encoded_by'];
                                        $get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
                                        $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);

                                        if (odbc_execute($encoder_stmt, array($c_encoded_by)) && $encoder = odbc_fetch_array($encoder_stmt)) {
                                            echo htmlspecialchars($encoder["c_realname"]);
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action <?php if ($row['e_status'] == 1) { echo '<span class="fa fa-lock"></span>'; } ?>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                                <a class="dropdown-item view_or" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
                                                <?php if ($row['c_encoded_by'] == $username){ ?>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="<?php echo base_url ?>print/print_or.php?id=<?php echo htmlspecialchars($row['c_or_no']); ?>" target="_blank">Print</a>
                                                <?php }; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-right" id="totalAmt">Total amount:</th>
                        <th id="totalAmount" class="text-center"></th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php include ('../modals/main_modals.php'); ?>
    </div>
</div>
<script src="../../dist/js/table.js"></script>
<script src="../../dist/js/of_js/all_of_list.js"></script>
<script src="../../dist/js/export_scripts.js"></script>
<!-- <script src="../../dist/js/manage_car.js"></script> -->
<script>

$(document).ready(function() {
    function updateAccountNo() {
        var accountNo = $('#buyer_acc_no').val();
        $('#create_new_of').attr('data-account-no', accountNo);
    }
    updateAccountNo();
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        updateAccountNo();
    });

    $('#c_account_no').on('input', function() {
        const accountNo = $(this).val();
        const buyerNameField = $('#buyer_name');

        if (accountNo.length > 0) {
            $.ajax({
                type: 'POST',
                url: '../../viewer/car/get_buyer_details.php',
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
    });
    var table = $('#data-table').DataTable();
    function calculateTotalAmount() {
        let totalAmount = 0;
        table.rows({ filter: 'applied' }).every(function(rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            var amount = parseFloat(data[7].replace(/,/g, ''));
            if (!isNaN(amount)) {
                totalAmount += amount;
            }
        });
        $('#totalAmount').text(totalAmount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
    }
    calculateTotalAmount();
    table.on('draw', function() {
        calculateTotalAmount();
    });
});
</script>
</div>
</body>
<?php include('../../inc/footer.php'); ?>
