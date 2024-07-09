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
                          a.c_car_paydate, a.c_car_amount, a.c_encoded_by, a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank,
                          b.c_name, b.c_phase, b.c_block, b.c_lot
                  FROM t_car_payment a
                  LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                  WHERE a.c_account_no = ? AND status != '1' ORDER BY a.c_tran_date ASC";
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

    function fetchEncoderName($conn, $employeeCode) {
        $query = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
        $stmt = odbc_prepare($conn, $query);
        if (!$stmt || !odbc_execute($stmt, array($employeeCode))) {
            return null;
        }
        $details = odbc_fetch_array($stmt);
        return $details ? htmlspecialchars($details["c_realname"]) : "";
    }

    /* Eto yung sa footer ng page */
    $c_employee_code = $_SESSION['username'];
    $get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
    $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
    $c_realname = "Unknown";

    if (odbc_execute($encoder_stmt, array($c_employee_code)) && $encoder = odbc_fetch_array($encoder_stmt)) {
        $c_realname = htmlspecialchars($encoder["c_realname"]);
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

    <footer>
        <div class="footer-content">
            <p>Printed By: ' . $c_realname . '</p>
        </div>
    </footer>
    
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>No.</th>
                <th>CAR No.</th>
                <th>Payment Type</th>
                <th>Location</th>
                <th>Cash</th>
                <th>Check</th>
                <th>Online</th>
                <th>Bank</th>
                <th>Transaction Date</th>
                <th>Payment Date</th>
                <th>Encoded By</th>
            </tr>
        </thead>
        <tbody>';

    if (!empty($carData)) {
        $counter = 1;
        $totalCash = 0;
        $totalCheck = 0;
        $totalOnline = 0;

        foreach ($carData as $row) {
            $cashAmount = ($row['c_mop'] == 1) ? $row['c_car_amount'] : 0;
            $checkAmount = ($row['c_mop'] == 2) ? $row['c_car_amount'] : 0;
            $checkOnline = ($row['c_mop'] == 3) ? $row['c_car_amount'] : 0;

            $totalCash += $cashAmount;
            $totalCheck += $checkAmount;
            $totalOnline += $checkOnline;

            $phase_acronym = fetchPhaseDetails($conn, $c_phase) . ' ' . $c_block . ' ' . $c_lot;

            $l_encode = fetchEncoderName($conn, $row['c_encoded_by']);

            $html .= '
            <tr>
                <td class="pdf-font">' . $counter++ . '</td>
                <td class="pdf-font">' . htmlspecialchars($row['c_car_no']) . '</td>
                <td class="pdf-font">' . htmlspecialchars($row['c_car_type']) . '</td>
                <td class="pdf-font">' . $phase_acronym . '</td>
                <td class="pdf-font">' . number_format($cashAmount, 2) . '</td>
                <td class="pdf-font">' . number_format($checkAmount, 2) . '</td>
                <td class="pdf-font">' . number_format($checkOnline, 2) . '</td>
                <td class="pdf-font">' . htmlspecialchars($row['c_bank'] == '' ? '-' : $row['c_bank']) . '</td>

                <td class="pdf-font">' . htmlspecialchars((new DateTime($row['c_tran_date']))->format('Y-m-d')) . '</td>
                <td class="pdf-font">' . htmlspecialchars($row['c_car_paydate']) . '</td>
                <td class="pdf-font">' . $l_encode . '</td>
            </tr>';
        }

        $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <td class="pdf-font" colspan="4" style="text-align: right;"></td>
                    <td class="pdf-font">' . number_format($totalCash, 2) . '</td>
                    <td class="pdf-font">' . number_format($totalCheck, 2) . '</td>
                    <td class="pdf-font">' . number_format($totalOnline, 2) . '</td>
                    <td class="pdf-font" colspan="4"></td>
                </tr>
                <tr>
                    <td class="pdf-font" colspan="4" style="text-align: right;">Final Total:</td>
                    <td class="pdf-font" colspan="3">' . number_format($totalCash + $totalCheck + $totalOnline, 2) . '</td>
                    <td class="pdf-font" colspan="4"></td>
                </tr>
            </tfoot>';

        $html .= '
                </tbody>
            </table>';

    } else {
        $html .= '
            <tr>
                <td class="pdf-font" colspan="9" style="text-align: center;">No records found.</td>
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

    $dompdf->stream("car_buyers_payment.pdf", ["Attachment" => 0]);
}
?>



