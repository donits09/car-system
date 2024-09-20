<?php
    session_start();
    require_once('../../inc/check_session.php');
    check_user_group(1);
    $c_encoded_by = '';
    include('../../config.php');
    
    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $accountId = $_GET['id'];
        $get_car_query = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                    a.c_car_paydate, a.c_car_amount, a.c_encoded_by, a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank, 
                    b.c_name, b.c_phase, b.c_block, b.c_lot, a.c_check_no, a.c_remarks
                        FROM t_car_payment a
                        LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no 
                        WHERE a.id = ?";
        $stmt = odbc_prepare($conn, $get_car_query);
        odbc_execute($stmt, array($accountId));
        $result = odbc_fetch_array($stmt);

        if ($result) {
            $row = $result;

            // Break down car types and amounts
            $carTypes = explode(',', $row['c_car_type']); // Assuming comma-separated types
            $carAmounts = explode(',', $row['c_car_amount']); // Assuming corresponding comma-separated amounts
?>
<link rel="stylesheet" href="../../dist/css/view_car.css">
<div class="container-fluid">
    <div class="callout callout-primary">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th style="width: 30%;">Account No.:</th>
                    <td><?php echo !empty($row['c_account_no']) ? $row['c_account_no'] : '----------'; ?></td>
                </tr>
                <tr>
                    <th>CAR No.:</th>
                    <td><?php echo $row['c_car_no']; ?></td>
                </tr>
                <!-- Car Types and Amounts Breakdown -->
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
                                // Iterate through the car types and amounts
                                foreach ($carTypes as $index => $type) {
                                    $amount = isset($carAmounts[$index]) ? $carAmounts[$index] : 0;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(trim($type)); ?></td>
                                        <td><?php echo number_format((float)$amount, 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>Name:</th>
                    <td>
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
                </tr>
                <!-- The rest of the fields remain unchanged -->
                <tr>
                    <th>Location:</th>
                    <td>
                    <?php
                        // Phase, Block, Lot Logic
                        // Your existing code for location will go here
                    ?>
                    </td>
                </tr>
                <tr>
                    <th>Amount:</th>
                    <td><?php echo number_format($row['c_car_amount'],2); ?></td>
                </tr>
                <tr>
                    <th>Mode of Payment:</th>
                    <td>
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
                </tr>
                <?php if ($row['c_mop'] == 2 || $row['c_mop'] == 3): ?>
                <tr>
                    <th>Issuance Bank:</th>
                    <td><?php echo htmlspecialchars($row['c_bank']); ?></td>
                </tr>
                <tr>
                    <th><?php echo $row['c_mop'] == 2 ? 'Check No' : 'Reference No'; ?>:</th>
                    <td><?php echo htmlspecialchars($row['c_check_no']); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>Transaction Date:</th>
                    <td><?php
                        $dateTime = new DateTime($row['c_tran_date']);
                        echo htmlspecialchars($dateTime->format('Y-m-d')); 
                    ?>
                    </td>
                </tr>
                <tr>
                    <th>Remarks:</th>
                    <td style="max-width: 200px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($row['c_remarks']); ?>
                    </td>
                </tr>
                <tr>
                    <th>Pay Date:</th>
                    <td><?php echo $row['c_car_paydate']; ?></td>
                </tr>
                <tr>
                    <th>Encoded by:</th>
                    <?php
                        $c_encoded_by =  $row['c_encoded_by']; 
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
            echo "No data found for the given ID.";
        }
    } else {
        echo "Invalid request.";
    }
?>