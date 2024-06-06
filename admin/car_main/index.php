<?php 
session_start();
include('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     
?>

<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">

<div class="cont_wrapper">
    <div class="card">
        <div class="pd-20" id="car-btn">
            <h2 class="text-blue h4">Car List</h2>
            <a id="create_new" class="btn btn-flat btn-primary" href="javascript:void(0)" data-account-no="">
                <span class="fa fa-edit"></span> Create New Payment 
            </a>
            <div class="pd-20">
            <hr>
        </div>
        <table class="table table-bordered table-striped" id="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Account No.</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>CAR No.</th>
                    <th>Pay Date</th>
                    <th>Encoder</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="car-type-body">
                <?php
                $car_list = "SELECT * FROM t_car_payment";
                $stmt = odbc_prepare($conn, $car_list);
                
                if ($stmt && odbc_execute($stmt)) {
                    $i = 1;
                    while ($row = odbc_fetch_array($stmt)): 
                ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['c_account_no']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['c_car_type']); ?></td>
                            <td class="text-center"><?php echo number_format($row['c_car_amount'], 2); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['c_car_no']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['c_car_paydate']); ?></td>
                            <td class="text-center">
                                <?php
                                $c_encoded_by = $_SESSION['username'];
                                $get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
                                $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
                                
                                if (odbc_execute($encoder_stmt, array($c_encoded_by)) && $encoder = odbc_fetch_array($encoder_stmt)) {
                                    echo htmlspecialchars($encoder["c_realname"]);
                                } else {
                                    echo "Unknown";
                                }
                                ?>
                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                        <span class="fa fa-eye text-primary"></span> View
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item edit_data" href="javascript:void(0)" 
                                    data-id="<?php echo $row['id']; ?>" 
                                    data-account-no="<?php echo htmlspecialchars($row['c_account_no']); ?>" 
                                    data-payment-type="<?php echo htmlspecialchars($row['c_car_type']); ?>" 
                                    data-amount="<?php echo htmlspecialchars($row['c_car_amount']); ?>" 
                                    data-car-no="<?php echo htmlspecialchars($row['c_car_no']); ?>" 
                                    data-pay-date="<?php echo htmlspecialchars($row['c_car_paydate']); ?>" 
                                    data-encoder="<?php echo htmlspecialchars($row['c_encoded_by']); ?>">
                                        <span class="fa fa-edit text-info"></span> Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo base_url ?>print/print_car.php?id=<?php echo htmlspecialchars($row['c_car_no']); ?>" target="_blank">
                                        <span class="fas fa-print"></span> Print
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-car-no="<?php echo htmlspecialchars($row['c_car_no']); ?>">
                                        <span class="fa fa-trash text-danger"></span> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                <?php 
                    endwhile;
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No data available or error executing query.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button onclick="closeModal()" class="btn customized-modal" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../../dist/js/table.js"></script>

<script>
$(document).ready(function() {
    function updateAccountNo() {
        var accountNo = $('#buyer_acc_no').val();
        $('#create_new').attr('data-account-no', accountNo);
    }
    updateAccountNo();
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        updateAccountNo();
    });
});

$(document).ready(function() {
    $(document).on('click', '.view_data', function() {
        var accountId = $(this).data('id');
        loadModal('Car Payment Details', 'view_car.php?id=' + accountId, '#viewModal');
    });
});


    
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


</script>
<script src="<?php echo base_url; ?>dist/js/table.js"></script>
<script src="<?php echo base_url; ?>dist/js/car_main_list.js"></script>
