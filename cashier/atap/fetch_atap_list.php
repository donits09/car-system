<?php
$dsn = "PostgreSQL30";
$user = "postgres";
$pass = "admin12345";

$conn = odbc_connect($dsn, $user, $pass);
if (!$conn) {
    die('Failed to connect to database: ' . odbc_errormsg());
}

$username = isset($_GET['username']) ? $_GET['username'] : 'Unknown';
$account_no = isset($_GET['buyer_acc_no']) ? $_GET['buyer_acc_no'] : '';

if (!empty($account_no)) {
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
            WHERE a.c_account_no = ?
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
    if (!$stmt) {
        die('Failed to prepare SQL statement: ' . odbc_errormsg());
    }

    if (odbc_execute($stmt, array($account_no))) {
        $i = 1;
        $totalAmount = 0;

        while ($row = odbc_fetch_array($stmt)) {
            $totalAmount += $row['total_amount'];
            ?>
            <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['c_account_no']); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['c_atap_no']); ?></td>
                <td class="text-center">
                    <?php
                    $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';

                    if (!empty($c_buyer_acc)) {
                        $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
                        $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);

                        if ($buyer_stmt && odbc_execute($buyer_stmt, array($c_buyer_acc))) {
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
                <td class="text-center"><?php echo number_format($row['total_amount'], 2); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($row['c_tran_date']); ?></td>
                <td class="text-center"><?php 
                    if ($row['status'] == 0){
                        echo  'PENDING' ; 
                    } else if($row['status'] == 1){
                        echo  'PAID' ; 
                    } else {
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
        echo "<script>$('#totalAtapAmount').text('" . number_format($totalAmount, 2) . "');</script>";
    } else {
        die('Failed to execute SQL statement: ' . odbc_errormsg());
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No data found.</td></tr>";
    echo "<script>$('#totalAtapAmount').text('0.00');</script>";
}
?>