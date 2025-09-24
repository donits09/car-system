<?php
session_start();

require '../dompdf/vendor/autoload.php';
include('../config.php');

use Dompdf\Dompdf;

$accountNo = isset($_GET['account_no']) ? preg_replace('/\D/', '', $_GET['account_no']) : '';
$location  = isset($_GET['location'])   ? preg_replace('/[^A-Z0-9]/i', '', $_GET['location']) : '';

$sql = "
    SELECT
        b.c_b1_last_name,
        b.c_b1_first_name,
        b.c_b1_middle_name,
        b.c_address,
        b.c_city_prov,
        b.c_account_no,
        l.c_name   AS log_name,
        l.c_date   AS log_date,
        l.c_time   AS log_time,
        l.c_module AS log_module,
        l.c_notes  AS log_notes
    FROM t_buyers_account b
    LEFT JOIN t_log l
        ON l.c_module IN ('Reservation-Payment', 'Payments', 'Payment Details')
        AND l.c_notes LIKE '%' || CAST(b.c_account_no AS VARCHAR(50)) || '%'
    WHERE 1=1
";

if ($accountNo !== '') {
    $sql .= " AND b.c_account_no = '" . $accountNo . "' ";
} elseif ($location !== '') {
    $sql .= " AND l.c_notes LIKE '%' || '" . $location . "' || '%' ";
}

$sql .= " ORDER BY b.c_account_no, l.c_date DESC, l.c_time DESC";

$result = odbc_exec($conn, $sql);
if (!$result) {
    die("Query failed: " . odbc_errormsg($conn));
}

function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$buyerName = '';
$buyerAddress = '';
$buyerAcct = $accountNo;

if ($firstRow = odbc_fetch_array($result)) {
    $buyerName = trim(($firstRow['c_b1_last_name'] ?? '') . ', ' . ($firstRow['c_b1_first_name'] ?? '') . ' ' . ($firstRow['c_b1_middle_name'] ?? ''));
    $buyerAddress = trim(($firstRow['c_address'] ?? '') . ' ' . ($firstRow['c_city_prov'] ?? ''));
    $buyerAcct = $firstRow['c_account_no'] ?? $accountNo;

    odbc_fetch_row($result, 0);
}

$rowsHtml = '';
$counter = 1;
while ($row = odbc_fetch_array($result)) {
    $rowsHtml .= '<tr>
        <td>'. $counter++ .'</td>
        <td>'.h($row['log_name']  ?? "").'</td>
        <td>'.h($row['log_date']  ?? "").'</td>
        <td>'.h($row['log_time']  ?? "").'</td>
        <td>'.h($row['log_module']?? "").'</td>
        <td>'.h($row['log_notes'] ?? "").'</td>
    </tr>';
}
if ($rowsHtml === '') {
    $rowsHtml = '<tr><td colspan="6" style="text-align:center;">No records found</td></tr>';
}

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Payment Log\'s Details</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h3 { text-align: center; margin: 0 0 12px 0; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #444; padding: 6px; vertical-align: top; }
    th { background: #f0f0f0; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
</style>
</head>
<body>
<h3>Payment Log\'s Details</h3>
<p><strong>Buyer:</strong> '.h($buyerName).'<br>
<strong>Address:</strong> '.h($buyerAddress).'<br>
<strong>Account No:</strong> '.h($buyerAcct).'</p>
<table>
    <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:15%;">Log User</th>
            <th style="width:15%;">Log Date</th>
            <th style="width:15%;">Log Time</th>
            <th style="width:15%;">Log Module</th>
            <th style="width:35%;">Log Notes</th>
        </tr>
    </thead>
    <tbody>'.$rowsHtml.'</tbody>
</table>
</body>
</html>
';

$dompdf = new Dompdf(['isRemoteEnabled' => true]);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$filename = "payment_logs";

if ($accountNo !== '') {
    $filename .= "_acct_" . $accountNo;
} elseif ($location !== '') {
    $filename .= "_loc_" . $location;
}

$filename .= ".pdf";

$dompdf->stream($filename, ["Attachment" => true]);
