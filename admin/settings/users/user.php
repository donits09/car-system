<?php
require_once('../../../config.php');
include('../../../inc/header.php');
?>

<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">

<div class="card mt-3">
    <div class="pd-20">
        <a id="create_new" class="btn btn-flat btn-primary" href="#" data-toggle="modal" data-target="#addUserModal">
            <span class="fa fa-edit"></span> Create New User
        </a>
        <hr>
    </div>
    <div class="table-container">
        <table class="table table-bordered table-striped" id="user-data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee Code</th>
                    <th>Name</th>
                    <th>Group</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                $car_list = "SELECT * FROM t_car_users ORDER BY id DESC";
                $car_result = odbc_exec($conn, $car_list);
                while ($row = odbc_fetch_array($car_result)): 
                ?>
                <tr>
                    <td class="text-center"><?php echo $i++; ?></td>
                    <td class="text-center"><?php echo $row['c_employee_code']; ?></td>
                    <td class="text-center"><?php echo $row['c_realname']; ?></td>
                    <td class="text-center"> 
                        <?php 
                        if ($row['c_group'] == '1'){
                            echo 'Admin';
                        }
                        elseif ($row['c_group'] == '2'){
                            echo 'Supervisor';
                        }
                        elseif ($row['c_group'] == '3'){
                            echo 'Cashier';   
                        }
                        elseif ($row['c_group'] == '4'){
                            echo 'Viewer';   
                        }else{
                            echo $row['c_group'];
                        }
                        ?>
                    </td>
                    <td class="text-center"><?php echo $row['c_department']; ?></td>
                    <td align="center">
                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            Action
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item edit_data" href="javascript:void(0)" 
                                data-id="<?php echo $row['id']; ?>"
                                data-employee_code="<?php echo $row['c_employee_code']; ?>" 
                                data-password="<?php echo $row['c_password']; ?>" 
                                data-realname="<?php echo $row['c_realname']; ?>" 
                                data-group="<?php echo $row['c_group']; ?>" 
                                data-department="<?php echo $row['c_department']; ?>">
                                <span class="fa fa-edit text-primary"></span> Edit
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
var $jq = jQuery.noConflict();

$jq(document).ready(function() {
    $jq('.edit_data').click(function() {
        $jq('#editUserModal').modal('show');
    });
    $jq('.delete_data').click(function() {
        $jq('#confirmDeleteModal').modal('show');
    });
});
</script>
<script>
function closeModal2() {
    $('#confirmDeleteModal').modal('hide');
    $('#editUserModal').modal('hide');
    $('#addUserModal').modal('hide');
  }
</script>
<?php include('manage_user.php'); ?>
<script src="../../../dist/js/table.js"></script>
<?php include('../../../inc/footer.php'); ?>