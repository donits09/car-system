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

<body>
    <div class="container mt-5">
        <div class="card mt-3">
        <h2 class="text-blue h4">User Logs</h2>
        <hr>
            <div class="pd-20">
                <form method="get" action="">
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
                <table class="table table-bordered table-striped" id="data-table">
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
                        
                        $car_list = "SELECT * FROM t_car_logs";
                        
                        if ($start_date && $end_date) {
                            $car_list .= " WHERE c_date BETWEEN '$start_date' AND '$end_date'";
                        } else {
                            $current_date = date('Y-m-d');
                            $car_list .= " WHERE c_date = '$current_date'";
                        }

                        $car_list .= " ORDER BY c_date, c_time DESC";
                        $car_result = odbc_exec($conn, $car_list);
                        while ($row = odbc_fetch_array($car_result)): 
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><?php echo $row['c_name']; ?></td>
                            <td>
                            <?php
                                //Eto yung lagi pong naooverwrite kapag nag memerge BAHAHAHAHA
                                $c_encoded_by = $row['c_name'];
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
    <script src="../../../dist/js/logs.js"></script>
    <?php include('../../../inc/footer.php'); ?>
</body>
</html>
