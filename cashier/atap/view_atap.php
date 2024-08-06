<?php
session_start();
include('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $atapId = $_GET['id'];
    $get_atap = "SELECT 
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
    $stmt = odbc_prepare($conn, $get_atap);
    odbc_execute($stmt, array($atapId));
    $row = odbc_fetch_array($stmt);

    if ($row) {
?>
        <link rel="stylesheet" href="../../dist/css/view_car.css">
        <div class="container-fluid">
            <div class="callout callout-primary">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 30%;">Account No.:</th>
                            <?php if ($row['c_account_no'] != "" || $row['c_account_no'] != null) { ?>
                                <td><?php echo $row['c_account_no']; ?></td>
                            <?php }else{ ?>
                                <td>----------</td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <th style="width: 30%;">ATAP No.:</th>
                            <td><?php echo htmlspecialchars($row['c_atap_no'] ?: '----------'); ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Name:</th>
                            <?php
                                $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';

                                if (!empty($c_buyer_acc)) {
                                    $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
                                    $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
                                    
                                    if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                                        $buyer_details = odbc_fetch_array($buyer_stmt);
                                        
                                        if ($buyer_details) {
                                            echo '<td>' . htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]) . '</td>';
                                        } else {
                                            echo "<td>Unknown</td>";
                                        }
                                    } else {
                                        echo "<td>Unknown</td>";
                                    }
                                } else {
                                    echo '<td>' . htmlspecialchars($row['c_name']) . '</td>';
                                }
                            ?>
                        </tr>
                        <tr>
                            <th style="width: 30%;">Location:</th>
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
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <?php
                                if ($row['status'] == 1) {
                                    echo '<span class="badge badge-success">PAID</span>';
                                } elseif ($row['status'] == 2) {
                                    echo '<span class="badge badge-primary">PARTIAL</span>';
                                } elseif ($row['status'] == 3) {
                                    echo '<span class="badge badge-danger">CANCELLED</span>';
                                }else {
                                    echo '<span class="badge badge-warning">PENDING</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Remarks:</th>
                            <td>
                                <?php echo htmlspecialchars($row['atap_remarks']); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Transaction Date:</th>
                            <td><?php
                                $dateTime = new DateTime($row['c_tran_date']);
                                echo htmlspecialchars($dateTime->format('Y-m-d'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Encoded by:</th>
                            <td>
                                <?php
                                $c_encoded_by = $row['c_encoded_by'];
                                $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                                $results = odbc_exec($conn, $get_encoder_details_qry);

                                if ($encoder = odbc_fetch_array($results)) {
                                    $realname = $encoder["c_realname"];
                                    echo htmlspecialchars($realname);
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Transaction Name</th>
                            <th class="text-center">Type</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $atapNo = $_GET['no'];
                    $get_atap_items = "SELECT * FROM t_atap_items WHERE c_atap_no = ?";
                    $stmt_items = odbc_prepare($conn, $get_atap_items);
                    odbc_execute($stmt_items, array($atapNo));

                    $totalAmount = 0;
                    $enableSaveButton = true; 

                    while ($row_items = odbc_fetch_array($stmt_items)) {
                        $amount = $row_items['c_atap_amount'];
                        $itemId = $row_items['id'];
                        $totalAmount += $amount; 

                        $c_payment = $row_items['c_tran_type'];
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
                        }

                        if ($row_items['atap_status'] == 0 && $pstatus == 'O') {
                            $enableSaveButton = false; 
                        }
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row_items['c_tran_type']); ?></td>
                            <td class="text-center"><?php echo $statusText; ?></td>
                            <td class="text-right"><?php echo number_format($amount, 2); ?></td>
                            <td class="text-center">
                                <?php
                                if ($row_items['atap_status'] == 0) {
                                    echo '<span class="badge badge-warning">Pending</span>';
                                } elseif ($row_items['atap_status'] == 1) {
                                    echo '<span class="badge badge-success">Paid</span>';
                                } else {
                                    echo '';
                                }
                                ?>
                            </td>
                            <td align="center">
                                <?php if ($pstatus != 'C') { ?>
                                    <?php
                                            $get_main_atap_stats = "SELECT status FROM t_atap WHERE c_atap_no = '$atapNo'";
                                            $results = odbc_exec($conn, $get_main_atap_stats);
                                            if ($main_atap = odbc_fetch_array($results)) {
                                                $stats = $main_atap["status"];
                                            }
                                        ?>
                                    <input type="checkbox" class="atap-status" data-id="<?php echo $itemId; ?>" data-no="<?php echo $atapNo; ?>" <?php echo ($stats == 3) ? 'disabled' : ''; ?>
                                        <?php 
                                            $isChecked = ($row_items['atap_status'] == 1) ? 'checked' : ''; 
                                            $isDisabled = ($row_items['atap_status'] == 1) ? 'disabled' : ''; 
                                            echo $isChecked . ' ' . $isDisabled;
                                        ?>>
                                    <input type="hidden" class="hidden-item-id" id="atapId" value="<?php echo $itemId; ?>" readonly>
                                    <input type="hidden" class="hidden-atap-status" id="status" value="<?php echo ($row_items['atap_status'] == 1) ? '1' : '0'; ?>" readonly>
                                    <button type="button" class="btn btn-primary btn-save-status d-none">Save</button>
                                <?php } else { ?>
                                    <span>---</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total Amount:</th>
                            <th class="text-right"><?php echo number_format($totalAmount, 2); ?></th>
                        </tr>
                        <?php
                            $get_main_atap_stats = "SELECT status FROM t_atap WHERE c_atap_no = '$atapNo'";
                            $results = odbc_exec($conn, $get_main_atap_stats);
                            if ($main_atap = odbc_fetch_array($results)) {
                                $stats = $main_atap["status"];
                            }
                        ?>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary btn-save-status-all" style="width:100%;" <?php echo (($stats == 1 || $stats == 3) || ($stats == 2 || $stats == 0) && $pstatus == 'C') ? 'disabled' : ''; ?>>Save</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <?php include ('../modals/main_modals.php'); ?>
        </div>
    <?php
        } else {
            echo "No data found for the given ID.";
        }
    } else {
        echo "Invalid request.";
    }
    ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.atap-status');
    const saveButton = document.querySelector('.btn-save-status-all');

    function updateSaveButtonState() {
        let anyEnabledCheckbox = false;
        checkboxes.forEach(checkbox => {
            if (!checkbox.hasAttribute('disabled')) {
                anyEnabledCheckbox = true;
            }
        });

        if (anyEnabledCheckbox) {
            saveButton.disabled = false;
        } else {
            saveButton.disabled = true;
        }
    }
    updateSaveButtonState();
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSaveButtonState);
    });
});
</script>
<script>
$(document).on('change', '.atap-status', function() {
    var $checkbox = $(this);
    var $row = $checkbox.closest('tr');
    var itemId = $checkbox.data('id');

    $row.find('.hidden-item-id').val(itemId);
    $row.find('.hidden-atap-status').val($checkbox.is(':checked') ? '1' : '0');
});

$(document).on('click', '.btn-save-status', function() {
    var $row = $(this).closest('tr');
    var itemId = $row.find('.hidden-item-id').val();
    var status = $row.find('.hidden-atap-status').val();

    console.log("Save button clicked. Item ID:", itemId, "Status:", status);

    if (status === '1') {
        paid_atap(itemId);
    } else {
        unpaid_atap(itemId);
    }
});

$(document).on('click', '.btn-save-status-all', function() {
    var items = [];
    var atapNo = '<?php echo htmlspecialchars($atapNo); ?>'; 

    $('.hidden-item-id').each(function() {
        var $textbox = $(this);
        var atapId = $textbox.val(); 
        var $row = $textbox.closest('tr');
        var status = $row.find('.hidden-atap-status').val(); 

        if (atapId) {
            items.push({
                atapId: atapId, 
                status: status,
                atapNo: atapNo  
            });
        }
    });

    console.log("Sending items:", items); 

    if (items.length > 0) {
        $.ajax({
            url: "../../classes/Master.php?f=update_items_status",
            method: "POST",
            data: { items: items },
            dataType: "json",
            error: function(err) {
                console.log("AJAX error response:", err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                console.log("AJAX success response:", resp);
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    setTimeout(function() {
                        location.reload(); 
                    }, 1000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                    end_loader();
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                    end_loader();
                }
            }
        });
    } else {
        alert_toast("No changes to save.", 'warning');
    }
});
</script>
