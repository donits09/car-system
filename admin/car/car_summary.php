<?php
header('Content-Type: text/html; charset=UTF-8');
require_once('../../config.php');

if (!isset($_GET['buyer_acc_no'])) {
    echo '<tr><td colspan="6" class="text-center">No account number provided</td></tr>';
    exit;
}

$account_no = $_GET['buyer_acc_no'];

$car_summary_query = "
        SELECT c_car_type, MAX(id) AS id, COUNT(*) AS car_type_count, MAX(c_car_paydate) AS last_pay_date, SUM(c_car_amount) AS total_amount
        FROM t_car_payment WHERE c_account_no = ? AND status != 1 GROUP BY c_car_type";

$stmt = odbc_prepare($conn, $car_summary_query);

if ($stmt === false) {
    echo '<tr><td colspan="6" class="text-center">Query preparation failed</td></tr>';
    exit;
}

$result = odbc_execute($stmt, array($account_no));

$totalAmount = 0;
if ($result) {
    $no = 1;
    while ($row = odbc_fetch_array($stmt)) {
        $totalAmount += $row['total_amount'] ?? 0;
        $format_pay_date = date('Y-m-d', strtotime($row['last_pay_date']));
        
        echo '<tr>';
        echo '<td style="text-align: center;">' . $no++ . '</td>';
        echo '<td style="text-align: center;">' . htmlspecialchars($row['c_car_type']) . '</td>';
        echo '<td style="text-align: center;">' . htmlspecialchars($row['car_type_count']) . '</td>';
        echo '<td style="text-align: center;">' . htmlspecialchars($format_pay_date) . '</td>';
        echo '<td style="text-align: center;">' . number_format($row['total_amount'], 2) . '</td>';
        
        echo '<td align="center">
                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    Action';
        echo '  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item view_summary" href="javascript:void(0)" data-id="' . $row['id'] . '" data-car-type="' . htmlspecialchars($row['c_car_type']) . '">View</a>
                </div>
              </td>';
        
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6" class="text-center">No records found</td></tr>';
}

echo "<script>$('#totalAmountSummary').text('" . number_format($totalAmount, 2) . "');</script>";
?>
