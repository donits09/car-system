<?php 
session_start();
// require_once('../../../inc/check_session.php');
// check_user_group(1);

include('../../config.php');
include('../../inc/header.php');  


$carType = isset($_GET['car_type']) ? $_GET['car_type'] : null;
$accountNo = isset($_GET['account-no']) ? $_GET['account-no'] : null;

$total_amount = 0;
$get_total_amount = "SELECT SUM(c_car_amount) AS total_amount FROM t_car_payment WHERE c_car_type = ? AND c_account_no = ?";
$stmt_total = odbc_prepare($conn, $get_total_amount);

if (odbc_execute($stmt_total, array($carType, $accountNo))) {
    $row_total = odbc_fetch_array($stmt_total);
    if ($row_total) {
        $total_amount = $row_total['total_amount'];
    }
}

$c_car_total_amount = number_format($total_amount, 2, '.', ',');
?>

<!-- <link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css"> -->
<style>
    .export_csv {
        margin-top: 24px;
    }
</style>

<body>
<div class="container mt-2">

    <div class="container">
        <div class="row">
        <div class="col-12 col-md-3">
            <label for="accountNo" class="form-label">Account No.</label>
            <input type="text" class="form-control" id="accountNo" value="<?php echo htmlspecialchars($accountNo); ?>" readonly>
            
        </div>
        <div class="col-12 col-md-3">
            <label for="carType" class="form-label">Payment Type</label>
            <input type="text" class="form-control" id="carType" value="<?php echo htmlspecialchars($carType); ?>" readonly>
        </div>
        <div class="col-12 col-md-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="text" class="form-control" id="total_amount" value="<?php echo htmlspecialchars($c_car_total_amount); ?>" readonly>
        </div>
        <div class="col-12 col-md-3">
            <a id="export_summary" class="btn btn-flat btn-danger export_csv" href="javascript:void(0)">
                <span class="fa fa-download"></span> Export as PDF
            </a>
        </div>
    </div>

    <div class="table-container mt-4">
        <table class="table table-bordered table-striped" id="data-table">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Account No.</th>
                    <th>CAR No.</th>
                    <th>Payment Type</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Amount</th>
                    <th>MoP</th>
                    <th>Bank</th>
                    <th>Transaction Date</th>
                    <th>Pay Date</th>
                    <th>Encoder</th>
                </tr>
            </thead>
            <tbody id="car-type-body">
            <?php
                $get_bank_types = "SELECT * FROM t_car_payment WHERE c_car_type = ? and c_account_no = ? ORDER BY c_tran_updated DESC";
                $stmt = odbc_prepare($conn, $get_bank_types);
                
                if (odbc_execute($stmt, array($carType, $accountNo))) {
                    $i = 1; 
                    while ($row = odbc_fetch_array($stmt)) {
                        $c_encoded_by = $row['c_encoded_by'];
                        $get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
                        $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
                        $encoder_name = '-';

                        if (odbc_execute($encoder_stmt, array($c_encoded_by))) {
                            if ($encoder = odbc_fetch_array($encoder_stmt)) {
                                $encoder_name = htmlspecialchars($encoder["c_realname"]);
                            }
                        }

                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['c_account_no']); ?></td>
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
                                                echo "-";
                                            }
                                        } else {
                                            echo "-";
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
                            <td class="text-center"><?php echo number_format($row['c_car_amount'],2); ?></td>
                            <td class="text-center">
                                <?php 
                                if ($row['c_mop'] == 1) {
                                    echo "Cash";
                                } elseif ($row['c_mop'] == 2) {
                                    echo "Check";
                                } elseif ($row['c_mop'] == 3) {
                                    echo "Online";
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                if ($row['c_bank'] == '') {
                                    echo "-";
                                }else {
                                    echo htmlspecialchars($row['c_bank']);
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
                            <td class="text-center"><?php echo $encoder_name; ?></td>
                        </tr>
                        <?php 
                    }
                } else {
                    echo "<tr><td colspan='12' class='text-center'>Error executing query.</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
<!-- <script src="../../dist/js/table.js"></script> -->
<script>
$(document).ready(function() {
    $('#data-table').DataTable({
        "pageLength": 5
    });
});
</script>
<script>
function exportPDFsummary() {
    let account_no = document.getElementById('accountNo').value;
    let car_type = document.getElementById('carType').value;

    if (account_no && car_type) {
        let url = `../../print/pdf_payment_type.php?account_no=${encodeURIComponent(account_no)}&car_type=${encodeURIComponent(car_type)}`;
        window.open(url, '_blank');
    } else {
        console.error('Account number or car type is empty or not found.');
    }
}

document.getElementById('export_summary').addEventListener('click', exportPDFsummary);

</script>
<script src="../../dist/js/export_scripts.js"></script>