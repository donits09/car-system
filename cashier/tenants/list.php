<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(3);

include('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     
?>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<link href="<?php echo base_url; ?>dist/css/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url; ?>dist/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css">
<style>
    #createTenantModal .modal-dialog{
        width: 60% !important;
        max-width: 60%;
        height:50%;
        margin-top: 0;
    }
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
    .dropdown-menu {
        top: auto;
        transform: translate3d(0, 0, 0); 
    }
</style>
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <div class="pd-20">
        <!-- <div class="pd-20" id="car-btn"> -->
        <h2 class="text-blue h4">Tenants - Full List</h2>
        <hr>
        </div>
        <div class="table-container">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Account No.</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Lot Area</th>
                        <th>Price SQM</th>
                        <th>Encoder</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="car-type-body">
                        <?php
                        $username = $_SESSION['username'];
                        $or_list = "SELECT * FROM t_tenant_accounts WHERE c_status = 0";

                        $stmt = odbc_prepare($conn, $or_list);

                        $result = odbc_execute($stmt, array($username));

                        if ($result === false) {
                            echo "<tr><td colspan='13' class='text-center'>No data available or error executing query.</td></tr>";
                        } else {
                            $i = 1;
                            while ($row = odbc_fetch_array($stmt)) {
                        ?>
                                <tr class="<?php echo $row_class; ?>">
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars(!empty($row['c_account_no']) ? $row['c_account_no'] : '----------'); ?>
                                    </td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_last_name']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_first_name']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_middle_name']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($row['c_lot_area']); ?></td>
                                    <td class="text-center"><?php echo number_format($row['c_price_sqm'], 2); ?></td>
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
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item view_tenant" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                            <!-- <span class="fa fa-eye text-primary"></span> -->View
                                        </a>
                                    </div>
                                </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
            </table>
        </div>
        <?php include ('modals/main_modals.php'); ?>
    </div>
</div>
<script src="../dist/js/table.js"></script>
<script src="../dist/js/of_js/all_tenant_list.js"></script>
<script src="../dist/js/export_scripts.js"></script>
<!-- <script src="../../dist/js/manage_car.js"></script> -->
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
window._conf = function(msg, func, params) {
    $('#confirm_modal .modal-body').html(msg);
    $('#confirm_modal #confirm').off('click').on('click', function() {
        func.apply(this, params);
    });
    $('#confirm_modal').modal('show');
};
</script>
<script>
    $('#create_new_tenant').click(function() {
        loadModal('Create New Tenant', '../tenants/manage_tenant.php', '#createTenantModal');
    });

    $(document).on('click', '.view_tenant', function() {
        var tenantId = $(this).data('id');
        loadModal('Tenant Details', '../tenants/view_tenant.php?id=' + tenantId, '#viewModal');
    });

    $(document).on('click', '.edit_tenant', function() {
        var tenantId = $(this).data('id');
        loadModal('Edit Tenant Details', '../tenants/manage_tenant.php?id=' + tenantId, '#createTenantModal');
    });

    $(document).on('click', '.delete_tenant', function() {
        var tenantId = $(this).data('id');
        _conf("Are you sure you want to delete this tenant's record?", delete_tenant, [tenantId]);
    });
</script>
<script>
    function delete_tenant(tenantId) {
        start_loader();
        
        $.ajax({
            url: "../classes/Master.php?f=delete_tenant",
            method: "POST",
            data: { tenantId: tenantId },
            dataType: "json",
            error: function(err) {
                console.error("AJAX Error: ", err.responseText); 
                alert_toast("An error occurred while making the request.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    
                    setTimeout(function() {
                        $('#confirm_modal').modal('hide'); 
                        $('body').removeClass('modal-open'); 
                        $('.modal-backdrop').remove(); 
                        location.reload(); 
                        $('.delete_tenant[data-id="' + tenantId + '"]').closest('tr').remove();
                    }, 1000);
                } else if (resp && resp.status === 'failed') {
                    let errorMsg = resp.err ? resp.err : resp.msg;
                    alert_toast("An error occurred: " + errorMsg, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }
        });
    }
</script>
</div>
</body>
<?php include('../../inc/footer.php'); ?>
