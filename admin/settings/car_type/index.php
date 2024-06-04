<?php 
session_start();
include('../../../config.php');
include('../../../inc/navbar.php');    
include('../../../inc/header.php');     
?>

<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<div class="cont_wrapper">
    <div class="card">
        <div class="pd-20" id="car-btn">
            <h2 class="text-blue h4">Car List</h2>
            <a id="create_new" class="btn btn-flat btn-primary" href="javascript:void(0)" data-account-no="">
                <span class="fa fa-edit"></span> Create New Payment Type
            </a>
            <div class="pd-20">
            <hr>
        </div>
        <table class="table table-bordered table-striped" id="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Payment Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="car-type-body">
            <?php
                $get_car_types = "SELECT id, c_payment_type, status FROM t_car_type ORDER BY status DESC";
                $stmt = odbc_prepare($conn, $get_car_types);
                if (odbc_execute($stmt)) {
                    $i = 1; 
                    while ($row = odbc_fetch_array($stmt)) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['c_payment_type']); ?></td>
                            <td class="text-center"><?php echo $row['status'] == 0 ? 'Active' : 'Inactive'; ?></td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item edit_data" href="javascript:void(0)" 
                                    data-id="<?php echo $row['id']; ?>" 
                                    data-payment-type="<?php echo ($row['c_payment_type']); ?>" 
                                    data-payment-status="<?php echo ($row['status']); ?>">
                                        <span class="fa fa-edit text-primary"></span> Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">
                                        <span class="fa fa-trash text-danger"></span> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Error executing query.</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php include ('../../modals/main_modals.php'); ?>
</div>
<script src="../../../dist/js/manage_car_type.js"></script>