<?php
$dsn = "PostgreSQL30"; 
$user = "postgres";    
$pass = "admin12345";    

$conn = odbc_connect($dsn, $user, $pass);

if (isset($_GET['username'])) {
    $username = $_GET['username'];
} else {
    $username = 'Unknown'; 
}

if (isset($_GET['buyer_acc_no'])) {
    $account_no = $_GET['buyer_acc_no'];
} else {
    $account_no = '';
}

if (!empty($account_no)) {
    $get_atap = "SELECT 
    a.c_account_no,
    a.id,
    a.c_atap_no, 
    a.c_tran_date, 
    a.status, 
    SUM(b.c_atap_amount) AS total_amount
FROM 
    t_atap a 
JOIN 
    t_atap_items b 
ON 
    a.c_atap_no = b.c_atap_no 
WHERE a.status = 0 AND a.c_account_no = ?
GROUP BY 
    a.id,
    a.c_account_no,
    a.c_atap_no, 
    a.c_tran_date, 
    a.status 
ORDER BY 
    MAX(a.c_tran_updated) DESC";
    $stmt = odbc_prepare($conn, $get_atap);

    if ($stmt && odbc_execute($stmt, array($account_no))) {
        $i = 1;

        while ($row = odbc_fetch_array($stmt)) {
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
                    echo "Unknown";
                }
                ?>
            </td>
            <td class="text-center"><?php echo number_format($row['total_amount'], 2); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['c_tran_date']); ?></td>
            <td class="text-center"><?php echo $row['status'] == 0 ? 'PENDING' : 'PAID'; ?></td>
            <td align="center">
                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    Action
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item view_atap" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-no="<?php echo $row['c_atap_no'] ?>">
                        <span class="fa fa-eye text-primary"></span> View
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item edit_atap" href="javascript:void(0)" 
                        data-id="<?php echo $row['id']; ?>"
                        data-no="<?php echo $row['c_atap_no'] ?>">
                        <span class="fa fa-edit text-primary"></span> Edit
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-no="<?php echo $row['c_atap_no']; ?>">
                        <span class="fa fa-ban text-danger"></span> Cancel
                    </a>
                </div>
            </td>
        </tr>
        <?php 
        }
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No data found.</td></tr>";
}
?>
