<?php 
session_start();
include('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     
?>
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/car_reports.css">
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
<div class="container mt-5">
    <div class="card mt-3">
        <div class="main_header">
            <div id="header">ASIAN LAND STRATEGIES CORPORATION</div>
            <div id="subheader">DAILY COLLECTION & DEPOSIT REPORT</div>
            <div id="current_date"><?php echo date("Y-m-d"); ?></div>
        </div>
        <hr>
        <div class="sub_container">
            <div class="date_container">
                <b>Search by Transaction Date</b><hr>
                <div class="pd-20">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" class="form-control" />
                    <label for="end_date" class="mt-2">End Date:</label>
                    <input type="date" id="end_date" class="form-control" />
                    <button id="filter" class="btn btn-primary mt-2">Filter</button>
                    <button id="reset" class="btn btn-secondary mt-2">Reset</button>
                </div>
            </div>
            <div class="btn_container">
                <!-- <button class="btn btn-primary mt-2">Print</button>
                <button class="btn btn-secondary mt-2">Copy</button> -->
                <button class="btn btn-danger mt-2">Export as PDF</button>
                <button id="export_csv" class="btn btn-flat btn-success mt-2" href="javascript:void(0)">Export as CSV</button>
            </div>
        </div>
        <hr>
        <div class="table-container">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                <tr>
                        <th>No</th>
                        <th>Account No.</th>
                        <th>CAR No.</th>
                        <th>Payment Type</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Amount</th>
                        <th>MoP</th>
                        <th>Transaction Date</th>
                        <th>Pay Date</th>
                        <th>Encoder</th>
                    </tr>
                </thead>
                <tbody id="car-type-body">
                    <?php
                    $car_list = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                    a.c_car_paydate,a.c_car_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop, b.c_name, b.c_phase,
                    b.c_block, b.c_lot
                        FROM t_car_payment a
                        LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no;
                        ";
                    $stmt = odbc_prepare($conn, $car_list);
                    
                    if ($stmt && odbc_execute($stmt)) {
                        $i = 1;
                        while ($row = odbc_fetch_array($stmt)): 
                    ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center">
                                    <?php 
                                        echo htmlspecialchars(!empty($row['c_account_no']) ? $row['c_account_no'] : '----------'); 
                                    ?>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_car_no']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_car_type']); ?></td>
                                <td class="text-center">
                                <?php
                                    $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';

                                    if (!empty($c_buyer_acc)) {
                                        $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
                                        $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
                                        
                                        if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                                            $buyer_details = odbc_fetch_array($buyer_stmt);
                                            
                                            if ($buyer_details) {
                                                echo htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]);
                                            } else {
                                                echo "Unknown";
                                            }
                                        } else {
                                            echo "Unknown";
                                        }
                                    } else {
                                        echo htmlspecialchars($row['c_name']);
                                    }
                                    ?>
                                </td>
                                <td>
                                <?php
                                $c_account_no = $row['c_account_no'];
                                try {
                                    if (!empty($c_account_no)) {
                                        $c_phase = substr($c_account_no, 0, 3);
                                        $c_block = ltrim(substr($c_account_no, 3, 3), '0'); 
                                        $c_lot = substr($c_account_no, 6, 2);

                                        $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                                        $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                                        if (odbc_execute($phase_stmt, array($c_phase))) {
                                            $phase_details = odbc_fetch_array($phase_stmt);

                                            if ($phase_details) {
                                                echo htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                                            } else {
                                                echo "-----";
                                            }
                                        } else {
                                            echo "-----";
                                        }
                                    } else {
                                        $c_phase = $row['c_phase'];
                                        $c_block = $row['c_block'];
                                        $c_lot = $row['c_lot'];

                                        if (empty($c_phase) && empty($c_block) && empty($c_lot)) {
                                            echo "-------------";
                                        } else {
                                            $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                                            $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                                            if (odbc_execute($phase_stmt, array($c_phase))) {
                                                $phase_details = odbc_fetch_array($phase_stmt);

                                                if ($phase_details) {
                                                    echo htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                                                } else {
                                                    echo "-----";
                                                }
                                            } else {
                                                echo "-----";
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo "-----";
                                }
                                ?>
                                </td>
                                <td class="text-center"><?php echo number_format($row['c_car_amount'], 2); ?></td>
                                <td class="text-center">
                                    <?php 
                                    if ($row['c_mop'] == 1) {
                                        echo "Cash";
                                    } elseif ($row['c_mop'] == 2) {
                                        echo "Check";
                                    } else {
                                        echo "Unknown";
                                    }
                                    ?>
                                </td>
                                <td class="text-center tran-date">
                                    <?php
                                    $dateTime = new DateTime($row['c_tran_date']);
                                    echo htmlspecialchars($dateTime->format('Y-m-d')); 
                                    ?>
                                </td>
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
                            </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No data available or error executing query.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="../../dist/js/table.js"></script>
<script src="../../dist/js/reports/car_reports.js"></script>
