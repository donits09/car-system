<?php
    session_start();

    require_once('../../inc/check_session.php');
    check_user_group(1);
    $c_encoded_by = '';
    include('../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $accountId = $_GET['id'];
        $get_or_query = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                    a.c_or_paydate,a.c_or_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop,a.c_bank, b.c_name, b.c_phase,
                    b.c_block, b.c_lot, a.c_check_no, a.c_remarks
                        FROM t_or_payment a
                        LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no WHERE a.id = ?";
        $stmt = odbc_prepare($conn, $get_or_query);
        odbc_execute($stmt, array($accountId));
        $result = odbc_fetch_array($stmt);
        if($result){
        $row = $result;
?>
<link rel="stylesheet" href="../../dist/css/view_car.css">
<div class="container-fluid">
    <div class="callout callout-primary">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th style="width: 30%;">Account No.:</th>
                    <?php if ($row['c_account_no'] != "" || $row['c_account_no'] != null) { ?>
                        <td><?php echo $row['c_account_no']; ?></td>
                    <?php }else{ ?>
                        <td>----------</td>
                    <?php } ?>
                </tr>
                <tr>
                    <th>OR No.:</th>
                    <td><?php echo $row['c_or_no']; ?></td>
                </tr>
                <tr>
                    <th>Transaction Type:</th>
                    <td><?php echo $row['c_or_type']; ?></td>
                </tr>
                <tr>
                    <th style="width: 30%;">Name:</th>
                    <?php
                        $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';

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
                            echo '<td>' . htmlspecialchars($row['c_name']) . '</td>';
                        }
                    ?>
                </tr>
                <tr>
                    <th style="width: 30%;">Location:</th>
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
                </tr>
                <tr>
                    <th>Amount:</th>
                    <td><?php echo number_format($row['c_or_amount'],2); ?></td>
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
                    <td>
                        <?php echo htmlspecialchars($row['c_bank']); ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $row['c_mop'] == 2 ? 'Check No' : 'Reference No'; ?>:</th>
                    <td>
                        <?php echo htmlspecialchars($row['c_check_no']); ?>
                    </td>
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
                    <td><?php echo $row['c_or_paydate']; ?></td>
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
