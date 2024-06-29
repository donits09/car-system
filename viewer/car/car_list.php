<?php
session_start();

include('../../config.php');

if (isset($_GET['buyer_acc_no'])) {
    $account_no = $_GET['buyer_acc_no'];
} else {
    $account_no = '';
}

$car_list_query = "SELECT * FROM t_car_payment WHERE c_account_no = ? AND status != 1";
$stmt = odbc_prepare($conn, $car_list_query);

$hasRows = false;

if ($stmt && odbc_execute($stmt, array($account_no))) {
    $i = 1; // Initialize the counter
    while ($row = odbc_fetch_array($stmt)) {
        $row_class = $row['e_status'] == 1 ? 'green-row' : ''; // Determine row class based on status

        // Fetch buyer details
        $c_buyer_acc = $row['c_account_no'];
        $buyer_name = "Unknown";
        if (!empty($c_buyer_acc)) {
            $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
            $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);

            if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                $buyer_details = odbc_fetch_array($buyer_stmt);
                if ($buyer_details) {
                    $buyer_name = htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]);
                }
            }
        }

        // Fetch phase details
        $phase_display = "-----";
        $c_account_no = $row['c_account_no'];
        if (!empty($c_account_no)) {
            $c_phase = substr($c_account_no, 0, 3);
            $c_block = ltrim(substr($c_account_no, 3, 3), '0');
            $c_lot = substr($c_account_no, 6, 2);

            $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
            $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

            if (odbc_execute($phase_stmt, array($c_phase))) {
                $phase_details = odbc_fetch_array($phase_stmt);
                if ($phase_details) {
                    $phase_display = htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                }
            }
        }

        // Output row
        ?>
        <tr class="<?php echo $row_class; ?>">
            <td class="text-center"><?php echo $i++; ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['c_account_no']); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['c_car_no']); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['c_car_type']); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($buyer_name); ?></td>
            <td class="text-center"><?php echo $phase_display; ?></td>
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
            <?php
            $c_encoded_by = $row['c_encoded_by'];
            $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
            $results = odbc_exec($conn, $get_encoder_details_qry);

            if ($encoder = odbc_fetch_array($results)) {
                $realname = htmlspecialchars($encoder["c_realname"]);
            } else {
                $realname = "Unknown";
            }
            ?>
            <td class="text-center"><?php echo $realname; ?></td>
            <td align="center">
                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    Action <?php if ($row['e_status'] == 1) { echo '<span class="fa fa-lock"></span>'; } ?>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo htmlspecialchars($row['id']); ?>">
                        <span class="fa fa-eye text-primary"></span> View
                    </a>
                    <?php if ($row['e_status'] == 0){ ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item edit_data" href="javascript:void(0)"
                            data-id="<?php echo htmlspecialchars($row['id']); ?>"
                            data-account-no="<?php echo htmlspecialchars($row['c_account_no']); ?>"
                            data-payment-type="<?php echo htmlspecialchars($row['c_car_type']); ?>"
                            data-amount="<?php echo htmlspecialchars($row['c_car_amount']); ?>"
                            data-car-no="<?php echo htmlspecialchars($row['c_car_no']); ?>"
                            data-pay-date="<?php echo htmlspecialchars($row['c_car_paydate']); ?>"
                            data-encoder="<?php echo htmlspecialchars($row['c_encoded_by']); ?>">
                            <span class="fa fa-edit text-info"></span> Edit
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item delete_data" href="javascript:void(0)"
                            data-id="<?php echo htmlspecialchars($row['id']); ?>"
                            data-car-no="<?php echo htmlspecialchars($row['c_car_no']); ?>">
                            <span class="fa fa-trash text-danger"></span> Delete
                        </a>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <?php
    }
}
?>
