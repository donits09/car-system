<?php 
session_start();
// require_once('../../../inc/check_session.php');
// check_user_group(1);

include('../../../config.php');
include('../../../inc/navbar.php');  
include('../../../inc/header.php');  

?>

<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css">
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <!-- <div class="pd-20" id="car-btn"> -->
        <h2 class="text-blue h4">List of Banks for Check</h2>
        <hr>
        <div class="pd-20">
            <a id="create_new" class="btn btn-flat btn-primary" href="javascript:void(0)" data-account-no="">
                <span class="fa fa-edit"></span> Create New Bank
            </a>
            <hr>
        </div>
        <div class="table-container">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Acronym</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="car-type-body">
                <?php
                    $get_bank_types = "SELECT id, c_bank_type, c_name, status FROM t_car_check ORDER BY c_name ASC";
                    $stmt = odbc_prepare($conn, $get_bank_types);
                    if (odbc_execute($stmt)) {
                        $i = 1; 
                        while ($row = odbc_fetch_array($stmt)) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_bank_type']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_name']); ?></td>
                                <td class="text-center"><?php echo $row['status'] == 0 ? 'Active' : 'Inactive'; ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item edit_data" href="javascript:void(0)" 
                                        data-id="<?php echo $row['id']; ?>" 
                                        data-bank-type="<?php echo ($row['c_bank_type']); ?>" 
                                        data-bank-status="<?php echo ($row['status']); ?>">
                                            <!-- <span class="fa fa-edit text-primary"></span>  -->Edit
                                        </a>
                                        <!-- <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-car-type="<?php echo $row['c_bank_type']; ?>">
                                            <span class="fa fa-trash text-danger"></span> Inactive
                                        </a> -->
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
    </div>
    <?php include ('../../modals/main_modals.php'); ?>
</div>
</body>
<script src="../../../dist/js/table.js"></script>
<script src="../../../dist/js/manage_check_type.js"></script>
<?php include('../../../inc/footer.php'); ?>
