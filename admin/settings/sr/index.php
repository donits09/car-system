<?php
session_start();

require_once('../../../inc/check_session.php');
check_user_group(1);

include('../../../config.php');
include('../../../inc/navbar.php');  
include('../../../inc/header.php');  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url; ?>dist/css/jquery-ui.css" rel="stylesheet">
    <script src="<?php echo base_url; ?>dist/js/jquery-ui.min.js"></script>
</head>

<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">

<style>
#btn-filter{
    width: 100px;
    margin-left: -40px;
}
#end_date{
    margin-left: -20px;
}
</style>

<body>
    <div class="container mt-5">
        <div class="card mt-3">
        <h2 class="text-blue h4">Service Requests</h2>
        <hr>
        <div class="pd-20">
            <a id="create_new" class="btn btn-flat btn-primary" href="javascript:void(0)" data-id="">
                <span class="fa fa-edit"></span> Create New Service Request
            </a>
            <hr>
        </div>
            <div class="pd-20">
                <form method="get" action="">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="start_date">Start Date:</label>
                            <input type="text" id="start_date" name="start_date" class="form-control datepicker" 
                                value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">End Date:</label>
                            <input type="text" id="end_date" name="end_date" class="form-control datepicker" 
                                value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-1 align-self-end">
                            <button type="submit" class="btn btn-primary" id="btn-filter">
                                <span class="fa fa-filter"></span> Filter
                            </button>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="table-container">
                <table class="table table-bordered table-striped" id="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Requestor</th>
                            <th>Nature of Request</th>
                            <th>Assigned to</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                            $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                            
                            $car_list = "SELECT * FROM t_service_requests";
                            
                            if ($start_date && $end_date) {
                                $car_list .= " WHERE date_created BETWEEN '$start_date' AND '$end_date'";
                            } else {
                                $current_date = date('Y-m-d');
                                $car_list .= " WHERE date_created = '$current_date'";
                            }

                            $car_list .= " ORDER BY date_created, c_time DESC";
                            $car_result = odbc_exec($conn, $car_list);
                            while ($row = odbc_fetch_array($car_result)): 
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><?php echo $row['date_created']; ?></td>
                            <td>
                            <?php
                                $c_encoded_by = $row['requestor'];
                                $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                                $results = odbc_exec($conn, $get_encoder_details_qry);

                                if ($encoder = odbc_fetch_array($results)) {
                                    $realname = $encoder["c_realname"];
                                }
                            ?>
                            <?php echo $realname ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    if ($row['nature_of_request'] == 1) {
                                        echo 'Payment Transfer';
                                    } else{
                                        echo '-';
                                    }
                                ?>  
                            </td>
                            <td class="text-center">
                                <?php echo $row['assigned_to']; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    if ($row['c_status'] == 0) {
                                        echo 'Open';
                                    } elseif ($row['c_status'] == 1) {
                                        echo 'Processing';
                                    } elseif ($row['c_status'] == 2) {
                                        echo 'Resolved';
                                    } elseif ($row['c_status'] == 3) {
                                        echo 'Closed';
                                    }else{
                                        echo '-';
                                    }
                                ?>  
                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action 
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item view_sr" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                        <!-- <span class="fa fa-eye text-primary"></span> -->View
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item edit_sr" href="javascript:void(0)" 
                                    data-id="<?php echo $row['id']; ?>">
                                        Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_sr" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php include ('../../modals/main_modals.php'); ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });

            $('#myTab a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
    <script src="../../../dist/js/table.js"></script>
    <script src="../../../dist/js/manage_sr.js"></script>
    <?php include('../../../inc/footer.php'); ?>
</body>
</html>
