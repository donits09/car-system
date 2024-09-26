<?php
session_start();
require_once('../../inc/check_session.php');
check_user_group(1);
$c_encoded_by = '';
include('../../config.php');

if (isset($_GET['car_no']) && !empty($_GET['car_no'])) {
    $totalAmount = 0;
    $carNo = $_GET['car_no'];
    $get_car_query = "SELECT a.c_account_no, a.c_car_no, a.c_car_type, a.c_car_amount, 
                             a.c_car_paydate, a.c_encoded_by, a.c_tran_date, a.c_tran_updated, 
                             a.c_mop, a.c_bank, b.c_name, b.c_phase, b.c_block, b.c_lot, 
                             a.c_check_no, a.c_remarks
                      FROM t_car_payment a
                      LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no 
                      WHERE a.c_car_no = ?";

    $stmt = odbc_prepare($conn, $get_car_query);
    odbc_execute($stmt, array($carNo));
    $carData = [];

    while ($firstEntry = odbc_fetch_array($stmt)) {
        $carData[] = $firstEntry;
    }

    if (!empty($carData)) {
        $firstEntry = $carData[0]; 
?>
<div class="container-fluid">
    <div class="callout callout-primary">
        <table class="table table-bordered" style="text-align:left;">
            <tbody>
                <tr>
                    <th style="width: 30%;">Account No.:</th>
                    <td><?php echo !empty($firstEntry['c_account_no']) ? $firstEntry['c_account_no'] : '----------'; ?></td>
                </tr>
                <tr>
                    <th>Car No.:</th>
                    <td><?php echo $firstEntry['c_car_no']; ?></td>
                </tr>
                <tr>
                    <th>Payment Type & Amount Breakdown:</th>
                    <td>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Car Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                foreach ($carData as $car) {
                                    $carTypes = explode(',', $car['c_car_type']);
                                    $carAmounts = explode(',', $car['c_car_amount']);

                                    foreach ($carTypes as $index => $type) {
                                        $amount = isset($carAmounts[$index]) ? (float)$carAmounts[$index] : 0;
                                        $totalAmount += $amount;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(trim($type)); ?></td>
                                        <td><?php echo number_format($amount, 2); ?></td>
                                    </tr>
                                <?php 
                                    } 
                                } 
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total:</th>
                                    <td><?php echo number_format($totalAmount, 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th style="width: 30%;">Name:</th>
                    <?php
                        $c_buyer_acc = !empty($firstEntry['c_account_no']) ? $firstEntry['c_account_no'] : '';

                        if (!empty($c_buyer_acc)) {
                            $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
                            $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
                            
                            if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                                $buyer_details = odbc_fetch_array($buyer_stmt);
                                
                                if ($buyer_details) {
                                    echo '<td>' . htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]) . '</td>';
                                } else {
                                    echo "<td>Unknown</td>";
                                }
                            } else {
                                echo "<td>Unknown</td>";
                            }
                        } else {
                            echo '<td>' . htmlspecialchars($firstEntry['c_name']) . '</td>';
                        }
                    ?>
                </tr>
                <tr>
                    <th style="width: 30%;">Location:</th>
                    <td>
                    <?php
                        $c_account_no = $firstEntry['c_account_no'];

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
                                $c_phase = $firstEntry['c_phase'];
                                $c_block = $firstEntry['c_block'];
                                $c_lot = $firstEntry['c_lot'];

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
                </tr>
                <tr>
                    <th>Mode of Payment:</th>
                    <td>
                        <?php 
                        if ($firstEntry['c_mop'] == 1) {
                            echo "Cash";
                        } elseif ($firstEntry['c_mop'] == 2) {
                            echo "Check";
                        } elseif ($firstEntry['c_mop'] == 3) {
                            echo "Online";
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                </tr>
                <?php if ($firstEntry['c_mop'] == 2 || $firstEntry['c_mop'] == 3): ?>
                <tr>
                    <th>Issuance Bank:</th>
                    <td>
                        <?php echo htmlspecialchars($firstEntry['c_bank']); ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $firstEntry['c_mop'] == 2 ? 'Check No' : 'Reference No'; ?>:</th>
                    <td>
                        <?php echo htmlspecialchars($firstEntry['c_check_no']); ?>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Transaction Date:</th>
                    <td><?php
                        $dateTime = new DateTime($firstEntry['c_tran_date']);
                        echo htmlspecialchars($dateTime->format('Y-m-d')); 
                    ?>
                    </td>
                </tr>
                <tr>
                    <th>Remarks:</th>
                    <td style="max-width: 200px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($firstEntry['c_remarks']); ?>
                    </td>
                </tr>
                <tr>
                    <th>Pay Date:</th>
                    <td><?php echo $firstEntry['c_car_paydate']; ?></td>
                </tr>
                <tr>
                    <th>Encoded by:</th>
                    <?php
                        $c_encoded_by =  $firstEntry['c_encoded_by']; 
                        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                        $results = odbc_exec($conn, $get_encoder_details_qry);

                        if ($encoder = odbc_fetch_array($results)) {
                            $realname = $encoder["c_realname"];
                        }
                    ?>
                    <td><?php echo $realname; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
    } else {
        echo "No data found for the given car number.";
    }
}
?>
