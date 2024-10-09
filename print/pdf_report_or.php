<?php
session_start();
require '../dompdf/vendor/autoload.php';
include('../config.php');

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

use Dompdf\Dompdf;
use Dompdf\Options;

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$c_encoded_by = $_SESSION['username'];

$l_css = '../dist/css/pdf.css';
$l_css_path = file_get_contents($l_css);

if ($startDate == $endDate) {
    $or_list = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                        a.c_or_paydate, a.c_or_amount, a.c_encoded_by, a.status, a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank,
                        b.c_name, b.c_phase, b.c_block, b.c_lot
                 FROM t_or_payment a
                 LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no
                 WHERE a.c_tran_date::text ILIKE ? AND a.c_encoded_by = ? 
                 ORDER BY a.c_or_no ASC"; /* inilipat yung order by sa c_or_no (cashier) */
    $stmt = odbc_prepare($conn, $or_list);
    $executeParams = ["%$startDate%", $c_encoded_by];
} else {
    $or_list = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                        a.c_or_paydate, a.c_or_amount, a.c_encoded_by, a.status, a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank,
                        b.c_name, b.c_phase, b.c_block, b.c_lot
                 FROM t_or_payment a
                 LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no
                 WHERE DATE(a.c_tran_date) BETWEEN ? AND ? AND a.c_encoded_by = ?
                 ORDER BY a.c_or_no ASC"; /* inilipat yung order by sa c_or_no (cashier) */
    $stmt = odbc_prepare($conn, $or_list);
    $executeParams = [$startDate, $endDate, $c_encoded_by];
}

$orData = [];
if ($stmt && odbc_execute($stmt, $executeParams)) {
    while ($row = odbc_fetch_array($stmt)) {
        $orData[] = $row;
    }
}

/* Eto yung sa footer ng page */
$c_employee_code = $_SESSION['username'];
$get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
$encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
$c_realname = "Unknown";

if (odbc_execute($encoder_stmt, array($c_employee_code)) && $encoder = odbc_fetch_array($encoder_stmt)) {
    $c_realname = htmlspecialchars($encoder["c_realname"]);
}


/* Para sa banks shutaenabells kayong lahat! */
$l_bank_query = "SELECT c_bank, SUM(c_or_amount) AS total_amount FROM t_or_payment 
                        LEFT JOIN t_other_or_payment ON t_or_payment.c_or_no = t_other_or_payment.c_or_no
                        WHERE DATE(c_tran_date) BETWEEN ? AND ? AND c_bank != '' AND status != '1' AND c_encoded_by = ? 
                        GROUP BY c_bank 
                        HAVING SUM(c_or_amount) > 0
                        ORDER BY c_bank;";

$bank_stmt = odbc_prepare($conn, $l_bank_query);
$bank_executeParams = [$startDate, $endDate, $c_encoded_by];
$l_bank_list = [];
if ($bank_stmt && odbc_execute($bank_stmt, $bank_executeParams)) {
    while ($bank_row = odbc_fetch_array($bank_stmt)) {
        $l_bank_list[$bank_row['c_bank']] = $bank_row['total_amount'];
    }
}


$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);

$html = '
<style>
    ' . $l_css_path . '

    .page-break { 
        page-break-before: always; 
    }
</style>

<body>
    <header>
        <h2>ASIAN LAND STRATEGIES CORPORATION</h2>
        <h5>DAILY COLLECTION & DEPOSIT REPORT</h5>';

        $l_start = date('F j, Y', strtotime($startDate));
        $l_end = date('F j, Y', strtotime($endDate));

        if ($startDate == $endDate) {
            $html .= '<p>' . htmlspecialchars($l_start) . '</p>';
        } else {
            $html .= '<p>From ' . htmlspecialchars($l_start) . ' to ' . htmlspecialchars($l_end) . '</p>';
        }

$html .= '
    </header>

    <footer>
        <div class="footer-content">
            <p>Report By: ' . $c_realname . '</p>
        </div>
    </footer>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>OR No.</th>
                <th>Buyer Name</th>
                <th>Account No.</th>
                <th>Transaction Type</th>
                <th>Location</th>
                <th>Cash/Online</th>
                <th>Check</th>
                <th>Bank</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>';

$l_cashonline = 0;
$l_check = 0;
$l_total = 0;

if (empty($orData)) {
    $html .= '
            <tr>
                <td class="pdf-font" colspan="12" style="text-align: center;">No records found.</td>
            </tr>';
} else {
    $counter = 1;
    foreach ($orData as $row) {
        $cashAmount = ($row['c_mop'] == 1 && $row['status'] != 1) ? $row['c_or_amount'] : 0;
        $checkAmount = ($row['c_mop'] == 2 && $row['status'] != 1) ? $row['c_or_amount'] : 0;
        $onlineAmount = ($row['c_mop'] == 3 && $row['status'] != 1) ? $row['c_or_amount'] : 0;
        $l_cashonline += $cashAmount + $onlineAmount;
        $l_check += $checkAmount;
        $l_total += $cashAmount + $onlineAmount + $checkAmount;

        $html .= '
        <tr>
            <td class="pdf-font">' . $counter++ . '</td>
            <td class="pdf-font">' . htmlspecialchars($row['c_or_no']) . '</td>
            <td class="pdf-font">';

        $c_buyer_acc = !empty($row['c_account_no']) ? $row['c_account_no'] : '';
        if (!empty($c_buyer_acc)) {
            $get_buyer_details_qry = "SELECT c_b1_last_name, c_b1_first_name FROM t_buyers_account WHERE c_account_no = ?";
            $buyer_stmt = odbc_prepare($conn, $get_buyer_details_qry);
            if (odbc_execute($buyer_stmt, array($c_buyer_acc))) {
                $buyer_details = odbc_fetch_array($buyer_stmt);
                if ($buyer_details) {
                    $html .= htmlspecialchars($buyer_details["c_b1_first_name"] . ' ' . $buyer_details["c_b1_last_name"]);
                } else {
                    $html .= "Unknown";
                }
            } else {
                $html .= "Unknown";
            }
        } else {
            $html .= htmlspecialchars($row['c_name']);
        }

        $html .= '</td>';

        $html .= '<td class="pdf-font">' . htmlspecialchars(!empty($row['c_account_no']) ? $row['c_account_no'] : '-----------') . '</td>';

        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_or_type']) . '</td>';

        $c_account_no = $row['c_account_no'];
        if (!empty($c_account_no)) {
            $c_phase = substr($c_account_no, 0, 3);
            $c_block = substr($c_account_no, 3, 3);
            $c_lot = substr($c_account_no, 6, 2);

            $get_acronym_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
            $project_stmt = odbc_prepare($conn, $get_acronym_qry);
            if (odbc_execute($project_stmt, array($c_phase))) {
                $project_details = odbc_fetch_array($project_stmt);
                if ($project_details) {
                    $c_acronym = $project_details["c_acronym"];
                } else {
                    $c_acronym = "-----";
                }
            } else {
                $c_acronym = "-----";
            }
        } else {
            $c_phase = '-----';
            $c_block = '-----';
            $c_lot = '-----';
            $c_acronym = "-----";
        }

        $html .= '<td class="pdf-font">' . htmlspecialchars($c_acronym) . " " .htmlspecialchars($c_block) . " " . htmlspecialchars($c_lot) . '</td>';
        /* $html .= '<td class="pdf-font">' . number_format($cashAmount, 2) . '</td>'; */
        $html .= '<td class="pdf-font">' . number_format($cashAmount + $onlineAmount, 2) . '</td>'; // SUM NG CASH AT ONLINE (pinabago ni boss jude)
        $html .= '<td class="pdf-font">' . number_format($checkAmount, 2) . '</td>';
        /* $html .= '<td class="pdf-font">' . number_format($onlineAmount, 2) . '</td>'; */
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_bank'] == '' ? '-' : $row['c_bank']) . '</td>';
        $html .= '<td class="pdf-font">' . number_format($cashAmount + $onlineAmount + $checkAmount, 2) . '</td>';

        /* $html .= '<td class="pdf-font">' . htmlspecialchars((new DateTime($row['c_tran_date']))->format('Y-m-d')) . '</td>'; */
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['status'] == 0 ? '-----' : ($row['status'] == 1 ? 'CANCELLED' : $row['status'])) . '</td>';
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_or_paydate']) . '</td>
        </tr>';
    }

    $html .= '
    <tfoot>
        <tr>
            <td class="pdf-font" colspan="6" style="text-align: right;"></td>
            <td class="pdf-font">' . number_format($l_cashonline, 2) . '</td>
            <td class="pdf-font">' . number_format($l_check, 2) . '</td>
            <td class="pdf-font" colspan="1"></td>
            <td class="pdf-font">' . number_format($l_total, 2) . '</td>
            <td class="pdf-font" colspan="2"></td>
        </tr>
    </tfoot>';
}

$html .= '
        </tbody>
    </table>';

    $html .= '
        <div class="page-break"></div>';

    // BANKS LIST PUTTTTAAAAAAAA!!! SEPARATE PA SA TABLE NI REPORT
    if (!empty($l_bank_list)) {
        $html .= '
        <div style="margin-top: 20px;">
            <h4>Total amount of banks</h4>
            <table>
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>';
    
        foreach ($l_bank_list as $bank => $bank_total) {
            $html .= '
            <tr>
                <td class="pdf-font">' . htmlspecialchars($bank) . '</td>
                <td class="pdf-font">' . number_format($bank_total, 2) . '</td>
            </tr>';
        }
    
        $html .= '
                </tbody>
            </table>
        </div>';
    } else {
        $html .= '
        <div style="margin-top: 20px;">
            <h4>Total amount of banks</h4>
            <table>
                <thead>
                    <tr>
                        <th class="pdf-font" colspan="2">Bank/Total amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="pdf-font" colspan="2" style="text-align: center;">No records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>';
    }

$html .= '</p>
    </div>
</body>
</html>';

$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

$dompdf->setPaper('legal', 'landscape');

$dompdf->render();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="daily_collection_report.pdf"');
echo $dompdf->output();
?>



