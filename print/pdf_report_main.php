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
                        a.c_car_paydate, a.c_car_amount, a.c_encoded_by, a.c_tran_date, a.c_tran_updated, a.c_mop, 
                        b.c_name, b.c_phase, b.c_block, b.c_lot
                 FROM t_car_payment a
                 LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                 WHERE a.c_tran_date::text ILIKE ?
                 ORDER BY a.c_tran_date ASC";
    $stmt = odbc_prepare($conn, $car_list);
    $executeParams = ["%$startDate%", $c_encoded_by];
} else {
    $car_list = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                        a.c_car_paydate, a.c_car_amount, a.c_encoded_by, a.c_tran_date, a.c_tran_updated, a.c_mop, 
                        b.c_name, b.c_phase, b.c_block, b.c_lot
                 FROM t_car_payment a
                 LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                 WHERE DATE(a.c_tran_date) BETWEEN ? AND ?
                 ORDER BY a.c_tran_date ASC";
    $stmt = odbc_prepare($conn, $car_list);
    $executeParams = [$startDate, $endDate, $c_encoded_by];
}

$carData = [];
if ($stmt && odbc_execute($stmt, $executeParams)) {
    while ($row = odbc_fetch_array($stmt)) {
        $carData[] = $row;
    }
}

$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);

$html = '
<style>
    ' . $l_css_path . '
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
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>CAR No.</th>
                <th>Buyer Name</th>
                <th>Account No.</th>
                <th>Payment Type</th>
                <th>Location</th>
                <th>Cash</th>
                <th>Check</th>
                <th>Transaction Date</th>
                <th>Payment Date</th>
                <th>Encoded By</th>
            </tr>
        </thead>
        <tbody>';

$totalCash = 0;
$totalCheck = 0;

if (empty($carData)) {
    $html .= '
            <tr>
                <td class="pdf-font" colspan="11" style="text-align: center;">No records found.</td>
            </tr>';
} else {
    $counter = 1;
    foreach ($carData as $row) {
        $cashAmount = $row['c_mop'] == 1 ? $row['c_car_amount'] : 0;
        $checkAmount = $row['c_mop'] == 2 ? $row['c_car_amount'] : 0;
        $totalCash += $cashAmount;
        $totalCheck += $checkAmount;

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
            $c_block = substr($c_account_no, 2, 2);
            $c_lot = substr($c_account_no, 4, 2);

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

        
        /* $html .= '<td class="pdf-font">' . htmlspecialchars($c_acronym) . '</td>';
        $html .= '<td class="pdf-font">B ' . htmlspecialchars($c_block) . '</td>';
        $html .= '<td class="pdf-font">L ' . htmlspecialchars($c_lot) . '</td>';
 */
        $html .= '<td class="pdf-font">' . htmlspecialchars($c_acronym) . " " .htmlspecialchars($c_block) . " " . htmlspecialchars($c_lot) . '</td>';
        
        $html .= '<td class="pdf-font">' . number_format($cashAmount, 2) . '</td>';
        $html .= '<td class="pdf-font">' . number_format($checkAmount, 2) . '</td>';

        $html .= '<td class="pdf-font">' . htmlspecialchars((new DateTime($row['c_tran_date']))->format('Y-m-d')) . '</td>';
        $html .= '<td class="pdf-font">' . htmlspecialchars($row['c_car_paydate']) . '</td>';
        $html .= '<td class="pdf-font">' . htmlspecialchars($c_realname) . '</td>
        </tr>';
    }

    $html .= '
    <tfoot>
        <tr>
            <td class="pdf-font" colspan="6" style="text-align: right;"></td>
            <td class="pdf-font">' . number_format($totalCash, 2) . '</td>
            <td class="pdf-font">' . number_format($totalCheck, 2) . '</td>
            <td class="pdf-font" colspan="3"></td>
        </tr>
        <tr>
            <td class="pdf-font" colspan="6" style="text-align: right;">Total:</td>
            <td class="pdf-font" colspan="2">' . number_format($totalCash + $totalCheck, 2) . '</td>
            <td class="pdf-font" colspan="3"></td>
        </tr>
    </tfoot>';
}

$html .= '
        </tbody>
    </table>';

$html .= '
    <div class="encoded_by">
        <p>Printed By: ';

$c_employee_code = $_SESSION['username'];
$get_encoder_details_qry = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
$encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);

if (odbc_execute($encoder_stmt, array($c_employee_code)) && $encoder = odbc_fetch_array($encoder_stmt)) {
    $html .= htmlspecialchars($encoder["c_realname"]);
} else {
    $html .= "Unknown";
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
header('Content-Disposition: inline; filename="daily_collection_repor_main.pdf"');
echo $dompdf->output();
?>



