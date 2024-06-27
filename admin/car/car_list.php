<?php
session_start();

// require_once('../../inc/check_session.php');
// check_user_group(1);

?>
<?php
include('../../config.php');
$account_no = $_GET['account_no'];
$car_list = "SELECT * FROM t_car_payment WHERE c_account_no = ? and status != 1 ORDER BY c_tran_date";
$stmt = odbc_prepare($conn, $car_list);
$hasRows = false;
if ($stmt && odbc_execute($stmt, array($account_no))) {
    while ($row = odbc_fetch_array($stmt)) {
        if (!empty($row['c_account_no'])) {
            $hasRows = true;
            break; 
        }
    }
    if ($hasRows) {
        odbc_execute($stmt, array($account_no));
        $i = 1;
        while ($row = odbc_fetch_array($stmt)): 
?>
<link rel="stylesheet" href="../../dist/css/manage_car.css">
<tr>
    <td class="text-center"><?php echo $i++; ?></td>
    <td class="text-center"><?php echo $row['c_account_no']; ?></td>
    <td class="text-center"><?php echo $row['c_car_no']; ?></td>
    <td class="text-center"><?php echo $row['c_car_type']; ?></td>
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
    <td class="text-center"><?php echo number_format($row['c_car_amount'],2); ?></td>
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
    <td class="text-center"><?php echo $row['c_car_paydate']; ?></td>
    <?php
        $c_encoded_by = $row['c_encoded_by'];
        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
        $results = odbc_exec($conn, $get_encoder_details_qry);

        if ($encoder = odbc_fetch_array($results)) {
            $realname = $encoder["c_realname"];
        }
    ?>
    <td class="text-center"><?php echo $realname; ?></td>
    <td align="center">
        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
            Action
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">
            <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                <span class="fa fa-eye text-primary"></span> View
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item edit_data" href="javascript:void(0)" 
            data-id="<?php echo $row['id']; ?>" 
            data-account-no="<?php echo $row['c_account_no']; ?>" 
            data-payment-type="<?php echo $row['c_car_type']; ?>" 
            data-amount="<?php echo $row['c_car_amount']; ?>" 
            data-car-no="<?php echo $row['c_car_no']; ?>" 
            data-pay-date="<?php echo $row['c_car_paydate']; ?>" 
            data-encoder="<?php echo $row['c_encoded_by']; ?>">
                <span class="fa fa-edit text-info"></span> Edit
            </a>
            <div class="dropdown-divider"></div>
            <div class="card-tools">
                <a class="dropdown-item" href="<?php echo base_url ?>print/print_car.php?id=<?php echo $row['c_car_no']; ?>" target="_blank">
                    <span class="fas fa-print"></span> Print
                </a>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-car-no="<?php echo $row['c_car_no']; ?>">
                <span class="fa fa-trash text-danger"></span> Delete
            </a>
        </div>
    </td>
</tr>
<?php 
        endwhile; 
    }
}
?>
