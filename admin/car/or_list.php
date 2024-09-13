<?php
session_start();
include('../../config.php');

$account_no = $_GET['buyer_acc_no'];

$total_amount = 0;
$get_total_amount = "SELECT SUM(c_or_amount) AS total_amount FROM t_or_payment WHERE c_account_no = ? AND status != 1";
$stmt_total = odbc_prepare($conn, $get_total_amount);

if (odbc_execute($stmt_total, array($account_no))) {
    $row_total = odbc_fetch_array($stmt_total);
    if ($row_total) {
        $total_amount = $row_total['total_amount'];
    } else {
        echo "No total amount found.";
    }
} else {
    echo "Failed to execute total amount query.";
}

$c_or_total_amount = number_format($total_amount, 2, '.', ',');

$or_list = "SELECT * FROM t_or_payment WHERE c_account_no = ? AND status != 1 ORDER BY c_tran_updated DESC";
$stmt = odbc_prepare($conn, $or_list);
$hasRows = false;

if ($stmt && odbc_execute($stmt, array($account_no))) {
    $rows = [];
    while ($row = odbc_fetch_array($stmt)) {
        $rows[] = $row;
    }

    if (count($rows) > 0) {
        $i = 1;
        foreach ($rows as $row) {
            $row_class = $row['e_status'] == 1 ? 'green-row' : '';
?>
<link rel="stylesheet" href="../../dist/css/manage_car.css">
<tr class="<?php echo $row_class; ?>">
    <td class="text-center"><?php echo $i++; ?></td>
    <td class="text-center"><?php echo $row['c_account_no']; ?></td>
    <td class="text-center"><?php echo $row['c_or_no']; ?></td>
    <td class="text-center">
        <?php
        $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';
        if (!empty($c_buyer_acc)) {
            $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
            $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
            
            if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                $buyer_details = odbc_fetch_array($buyer_stmt);
                echo $buyer_details ? htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]) : "-";
            } else {
                echo "-";
            }
        } else {
            echo htmlspecialchars($row['c_name']);
        }
        ?>
    </td>
    <td class="text-center"><?php echo number_format($row['c_or_amount'], 2); ?></td>
    <td class="text-center"><?php echo $row['c_or_paydate']; ?></td>
    <?php
    $c_encoded_by = $row['c_encoded_by'];
    $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = ?";
    $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
    $realname = '-';
    if (odbc_execute($encoder_stmt, array($c_encoded_by))) {
        if ($encoder = odbc_fetch_array($encoder_stmt)) {
            $realname = $encoder["c_realname"];
        }
    }
    ?>
    <td class="text-center"><?php echo $realname; ?></td>
    <td align="center">
        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
            Action <?php if ($row['e_status'] == 1) { echo '<span class="fa fa-lock"></span>'; } ?>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">
            <a class="dropdown-item view_or" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item edit_or" href="javascript:void(0)" 
            data-id="<?php echo $row['id']; ?>" 
            data-account-no="<?php echo $row['c_account_no']; ?>" 
            data-payment-type="<?php echo $row['c_or_type']; ?>" 
            data-amount="<?php echo $row['c_or_amount']; ?>" 
            data-or-no="<?php echo $row['c_or_no']; ?>" 
            data-pay-date="<?php echo $row['c_or_paydate']; ?>" 
            data-encoder="<?php echo $row['c_encoded_by']; ?>">Edit</a>
            <div class="dropdown-divider"></div>
            <div class="card-tools">
                <a class="dropdown-item" href="<?php echo base_url ?>print/print_or.php?id=<?php echo $row['c_or_no']; ?>" target="_blank">Print</a>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item delete_or" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-or-no="<?php echo htmlspecialchars($row['c_or_no']); ?>">Cancel</a>
        </div>
    </td>
</tr>
<?php
        }
    }
}
?>
