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
                        b.c_name, b.c_phase, b.c_block, b.c_lot, c.c_position
                 FROM t_or_payment a
                 LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no
                 LEFT JOIN t_car_users c ON a.c_encoded_by = c.c_employee_code /* c_position (Cashier 2) */
                 WHERE a.c_tran_date::text ILIKE ?
                 /* AND c.c_position != 'Cashier 2' */ /* hide muna daw kasi manual collect nalang ni ms arlene */
                 ORDER BY a.c_or_no ASC";
    $stmt = odbc_prepare($conn, $or_list);
    $executeParams = ["%$startDate%", $c_encoded_by];
} else {
    $or_list = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                        a.c_or_paydate, a.c_or_amount, a.c_encoded_by, a.status, a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank,
                        b.c_name, b.c_phase, b.c_block, b.c_lot, c.c_position
                 FROM t_or_payment a
                 LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no
                 LEFT JOIN t_car_users c ON a.c_encoded_by = c.c_employee_code /* c_position (Cashier 2) */
                 WHERE DATE(a.c_tran_date) BETWEEN ? AND ?
                 /* AND c.c_position != 'Cashier 2' */ /* hide muna daw kasi manual collect nalang ni ms arlene */
                 ORDER BY a.c_or_no ASC"; 
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


/* Online Banks */
$l_bank_online = "SELECT c_bank, SUM(c_or_amount) AS total_amount FROM t_or_payment 
                        LEFT JOIN t_other_or_payment ON t_or_payment.c_or_no = t_other_or_payment.c_or_no
                        WHERE DATE(c_tran_date) BETWEEN ? AND ? and c_bank != '' AND status != '1' AND c_mop = '3' GROUP BY c_bank 
                        HAVING SUM(c_or_amount) > 0
                        ORDER BY c_bank; ";

$bank_stmt = odbc_prepare($conn, $l_bank_online);
$bank_executeParams = [$startDate, $endDate];
$l_bank_list = [];
if ($bank_stmt && odbc_execute($bank_stmt, $bank_executeParams)) {
    while ($bank_row = odbc_fetch_array($bank_stmt)) {
        $l_bank_list[$bank_row['c_bank']] = $bank_row['total_amount'];
    }
}

/* Check Banks */
$l_bank_check = "SELECT c_bank, SUM(c_or_amount) AS total_amount FROM t_or_payment 
                        LEFT JOIN t_other_or_payment ON t_or_payment.c_or_no = t_other_or_payment.c_or_no
                        WHERE DATE(c_tran_date) BETWEEN ? AND ? and c_bank != '' AND status != '1' AND c_mop = '2' GROUP BY c_bank 
                        HAVING SUM(c_or_amount) > 0
                        ORDER BY c_bank; ";

$bank_stmt = odbc_prepare($conn, $l_bank_check);
$bank_executeParams = [$startDate, $endDate];
$l_check_list = [];
if ($bank_stmt && odbc_execute($bank_stmt, $bank_executeParams)) {
    while ($bank_row = odbc_fetch_array($bank_stmt)) {
        $l_check_list[$bank_row['c_bank']] = $bank_row['total_amount'];
    }
}

$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);

$html .= '
<style>
    ' . $l_css_path . '

    /* Pang Break ng pages huhubels shaket!!!! */
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
                <th>Encoded By</th>
            </tr>
        </thead>
        <tbody>';

$totalCash = 0;
$totalCheck = 0;
$totalOnline = 0;

if (empty($orData)) {
    $html .= '
            <tr>
                <td class="pdf-font" colspan="13" style="text-align: center;">No records found.</td>
            </tr>';
} else {
    $counter = 1;
    foreach ($orData as $row) {
        $cashAmount = ($row['c_mop'] == 1 && $row['status'] != 1) ? $row['c_or_amount'] : 0;
        $checkAmount = ($row['c_mop'] == 2 && $row['status'] != 1) ? $row['c_or_amount'] : 0;
        $onlineAmount = ($row['c_mop'] == 3 && $row['status'] != 1) ? $row['c_or_amount'] : 0;
        $totalCashOnline += $cashAmount + $onlineAmount;
        $totalCheck += $checkAmount;
        $totalCash += $cashAmount;
        $totalOnline += $onlineAmount;
        $totalOfall += $cashAmount + $onlineAmount + $checkAmount;
        $totalCashCheck += $cashAmount + $checkAmount;

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
                    $c_acronym = "";
                }
            } else {
                $c_acronym = "";
            }
        } else {
            $c_phase = '-----';
            $c_block = '-----';
            $c_lot = '-----';
            $c_acronym = "-----";
        }

        $c_encoded_by = $row['c_encoded_by'];
        $c_realname = "";

        if (!empty($c_encoded_by)) {
            $get_user_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
            $user_stmt = odbc_prepare($conn, $get_user_qry);
            if (odbc_execute($user_stmt, array($c_encoded_by))) {
                $user_details = odbc_fetch_array($user_stmt);
                if ($user_details) {
                    $c_realname = $user_details['c_realname'];
                }
            }
        }

        $html .= '<td class="pdf-font">' . htmlspecialchars($c_acronym) . " " .htmlspecialchars($c_block) . " " . htmlspecialchars($c_lot) . '</td>';
        /* $html .= '<td class="pdf-font">' . number_format($cashAmount, 2) . '</td>'; */
        $html .= '<td class="pdf-font">' . number_format($cashAmount + $onlineAmount, 2) . '</td>'; // SUM NG CASH AT ONLINE (pinabago ni boss jude)
        $html .= '<td class="pdf-font">' . number_format($checkAmount, 2) . '</td>';
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_bank'] == '' ? '-' : $row['c_bank']) . '</td>';
        $html .= '<td class="pdf-font">' . number_format($cashAmount + $onlineAmount + $checkAmount, 2) . '</td>';

        /* $html .= '<td class="pdf-font">' . htmlspecialchars((new DateTime($row['c_tran_date']))->format('Y-m-d')) . '</td>'; */
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['status'] == 0 ? '-----' : ($row['status'] == 1 ? 'CANCELLED' : $row['status'])) . '</td>';
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_or_paydate']) . '</td>';
        $html .= '<td class="pdf-font">' . htmlspecialchars($c_realname) . '</td>
        </tr>';
    }

    $html .= '
        <tfoot>
            <tr>
                <td class="pdf-font" colspan="6" style="text-align: right;"></td>
                <td class="pdf-font">' . number_format($totalCashOnline, 2) . '</td>
                <td class="pdf-font">' . number_format($totalCheck, 2) . '</td>
                <td class="pdf-font" colspan="1"></td>
                <td class="pdf-font" >' . number_format($totalOfall, 2) . '</td>
                <td class="pdf-font" colspan="3"></td>
            </tr>
        </tfoot>';

    $html .= '
        </tbody>
    </table>';

    $html .= '
        <div class="page-break"></div>';

    /* Online Banks */
    if (!empty($l_bank_list)) {
        $html .= '
        <div>
            <h4>Total amount of banks (Online)</h4>
            <table>
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>';
    
        $total_amount = 0;
    
        foreach ($l_bank_list as $bank => $bank_total) {
            $html .= '
            <tr>
                <td class="pdf-font" style="width: 25%;">' . htmlspecialchars($bank) . '</td>
                <td class="pdf-font" style="width: 25%;">' . number_format($bank_total, 2) . '</td>
            </tr>';
    
            $total_amount += $bank_total;
        }
    
        $html .= '
            <tr>
                <td class="pdf-font" style="width: 25%;"><strong>TOTAL ONLINE:</strong></td>
                <td class="pdf-font" style="width: 25%;"><strong>' . number_format($total_amount, 2) . '</strong></td>
            </tr>';
    
        $html .= '
                </tbody>
            </table>
        </div>';
    } else {
        $html .= '
        <div>
            <h4>Total amount of banks (Online)</h4>
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

    /* Check Banks */
    if (!empty($l_check_list)) {
        $html .= '
        <div style="margin-top: 20px;">
            <h4>Total amount of banks (Check)</h4>
            <table>
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>';
    
        $total_amount = 0;
    
        foreach ($l_check_list as $bank => $bank_total) {
            $html .= '
            <tr>
                <td class="pdf-font" style="width: 25%;">' . htmlspecialchars($bank) . '</td>
                <td class="pdf-font" style="width: 25%;">' . number_format($bank_total, 2) . '</td>
            </tr>';
    
            $total_amount += $bank_total;
        }
    
        $html .= '
            <tr>
                <td class="pdf-font" style="width: 25%;"><strong>TOTAL CHECK:</strong></td>
                <td class="pdf-font" style="width: 25%;"><strong>' . number_format($total_amount, 2) . '</strong></td>
            </tr>';
    
        $html .= '
                </tbody>
            </table>
        </div>';
    } else {
        $html .= '
        <div style="margin-top: 20px;">
            <h4>Total amount of banks (Check)</h4>
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

    /* ETO YUNG SA TOTAL NG CASH/ONLIN/CHECK */
    $html .= '
    <div style="margin-top: 40px;">
        <table>
            <thead>
                <tr>
                    <th>Cash</th>
                    <th>Check</th>
                    <th>Online</th>
                    <th>Total of Payment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCash, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCheck, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . "-" . number_format($totalOnline, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCashCheck, 2) . '<strong>' . '</td>
                </tr>
            </tbody>
        </table>
    </div>';

$html .= '</p>
    </div>
</body>
</html>';
}

/* $options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options); */
$dompdf->loadHtml($html);

$dompdf->setPaper('legal', 'landscape');

$dompdf->render();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="daily_collection_report_OR.pdf"');
echo $dompdf->output();
?>



