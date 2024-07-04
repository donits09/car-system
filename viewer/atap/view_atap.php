<?php
session_start();

include('../../config.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $atapId = $_GET['id'];
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
    WHERE 
        a.status != 2 AND a.id = ?
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
        b.c_lot";
    $stmt = odbc_prepare($conn, $get_atap);
    odbc_execute($stmt, array($atapId));
    $row = odbc_fetch_array($stmt);

    if ($row) {
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
                            <th style="width: 30%;">ATAP No.:</th>
                            <td><?php echo htmlspecialchars($row['c_atap_no'] ?: '----------'); ?></td>
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
                            <th>Status:</th>
                            <td>
                                <?php
                                if ($row['status'] == 1) {
                                    echo "PAID";
                                } elseif ($row['status'] == 2) {
                                    echo "CANCELLED";
                                } else {
                                    echo "PENDING";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Remarks:</th>
                            <td>
                                <?php echo htmlspecialchars($row['atap_remarks']); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Transaction Date:</th>
                            <td><?php
                                $dateTime = new DateTime($row['c_tran_date']);
                                echo htmlspecialchars($dateTime->format('Y-m-d'));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Encoded by:</th>
                            <td>
                                <?php
                                $c_encoded_by = $row['c_encoded_by'];
                                $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                                $results = odbc_exec($conn, $get_encoder_details_qry);

                                if ($encoder = odbc_fetch_array($results)) {
                                    $realname = $encoder["c_realname"];
                                    echo htmlspecialchars($realname);
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Transaction Type</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $atapNo = $_GET['no'];
                    $get_atap_items = "SELECT * FROM t_atap_items WHERE c_atap_no = ?";
                    $stmt_items = odbc_prepare($conn, $get_atap_items);
                    odbc_execute($stmt_items, array($atapNo));

                    $totalAmount = 0;

                    while ($row_items = odbc_fetch_array($stmt_items)) {
                        $amount = $row_items['c_atap_amount'];
                        $totalAmount += $amount; 

                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row_items['c_tran_type']); ?></td>
                            <td class="text-right"><?php echo number_format($amount, 2); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-right">Total Amount:</th>
                            <th class="text-right"><?php echo number_format($totalAmount, 2); ?></th>
                        </tr>
                    </tfoot>
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
