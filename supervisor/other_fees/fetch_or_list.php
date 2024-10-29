<?php
$dsn = "PostgreSQL30";
$user = "postgres";
$pass = "admin12345";

$conn = odbc_connect($dsn, $user, $pass);
if (!$conn) {
    die('Failed to connect to database: ' . odbc_errormsg());
}

$username = isset($_GET['username']) ? $_GET['username'] : 'Unknown';
$account_no = isset($_GET['buyer_acc_no']) ? $_GET['buyer_acc_no'] : '';

if (!empty($account_no)) {
    $get_or = "SELECT * FROM t_or_payment WHERE c_account_no = ? and status != 1 ORDER BY c_or_paydate DESC";

    $stmt = odbc_prepare($conn, $get_or);
    if (!$stmt) {
        die('Failed to prepare SQL statement: ' . odbc_errormsg());
    }

    if (odbc_execute($stmt, array($account_no))) {
        $i = 1;
        $totalAmount = 0;

        while ($row = odbc_fetch_array($stmt)) {
            //$totalAmount += $row['total_amount'];
            ?>
            <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['c_account_no']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['c_or_no']); ?></td>
                <td class="text-center">
                    <?php
                    $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';

                    if (!empty($c_buyer_acc)) {
                        $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
                        $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);

                        if ($buyer_stmt && odbc_execute($buyer_stmt, array($c_buyer_acc))) {
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
                <td class="text-center"><?php echo number_format($row['c_or_amount'], 2); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['c_or_paydate']); ?></td>
                <!-- <td class="text-center"><?php 
                    if ($row['status'] == 0){
                        echo  '<span class="badge badge-warning">PENDING</span>'; 
                    } else if($row['status'] == 1){
                        echo  '<span class="badge badge-primary">PAID</span>'; 
                    }else if($row['status'] == 2){
                        echo  '<span class="badge badge-success">PARTIAL</span>'; 
                    } else {
                        echo  '<span class="badge badge-danger">CANCELLED</span>'; 
                    } ?>
                </td> -->
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
                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                        Action
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <di class="dropdown-menu" role="menu">
                        <a class="dropdown-item view_or_spec" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-no="<?php echo $row['c_or_no'] ?>">
                            <!-- <span class="fa fa-eye text-primary"></span> -->View 
                        </a>
                        <div class="dropdown-divider <?php echo ($row['status'] != 0) ? 'd-none' : ''; ?>"></div>
                        <a class="dropdown-item edit_of_spec<?php echo ($row['status'] != 0) ? 'd-none' : ''; ?>" href="javascript:void(0)" 
                            data-acc-no="<?php echo $row['c_account_no']; ?>"
                            data-id="<?php echo $row['id']; ?>"
                            data-no="<?php echo $row['c_or_no']; ?>">
                            <!-- <span class="fa fa-edit text-primary"></span>  -->Edit
                        </a>
                        <div class="dropdown-divider <?php echo ($row['status'] != 0) ? 'd-none' : ''; ?>"></div>
                        <a class="dropdown-item" href="../../print/print_or.php?id=<?php echo htmlspecialchars($row['c_or_no']); ?>" target="_blank">
                            Print
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item delete_or_spec" href="javascript:void(0)" 
                            data-atap-no="<?php echo $row['c_atap_no']; ?>" 
                            data-id="<?php echo $row['id']; ?>" 
                            data-or-no="<?php echo htmlspecialchars($row['c_or_no']); ?>">
                        Cancel
                        </a>
                    </div>
                </td>
            </tr>
            <?php 
        }
        echo "<script>$('#totalORAmount').text('" . number_format($totalAmount, 2) . "');</script>";
    } else {
        die('Failed to execute SQL statement: ' . odbc_errormsg());
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No data found.</td></tr>";
    echo "<script>$('#totalORAmount').text('0.00');</script>";
}
?>
<script>
    function loadModal(title, url, modalId) {
        start_loader();
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $(modalId + ' .modal-body').html(response);
                $(modalId + ' .modal-title').text(title);
                $(modalId).modal('show');
                end_loader();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred while loading data.");
                end_loader();
            }
        });
    }
    
     $(document).on('click', '.edit_of_spec', function() {
        var orId = $(this).data('id');
        var orNo = $(this).data('no');
        var accountNo = $(this).data('acc-no');
        var modalTitle = 'Edit OR Details ';
        var modalSelector = '#createCarModal';
        var url;
    
        url = '../other_fees/manage_of_spec.php?id=' + orId + '&no=' + orNo + '&acc-no=' + accountNo;
        
        loadModal(modalTitle, url, modalSelector);
    });
    
    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };
    
    function delete_or_spec(orId, orNo, atapNo) {
    start_loader();
    $.ajax({
        url: "../../classes/Master.php?f=delete_or",
        method: "POST",
        data: { orId: orId, orNo: orNo, atapNo: atapNo }, 
        dataType: "json",
        error: function(err) {
            console.log(err);
            alert_toast("An error occurred while processing your request.", 'error');
            end_loader(); 
        },
        success: function(resp) {
            if (resp) {
                if (resp.status === 'success') {
                    alert_toast(resp.msg, 'success'); 
                    setTimeout(function() {
                        $('#confirm_modal').modal('hide'); 
                        $('body').removeClass('modal-open'); 
                        $('.modal-backdrop').remove(); 
                        $('.delete_or[data-id="' + orId + '"]').closest('tr').remove(); 
                        updateORList();
                    }, 1000);
                } else if (resp.status === 'failed' && resp.err) {
                    alert_toast("Error: " + resp.err, 'error'); 
                } else {
                    alert_toast("An unexpected response occurred: " + JSON.stringify(resp), 'error'); 
                }
            } else {
                alert_toast("No response from the server.", 'error'); 
            }
            end_loader(); 
        }
    });
}

</script>
<script>
$(document).on('click', '.delete_or_spec', function() {
    var orId = $(this).data('id');
    var orNo = $(this).data('or-no');
    var atapNo = $(this).data('atap-no');
    
    window._conf("Are you sure you want to cancel this OR?", delete_or_spec, [orId, orNo, atapNo]);
});

</script>