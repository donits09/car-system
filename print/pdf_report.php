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
    $car_list = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                        a.c_car_paydate, a.c_car_amount, a.c_encoded_by, a.status, a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank,
                        b.c_name, b.c_phase, b.c_block, b.c_lot
                 FROM t_car_payment a
                 LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                 WHERE a.c_tran_date::text ILIKE ? AND a.c_encoded_by = ? 
                 ORDER BY a.c_car_no ASC"; /* inilipat yung order by sa c_or_no (cashier) */
    $stmt = odbc_prepare($conn, $car_list);
    $executeParams = ["%$startDate%", $c_encoded_by];
} else {
    $car_list = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                        a.c_car_paydate, a.c_car_amount, a.c_encoded_by, a.status, a.c_tran_date, a.c_tran_updated, a.c_mop, a.c_bank,
                        b.c_name, b.c_phase, b.c_block, b.c_lot
                 FROM t_car_payment a
                 LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                 WHERE DATE(a.c_tran_date) BETWEEN ? AND ? AND a.c_encoded_by = ?
                 ORDER BY a.c_car_no ASC"; /* inilipat yung order by sa c_or_no (cashier) */
    $stmt = odbc_prepare($conn, $car_list);
    $executeParams = [$startDate, $endDate, $c_encoded_by];
}

$carData = [];
if ($stmt && odbc_execute($stmt, $executeParams)) {
    while ($row = odbc_fetch_array($stmt)) {
        $carData[] = $row;
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
$l_online_query = "SELECT c_bank, SUM(c_car_amount) AS total_amount FROM t_car_payment 
                        LEFT JOIN t_other_car_payment ON t_car_payment.c_car_no = t_other_car_payment.c_car_no
                        WHERE DATE(c_tran_date) BETWEEN ? AND ? AND c_bank != '' AND status != '1' AND c_mop = '3' AND c_bank != 'CDV' AND c_encoded_by = ? 
                        GROUP BY c_bank 
                        HAVING SUM(c_car_amount) > 0
                        ORDER BY c_bank;";

$bank_stmt = odbc_prepare($conn, $l_online_query);
$bank_executeParams = [$startDate, $endDate, $c_encoded_by];
$l_online_list = [];
if ($bank_stmt && odbc_execute($bank_stmt, $bank_executeParams)) {
    while ($bank_row = odbc_fetch_array($bank_stmt)) {
        $l_online_list[$bank_row['c_bank']] = $bank_row['total_amount'];
    }
}

/* CSV (check voucher) */
$l_check_voucher = "SELECT c_bank, SUM(c_car_amount) AS total_amount FROM t_car_payment 
                        LEFT JOIN t_other_car_payment ON t_car_payment.c_car_no = t_other_car_payment.c_car_no
                        WHERE DATE(c_tran_date) BETWEEN ? AND ? AND c_bank != '' AND status != '1' AND c_mop = '4' AND c_bank = 'CDV' AND c_encoded_by = ? 
                        GROUP BY c_bank 
                        HAVING SUM(c_car_amount) > 0
                        ORDER BY c_bank;";

$bank_stmt = odbc_prepare($conn, $l_check_voucher);
$bank_executeParams = [$startDate, $endDate, $c_encoded_by];
$l_check_voucher_list = [];
if ($bank_stmt && odbc_execute($bank_stmt, $bank_executeParams)) {
    while ($bank_row = odbc_fetch_array($bank_stmt)) {
        $l_check_voucher_list[$bank_row['c_bank']] = $bank_row['total_amount'];
    }
}


/* Check Banks */
$l_check_query = "SELECT c_bank, SUM(c_car_amount) AS total_amount FROM t_car_payment 
                        LEFT JOIN t_other_car_payment ON t_car_payment.c_car_no = t_other_car_payment.c_car_no
                        WHERE DATE(c_tran_date) BETWEEN ? AND ? AND c_bank != '' AND status != '1' AND c_encoded_by = ? AND c_mop = '2' AND c_bank != 'On Hand'
                        GROUP BY c_bank 
                        HAVING SUM(c_car_amount) > 0 
                        ORDER BY c_bank;";

$bank_stmt = odbc_prepare($conn, $l_check_query);
$bank_executeParams = [$startDate, $endDate, $c_encoded_by];
$l_check_list = [];
if ($bank_stmt && odbc_execute($bank_stmt, $bank_executeParams)) {
    while ($bank_row = odbc_fetch_array($bank_stmt)) {
        $l_check_list[$bank_row['c_bank']] = $bank_row['total_amount'];
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
        <h3>CASH ACKNOWLEDGEMENT RECEIPT</h3>
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
                <th>CAR No.</th>
                <th>Buyer Name</th>
                <th>Account No.</th>
                <th>Transaction Type</th>
                <th>Location</th>
                <th>Cash/Online/Voucher</th>
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

if (empty($carData)) {
    $html .= '
            <tr>
                <td class="pdf-font" colspan="12" style="text-align: center;">No records found.</td>
            </tr>';
} else {
    $counter = 1;
    foreach ($carData as $row) {
        $cashAmount = ($row['c_mop'] == 1 && $row['status'] != 1) ? $row['c_car_amount'] : 0;
        $checkAmount = ($row['c_mop'] == 2 && $row['status'] != 1) ? $row['c_car_amount'] : 0;
        $onlineAmount = ($row['c_mop'] == 3 && $row['status'] != 1) ? $row['c_car_amount'] : 0;
        $voucherAmount = ($row['c_mop'] == 4 && $row['status'] != 1) ? $row['c_car_amount'] : 0;
        $l_cashonline += $cashAmount + $onlineAmount + $voucherAmount;
        $l_check += $checkAmount;
        $l_total += $cashAmount + $onlineAmount + $checkAmount + $voucherAmount;
        $totalCash += $cashAmount;
        $totalOnline += $onlineAmount;
        $totalCheck += $checkAmount;

        /* Checked On Hand */
        $checked = ($row['c_mop'] == 2 && $row['status'] != 1 && $row['c_bank'] != 'On Hand') ? $row['c_car_amount'] : 0;
        $t_checked += $checked;

        $checkOnhand = ($row['c_mop'] == 2 && $row['status'] != 1 && $row['c_bank'] == 'On Hand') ? $row['c_car_amount'] : 0;
        $t_check_on_hand += $checkOnhand;

        $totalCashCheck += $cashAmount + $checkOnhand;
        $totalCashOnly += $cashAmount + $checkOnhand;

        $html .= '
        <tr>
            <td class="pdf-font">' . $counter++ . '</td>
            <td class="pdf-font">' . htmlspecialchars($row['c_car_no']) . '</td>
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

        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_car_type']) . '</td>';

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
        $html .= '<td class="pdf-font">' . number_format($cashAmount + $onlineAmount + $voucherAmount, 2) . '</td>'; // SUM NG CASH AT ONLINE (pinabago ni boss jude)
        $html .= '<td class="pdf-font">' . number_format($checkAmount, 2) . '</td>';
        /* $html .= '<td class="pdf-font">' . number_format($onlineAmount, 2) . '</td>'; */
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_bank'] == '' ? '-' : $row['c_bank']) . '</td>';
        $html .= '<td class="pdf-font">' . number_format($cashAmount + $onlineAmount + $checkAmount + $voucherAmount, 2) . '</td>';

        /* $html .= '<td class="pdf-font">' . htmlspecialchars((new DateTime($row['c_tran_date']))->format('Y-m-d')) . '</td>'; */
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['status'] == 0 ? '-----' : ($row['status'] == 1 ? 'CANCELLED' : $row['status'])) . '</td>';
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_car_paydate']) . '</td>
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

    /* Online Bank */
    if (!empty($l_online_list)) {
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
    
        foreach ($l_online_list as $bank => $bank_total) {
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

    /* Check banks */
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

    /* Check Voucher */
    if (!empty($l_check_voucher_list)) {
        $html .= '
        <div>
            <h4>Check Voucher (CDV)</h4>
            <table>
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>';
    
        foreach ($l_check_voucher_list as $bank => $bank_total) {
            $html .= '
            <tr>
                <td class="pdf-font" style="width: 25%;">' . htmlspecialchars($bank) . '</td>
                <td class="pdf-font" style="width: 25%;"><strong>' . number_format($bank_total, 2) . '</strong></td>
            </tr>';
        
        }
    
        $html .= '
                </tbody>
            </table>
        </div>';
    } else {
        $html .= '
        <div>
            <h4>Check Voucher (CDV)</h4>
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

    /* CASH */
    /* $html .= '
    <div>
        <h4>Total of (Cash)</h4>
        <table>
            <thead>
                <tr>
                    <th>Cash On Hand</th>
                    <th>Check On Hand</th>
                    <th>Total On Hand</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCash, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($t_check_on_hand, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCashOnly, 2) . '<strong>' . '</td>
                </tr>
            </tbody>
        </table>
    </div>'; */

    /* CASH AND CHECK */
    /* $html .= '
    <div>
        <h4>Total of (Cash and Check)</h4>
        <table>
            <thead>
                <tr>
                    <th>Cash On Hand</th>
                    <th>Check Deposited</th>
                    <th>Check On Hand   
                    <th>Total On Hand</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCash, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($t_checked, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($t_check_on_hand, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCashCheck, 2) . '<strong>' . '</td>
                </tr>
            </tbody>
        </table>
    </div>'; */

    /* CASH AND CHECK ON HAND TOTAL BASE ON SIR JUDE */
    $html .= '
    <div>
        <h4>Total of (Cash and Check)</h4>
        <table>
            <thead>
                <tr>
                    <th>Cash On Hand</th>
                    <th>Check On Hand   
                    <th>Total On Hand</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCash, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($t_check_on_hand, 2) . '<strong>' . '</td>
                    <td class="pdf-font" style="width: 25%; height: 30px;">' . '<strong>' . number_format($totalCashCheck, 2) . '<strong>' . '</td>
                </tr>
            </tbody>
        </table>
    </div>';

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



