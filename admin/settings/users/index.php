<?php
session_start();

require_once('../../../config.php');
include('../../../inc/header.php');
include('../../../inc/navbar.php');    
include('manage_user.php');

if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != 1) {
    require_once('../logout.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
    <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
</head>
<body>

    <div class="container mt-5">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="user-tab" data-toggle="tab" href="#user" role="tab" aria-controls="user" aria-selected="true">User Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false">User Logs</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
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
                                $user_list = "SELECT * FROM t_car_users ORDER BY id DESC";
                                $user_result = odbc_exec($conn, $user_list);
                                while ($row = odbc_fetch_array($user_result)): 
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-center"><?php echo $row['c_employee_code']; ?></td>
                                    <td class="text-center"><?php echo $row['c_realname']; ?></td>
                                    <td class="text-center"> 
                                        <?php 
                                        switch ($row['c_group']) {
                                            case '1':
                                                echo 'Admin';
                                                break;
                                            case '2':
                                                echo 'Supervisor';
                                                break;
                                            case '3':
                                                echo 'Cashier';
                                                break;
                                            case '4':
                                                echo 'Viewer';
                                                break;
                                            default:
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
            </div>

            <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                <div class="card mt-3">
                    <div class="pd-20">
                        <form method="get" action="">
                            <input type="hidden" name="active_tab" value="logs">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="start_date">Start Date:</label>
                                    <input type="text" id="start_date" name="start_date" class="form-control datepicker" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date">End Date:</label>
                                    <input type="text" id="end_date" name="end_date" class="form-control datepicker" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                                </div>
                                <div class="col-md-1 align-self-end">
                                    <button type="submit" class="btn btn-primary"><span class="fa fa-filter"></span> Filter</button>
                                </div>
                            </div>
                        </form>
                        <br>
                    </div>
                    <div class="table-container">
                        <table class="table table-bordered table-striped" id="logs-data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Log</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Module</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                                $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                                
                                $log_list = "SELECT * FROM t_car_logs";
                                
                                if ($start_date && $end_date) {
                                    $log_list .= " WHERE c_date BETWEEN '$start_date' AND '$end_date'";
                                } else {
                                    $current_date = date('Y-m-d');
                                    $log_list .= " WHERE c_date = '$current_date'";
                                }

                                $log_list .= " ORDER BY c_date, c_time DESC";
                                $log_result = odbc_exec($conn, $log_list);
                                while ($row = odbc_fetch_array($log_result)): 
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td class="text-center"><?php echo $row['c_name']; ?></td>
                                    <td>
                                    <?php
                                        $c_encoded_by = $_SESSION['username'];
                                        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                                        $results = odbc_exec($conn, $get_encoder_details_qry);

                                        if ($encoder = odbc_fetch_array($results)) {
                                            $realname = $encoder["c_realname"];
                                        }
                                    ?>
                                    <?php echo $realname ?>
                                    </td>
                                    <td class="text-center"><?php echo $row['c_log']; ?></td>
                                    <td class="text-center"><?php echo $row['c_date']; ?></td>
                                    <td class="text-center"><?php echo $row['c_time']; ?></td>
                                    <td class="text-center"><?php echo $row['c_module']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
        });
    });
    </script>

    <script src="../../../dist/js/table.js"></script>
    <script src="../../../dist/js/logs.js"></script>
    <?php include('../../../inc/footer.php'); ?>
</body>
</html>
