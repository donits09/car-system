<?php
$dsn = "PostgreSQL30";
$user = "postgres";
$pass = "admin12345";

$conn = odbc_connect($dsn, $user, $pass);
if (!$conn) {
    die('Failed to connect to database: ' . odbc_errormsg());
}

$id = 1;
if (isset($_GET['acct_no']) && $_GET['acct_no'] !== '') {
    $id = $_GET['acct_no'];
}

$qry4 = "SELECT * FROM t_payment WHERE c_account_no = ? order by c_payment_count , c_due_date";
$stmt = odbc_prepare($conn, $qry4);

if ($stmt) {
    $result = odbc_execute($stmt, array($id));
    if ($result) {
        echo '<table><tbody>'; 
        while ($row = odbc_fetch_array($stmt)) {
            $total_rebate = 0;
            $due_dte = $row['c_due_date'];
            $pay_dte = $row['c_pay_date'];
            $or_no = $row['c_or_no'];
            $amt_paid = $row['c_amount_paid'];
            $interest = $row['c_interest'];
            $principal = $row['c_principal'];
            $surcharge = $row['c_surcharge'];
            $rebate = $row['c_rebate'];
            $period = $row['c_status'];
            $balance = $row['c_balance'];

            $total_rebate += $rebate;
            echo "<tr>
                <td class='text-center' style='font-size:13px;width:12%;'>{$due_dte}</td>
                <td class='text-center' style='font-size:13px;width:12%;'>{$pay_dte}</td>
                <td class='text-center' style='font-size:13px;width:10%;'>";
            
            if (strpos($or_no, 'RSTR') === 0) {
                echo "<a class='basic-link view_restruc' data-id='{$row['c_account_no']}' cid='" . str_replace('RSTR-', '', $or_no) . "'>{$or_no}</a>";
            } elseif (strpos($or_no, 'AV') === 0) {
                echo "<a class='basic-link view_av' data-id='{$row['c_account_no']}' cid='{$or_no}'>{$or_no}</a>";
            } elseif (strpos($or_no, 'CM') === 0 || strpos($or_no, 'DM') === 0) {
                $newId = substr($or_no, 2);
                echo "<a class='basic-link view_cm' data-id='{$or_no}'>{$or_no}</a>";
            } else {
                echo $or_no;
            }
            echo "</td>
                <td class='text-center' style='font-size:13px;width:15%;'>" . number_format($amt_paid, 2) . "</td>
                <td class='text-center' style='font-size:13px;width:10%;'>" . number_format($surcharge, 2) . "</td>
                <td class='text-center' style='font-size:13px;width:10%;'>" . number_format($interest, 2) . "</td>
                <td class='text-center' style='font-size:13px;width:10%;'>" . number_format($principal, 2) . "</td>
                <td class='text-center' style='font-size:13px;width:10%;'>" . number_format($rebate, 2) . "</td>
                <td class='text-center' style='font-size:13px;width:10%;'>{$period}</td>
                <td class='text-center' style='font-size:13px;width:10%;'>" . number_format($balance, 2) . "</td>
            </tr>";
        }
        echo '</tbody></table>'; 
    } else {
        echo "Failed to execute query: " . odbc_errormsg($conn);
    }
} else {
    echo "Failed to prepare query: " . odbc_errormsg($conn);
}
?>
