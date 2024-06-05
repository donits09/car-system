<?php
session_start();
require_once('../../config.php');

if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != 2) {
    require_once('../logout.php');
    exit();
}

if (isset($_SESSION['username'])) {
    echo "Username: " . $_SESSION['username'];
}

include('../../inc/header.php');
include('manage_user.php');

?>

<link rel="stylesheet" href="../../dist/css/index.css">
<p><a href="../../auth/logout.php">Logout</a></p>
    <div class="container mt-5">
        <div class="card mt-3">
            <div class="pd-20">
                <a id="create_new" class="btn btn-flat btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <span class="fa fa-edit text-info"></span> Create New User
                </a>
                <hr>
            </div>
            <div class="container">
                <table class="table table-bordered table-striped" id="data-table">
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
                        $car_list = "SELECT * FROM t_car_users ORDER BY c_date DESC";
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
                                    echo 'Cashier';
                                }
                                elseif ($row['c_group'] == '3'){
                                    echo 'View Only';   
                                }else{
                                    echo $row['c_group'];
                                }
                                ?>
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
                                    <span class="fa fa-edit text-primary"></span> Edit</a>
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
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#addUserForm').submit(function(e) {
            e.preventDefault();
        });
        $('#edit_data').submit(function(e) {
            e.preventDefault();
        });
    });
</script>

