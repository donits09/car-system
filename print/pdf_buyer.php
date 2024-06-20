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
                          a.c_car_paydate, a.c_car_amount, a.c_encoded_by, a.c_tran_date, a.c_tran_updated, a.c_mop,
                          b.c_name, b.c_phase, b.c_block, b.c_lot
                  FROM t_car_payment a
                  LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                  WHERE a.c_account_no = ?";
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
        if (!$stmt || !odbc_execute($stmt, array($accountNo))) {
            return null;
        }
        $details = odbc_fetch_array($stmt);
        return $details ? htmlspecialchars($details["c_b1_first_name"] . ' ' . $details["c_b1_last_name"]) : "";
    }

    function fetchPhaseDetails($conn, $phaseCode) {
        $query = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
        $stmt = odbc_prepare($conn, $query);
        if (!$stmt || !odbc_execute($stmt, array($phaseCode))) {
            return null;
        }
        $details = odbc_fetch_array($stmt);
        return $details ? htmlspecialchars($details["c_acronym"]) : "";
    }

    $carData = fetchCarPayments($conn, $c_account_no);
    $buyer_name = fetchBuyerDetails($conn, $c_account_no);

    $c_phase = substr($c_account_no, 0, 3);
    $c_block = 'B' . ltrim(substr($c_account_no, 3, 3), '0');
    $c_lot = 'L' . substr($c_account_no, 6, 2);
    $phase_acronym = fetchPhaseDetails($conn, $c_phase) . ' ' . $c_block . ' ' . $c_lot;
    
    $totalCash = 0;
    $totalCheck = 0;

    $html = '
<html>
<head>
    <style>
        ' . $l_css_path . '
    </style>
</head>
<body>
    <header>
        <h2>ASIAN LAND STRATEGIES CORPORATION</h2>
        <h3>CASH ACKNOWLEDGEMENT RECEIPT</h3>

        <div class="info-box">
            <p class="b-info">Account No:</p>
            <p>' . htmlspecialchars($c_account_no) . '</p>
        </div>
        <div class="info-box">
            <p class="b-info">Buyers Name:</p>
            <p>' . $buyer_name . '</p>
        </div>
        <div class="info-box">
            <p class="b-info">Project Site:</p>
            <p>' . $phase_acronym . '</p>
        </div>

    </header>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>No.</th>
                <th>CAR No.</th>
                <th>Payment Type</th>
                <th>Location</th>
                <th>Cash</th>
                <th>Check</th>
                <th>Transaction Date</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>';

    if (!empty($carData)) {
        $counter = 1;
        $totalCash = 0;
        $totalCheck = 0;

        foreach ($carData as $row) {

            $cashAmount = ($row['c_mop'] == 1) ? $row['c_car_amount'] : 0;
            $checkAmount = ($row['c_mop'] == 2) ? $row['c_car_amount'] : 0;

            $totalCash += $cashAmount;
            $totalCheck += $checkAmount;

            $phase_acronym = fetchPhaseDetails($conn, $c_phase) . ' ' . $c_block . ' ' . $c_lot;

            $html .= '
            <tr>
                <td class="pdf-font">' . $counter++ . '</td>
                <td class="pdf-font">' . htmlspecialchars($row['c_car_no']) . '</td>
                <td class="pdf-font">' . htmlspecialchars($row['c_car_type']) . '</td>
                <td class="pdf-font">' . $phase_acronym . '</td>
                <td class="pdf-font">' . number_format($cashAmount, 2) . '</td>
                <td class="pdf-font">' . number_format($checkAmount, 2) . '</td>
                <td class="pdf-font">' . htmlspecialchars((new DateTime($row['c_tran_date']))->format('Y-m-d')) . '</td>
                <td class="pdf-font">' . htmlspecialchars($row['c_car_paydate']) . '</td>
            </tr>';
        }

        $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <td class="pdf-font" colspan="4" style="text-align: right;"></td>
                    <td class="pdf-font">' . number_format($totalCash, 2) . '</td>
                    <td class="pdf-font">' . number_format($totalCheck, 2) . '</td>
                    <td class="pdf-font" colspan="2"></td>
                </tr>
                <tr>
                    <td class="pdf-font" colspan="4" style="text-align: right;">Final Total:</td>
                    <td class="pdf-font" colspan="2">' . number_format($totalCash + $totalCheck, 2) . '</td>
                    <td class="pdf-font" colspan="2"></td>
                </tr>
            </tfoot>';
    } else {
        $html .= '
            <tr>
                <td class="pdf-font" colspan="12" style="text-align: center;">No records found.</td>
            </tr>
        </tbody>';
    }

    $html .= '</p>
        </div>
    </body>
    </html>';

    $dompdf->loadHtml($html);

    $dompdf->setPaper('legal', 'landscape');

    $dompdf->render();

    $dompdf->stream("car_payment_report.pdf", ["Attachment" => 0]);
}
?>
