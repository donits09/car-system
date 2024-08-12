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
        <h2 class="text-blue h4">List of Departments for System Users</h2>
        <hr>
        <div class="pd-20">
            <a id="create_new" class="btn btn-flat btn-primary" href="javascript:void(0)" data-account-no="">
                <span class="fa fa-edit"></span> Create New Department
            </a>
            <hr>
        </div>
        <div class="table-container">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="car-type-body">
                <?php
                    $department = "SELECT id, c_department, status FROM t_emp_department ORDER BY status DESC";
                    $stmt = odbc_prepare($conn, $department);
                    if (odbc_execute($stmt)) {
                        $i = 1; 
                        while ($row = odbc_fetch_array($stmt)) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_department']); ?></td>
                                <td class="text-center"><?php echo $row['status'] == 0 ? 'Active' : 'Inactive'; ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item edit_data" href="javascript:void(0)" 
                                        data-id="<?php echo $row['id']; ?>" 
                                        data-department-type="<?php echo ($row['c_department']); ?>" 
                                        data-department-status="<?php echo ($row['status']); ?>">
                                            <!-- <span class="fa fa-edit text-primary"></span>  -->Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-department-type="<?php echo $row['c_department']; ?>">
                                            <!-- <span class="fa fa-trash text-danger"></span>  -->
                                            Remove
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
    </div>
    <?php include ('../../modals/main_modals.php'); ?>
</div>
</body>
<script src="../../../dist/js/table.js"></script>
<script src="../../../dist/js/manage_department.js"></script>
<?php include('../../../inc/footer.php'); ?>
