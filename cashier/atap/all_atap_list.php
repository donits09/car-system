<?php 
session_start();

require_once('../../inc/check_session.php');
check_user_group(3);

include('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     
?>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css">
<style>
    .green-row {
        background-color: #cbd2d9 !important;
        font-weight: bold !important;
        font-style: italic;
    }
    .hidden_fields{
        display:none;
    }
    /* .status-pending {
        background-color: orange !important;
    }
    .status-paid {
        background-color: green !important;
    }
    .status-cancelled {
        background-color: red !important;
    } */
</style>
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <div class="pd-20">
        <!-- <div class="pd-20" id="car-btn"> -->
        <h2 class="text-blue h4">Authority to Accept Payment - Full List</h2><hr><br>
        <!-- <hr>
            <a id="create_new_atap" class="btn btn-flat btn-primary" href="javascript:void(0)">
                <span class="fa fa-edit"></span> Create New ATAP
            </a>
            <a id="create_other_atap" class="btn btn-flat btn-success" href="javascript:void(0)">
                <span class="fa fa-edit"></span> Create Other ATAP
            </a>
            <div class="pd-20">
            <hr> -->
        </div>
        <div class="table-container">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Account No.</th>
                        <th>ATAP No.</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Total Amount</th>
                        <th>Transaction Date</th>
                        <th>Status</th>
                        <th>Requester</th>
                        <th>Action</th>
                    </tr>
                </thead>
                    <?php
                    $get_atap = "SELECT 
                            a.id, 
                            a.c_account_no, 
                            a.c_atap_no, 
                            a.c_encoded_by,
                            a.c_tran_date, 
                            a.c_tran_updated, 
                            a.atap_remarks, 
                            a.status, 
                            b.c_name, 
                            b.c_phase,
                            b.c_block, 
                            b.c_lot, 
                            SUM(c.c_atap_amount) AS total_amount
                        FROM 
                            t_atap a
                        LEFT JOIN 
                            t_other_atap b ON a.c_atap_no = b.c_atap_no 
                        LEFT JOIN 
                            t_atap_items c ON a.c_atap_no = c.c_atap_no
                        GROUP BY 
                            a.id, 
                            a.c_account_no, 
                            a.c_atap_no, 
                            a.c_encoded_by,
                            a.c_tran_date, 
                            a.c_tran_updated, 
                            a.atap_remarks, 
                            a.status, 
                            b.c_name, 
                            b.c_phase,
                            b.c_block, 
                            b.c_lot
                        ORDER BY 
                            a.c_tran_updated DESC";
                    $stmt = odbc_prepare($conn, $get_atap);
                    if (odbc_execute($stmt)) {
                        $i = 1; 
                        while ($row = odbc_fetch_array($stmt)) {
                            $rowClass = '';
                            if ($row['status'] == 0) {
                                $rowClass = 'status-pending';
                            } else if ($row['status'] == 1) {
                                $rowClass = 'status-paid';
                            } else {
                                $rowClass = 'status-cancelled';
                            }
                            ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center">
                                    <?php echo htmlspecialchars(!empty($row['c_account_no']) ? $row['c_account_no'] : '----------'); ?>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_atap_no']); ?></td>
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
                                <td class="text-center"><?php echo number_format($row['total_amount'],2); ?></td>
                                <td class="text-center tran-date">
                                    <?php
                                    $dateTime = new DateTime($row['c_tran_date']);
                                    echo htmlspecialchars($dateTime->format('Y-m-d'));
                                    ?>
                                </td>
                                <td class="text-center"><?php 
                                    if ($row['status'] == 0){
                                        echo  'PENDING' ; 
                                    }else if($row['status'] == 1){
                                        echo  'PAID' ; 
                                    }else{
                                        echo  'CANCELLED' ; 
                                    } ?>
                               </td>
                               <td class="text-center">
                                    <?php
                                    $c_encoded_by = $row['c_encoded_by'];
                                    $get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
                                    $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);

                                    if (odbc_execute($encoder_stmt, array($c_encoded_by)) && $encoder = odbc_fetch_array($encoder_stmt)) {
                                        echo htmlspecialchars($encoder["c_realname"]);
                                    } else {
                                        echo "Unknown";
                                    }
                                    ?>
                                </td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item view_atap" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-no="<?php echo $row['c_atap_no'] ?>">
                                            <span class="fa fa-eye text-primary"></span> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>Error.</td></tr>";
                    }
                    ?>
                
            </table>
        </div>
        <?php include ('../modals/main_modals.php'); ?>
    </div>
</div>
<script src="../../dist/js/table.js"></script>
<script src="../../dist/js/all_atap_list.js"></script>
</body>
<?php include('../../inc/footer.php'); ?>
