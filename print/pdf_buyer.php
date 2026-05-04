<?php
session_start();
require '../dompdf/vendor/autoload.php';
include('../config.php');

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$l_css = '../dist/css/pdf.css';
$l_css_path = file_get_contents($l_css);

if (isset($_GET['id'])) {
    $c_account_no = $_GET['id'];

    function fetchCarPayments($conn, $carNo) {
        $query = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                        a.c_car_paydate, a.c_car_amount, a.c_encoded_by,
                        a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank,
                        b.c_name, b.c_phase, b.c_block, b.c_lot, a.status
                    FROM t_car_payment a
                    LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                    WHERE a.c_account_no = ? AND a.status != '1'
                    ORDER BY a.c_tran_updated DESC";

        $stmt = odbc_prepare($conn, $query);
        if (!$stmt || !odbc_execute($stmt, array($carNo))) {
            return null;
        }

        $rows = [];
        while ($row = odbc_fetch_array($stmt)) {
            $rows[] = $row;
        }
        return $rows;
    }

    function fetchBuyerDetails($conn, $accountNo) {
        $query = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
        $stmt = odbc_prepare($conn, $query);
        odbc_execute($stmt, array($accountNo));
        $details = odbc_fetch_array($stmt);
        return $details ? htmlspecialchars($details["c_b1_first_name"] . ' ' . $details["c_b1_last_name"]) : "";
    }

    function fetchPhaseDetails($conn, $phaseCode) {
        $query = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
        $stmt = odbc_prepare($conn, $query);
        odbc_execute($stmt, array($phaseCode));
        $details = odbc_fetch_array($stmt);
        return $details ? htmlspecialchars($details["c_acronym"]) : "";
    }

    function fetchEncoderName($conn, $employeeCode) {
        $query = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
        $stmt = odbc_prepare($conn, $query);
        odbc_execute($stmt, array($employeeCode));
        $details = odbc_fetch_array($stmt);
        return $details ? htmlspecialchars($details["c_realname"]) : "";
    }

    $c_employee_code = $_SESSION['username'];
    $encoder_stmt = odbc_prepare($conn, "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?");
    odbc_execute($encoder_stmt, array($c_employee_code));
    $encoder = odbc_fetch_array($encoder_stmt);
    $c_realname = $encoder ? htmlspecialchars($encoder["c_realname"]) : "Unknown";

    $carData = fetchCarPayments($conn, $c_account_no);
    $buyer_name = fetchBuyerDetails($conn, $c_account_no);

    $c_phase = substr($c_account_no, 0, 3);
    $c_block = 'B' . ltrim(substr($c_account_no, 3, 3), '0');
    $c_lot = 'L' . substr($c_account_no, 6, 2);
    $phase_acronym = fetchPhaseDetails($conn, $c_phase) . ' ' . $c_block . ' ' . $c_lot;

    $html = '
<html>
<head>
<style>' . $l_css_path . '</style>
</head>
<body>

<header>
    <h2>ASIAN LAND STRATEGIES CORPORATION</h2>
    <h3>CASH ACKNOWLEDGEMENT RECEIPT</h3>

    <div class="info-box"><p class="b-info">Account No:</p><p>' . htmlspecialchars($c_account_no) . '</p></div>
    <div class="info-box"><p class="b-info">Buyers Name:</p><p>' . $buyer_name . '</p></div>
    <div class="info-box"><p class="b-info">Project Site:</p><p>' . $phase_acronym . '</p></div>
</header>

<footer>
    <p>Printed By: ' . $c_realname . '</p>
</footer>

<table border="1" cellspacing="0" cellpadding="5">
<thead>
<tr>
    <th>No.</th>
    <th>CAR No.</th>
    <th>Transaction Type</th>
    <th>Location</th>
    <th>Cash/Online</th>
    <th>Check</th>
    <th>Bank</th>
    <th>Total</th>
    <th>Status</th>
    <th>Transaction Date</th>
    <th>Payment Date</th>
    <th>Encoded By</th>
</tr>
</thead>
<tbody>
';

    if (!empty($carData)) {
        $counter = 1;
        $totalCashOnline = 0;
        $totalCheck = 0;
        $totalOfall = 0;

        foreach ($carData as $row) {

            $actualAmount = $row['c_car_amount'];
            $isBounce = ($row['status'] == 2);

            // DISPLAY (negative if bounce)
            $displayAmount = $isBounce ? -$actualAmount : $actualAmount;

            $cashAmount = ($row['c_mop'] == 1) ? $displayAmount : 0;
            $checkAmount = ($row['c_mop'] == 2) ? $displayAmount : 0;
            $checkOnline = ($row['c_mop'] == 3) ? $displayAmount : 0;

            // TOTALS (exclude bounce)
            $totalCashAmount = ($row['c_mop'] == 1 && !$isBounce) ? $actualAmount : 0;
            $totalCheckAmount = ($row['c_mop'] == 2 && !$isBounce) ? $actualAmount : 0;
            $totalOnlineAmount = ($row['c_mop'] == 3 && !$isBounce) ? $actualAmount : 0;

            $totalCashOnline += $totalCashAmount + $totalOnlineAmount;
            $totalCheck += $totalCheckAmount;
            $totalOfall += $totalCashAmount + $totalCheckAmount + $totalOnlineAmount;

            $l_encode = fetchEncoderName($conn, $row['c_encoded_by']);

            $statusTex = '-';
            if ($row['status'] == 2) $statusTex = 'BOUNCE CHECK';

            $displayCashOnline = $cashAmount + $checkOnline;
            $displayCheck = $checkAmount;
            $displayTotal = $displayCashOnline + $displayCheck;

            $html .= '
            <tr>
                <td>' . $counter++ . '</td>
                <td>' . htmlspecialchars($row['c_car_no']) . '</td>
                <td>' . htmlspecialchars($row['c_car_type']) . '</td>
                <td>' . $phase_acronym . '</td>

                <td>' . ($displayCashOnline < 0 ? '-' : '') . number_format(abs($displayCashOnline), 2) . '</td>
                <td>' . ($displayCheck < 0 ? '-' : '') . number_format(abs($displayCheck), 2) . '</td>
                <td>' . htmlspecialchars($row['c_bank'] ?: '-') . '</td>
                <td>' . ($displayTotal < 0 ? '-' : '') . number_format(abs($displayTotal), 2) . '</td>

                <td>' . $statusTex . '</td>

                <td>' . htmlspecialchars((new DateTime($row['c_tran_date']))->format('Y-m-d')) . '</td>
                <td>' . htmlspecialchars($row['c_car_paydate']) . '</td>
                <td>' . $l_encode . '</td>
            </tr>';
        }

        $html .= '
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4"></td>
            <td>' . number_format($totalCashOnline, 2) . '</td>
            <td>' . number_format($totalCheck, 2) . '</td>
            <td></td>
            <td>' . number_format($totalOfall, 2) . '</td>
            <td colspan="4"></td>
        </tr>
        </tfoot>
        </table>';
    }

    $html .= '</body></html>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('legal', 'landscape');
    $dompdf->render();
    $dompdf->stream("car_buyers_payment.pdf", ["Attachment" => 0]);
}
?>