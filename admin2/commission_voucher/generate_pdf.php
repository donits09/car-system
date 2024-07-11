<?php
require '../../dompdf/autoload.inc.php';
require_once('../../config.php');
include('../../inc/common.php');
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $account_no = $_POST['account_no'];
    $sql = "SELECT * FROM t_buyers_account where c_account_no = '%s' order by c_account_no ";   
    $query = sprintf($sql, $account_no);
    $l_qry = odbc_exec($conn, $query);
    $l_rst = odbc_fetch_array($l_qry);
    if ($l_rst === false) {
        echo "Error: No account found with the provided account number.";
    } else {
        $sql2 = "SELECT * FROM t_payment WHERE c_account_no = '%s' ORDER BY c_payment_count, c_pay_date, c_balance DESC";
        $query2 = sprintf($sql2, $account_no);
        $l_qry2 = odbc_exec($conn, $query2);
        
        $first_row = null;
        $last_row = null;
        $reservation = 0;
        
        while ($row = odbc_fetch_array($l_qry2)) {
            if ($first_row === null) {
                // Store the first row
                $first_row = $row;
            }
            
            if ($row['c_status'] == 'ERN' || $row['c_status'] == 'RES') {
                $reservation += $row['c_principal']; // Accumulate reservation amount
            }
            
            // Update the last row on each iteration
            $last_row = $row;
        }
        
        // Now $first_row contains the first row, and $last_row contains the last row fetched
        
        if ($first_row) {
            $first_row_date = $first_row['c_pay_date'];
            $first_row_or_no = $first_row['c_or_no'];
            $first_row_principal = $first_row['c_principal'];
            $first_row_balance = $first_row['c_balance'];
            $first_row_amount_paid = $first_row['c_amount_paid'];
            $first_row_status = $first_row['c_status'];
        }
        
        if ($last_row) {
            $last_row_date = $last_row['c_pay_date'];
            $last_row_or_no = $last_row['c_or_no'];
            $last_row_principal = $last_row['c_principal'];
            $last_row_balance = $last_row['c_balance'];
            $last_row_amount_paid = $last_row['c_amount_paid'];
            $last_row_status = $last_row['c_status'];
        }
    
        $l_lot_area = $l_rst['c_lot_area'];
        $l_price_sqm = $l_rst['c_price_sqm'];
        $lot_disc = $l_rst['c_lot_discount'];
        $house_disc = $l_rst['c_h_discount'];
        $tcp_disc = $l_rst['c_tcp_discount'];
        $lot_disc_amt = $l_rst['c_lot_discount_amount'];
        $hse_disc_amt = $l_rst['c_h_discount_amount'];
        $tcp_disc_amt = $l_rst['c_tcp_discount_amount'];
        $lcp = $l_lot_area * $l_price_sqm;
        $l_total_lot_discount = (($lcp) *($lot_disc/100));
        $l_floor_area = $l_rst['c_floor_area'];
        $l_h_price_sqm = $l_rst['c_house_price_sqm'];
        $hcp = $l_floor_area * $l_h_price_sqm;
        $l_down_percent = mtof($l_rst['c_down_percent']);
        $l_net_dp = mtof($l_rst['c_net_dp']);
        
        if ($tcp_disc_amt == '' || $tcp_disc_amt == 0 || $tcp_disc_amt == '0' || $tcp_disc_amt == NULL) {
            $total_discount = $lcp  * ($lot_disc/100);
        } else {
            $total_discount = $lot_disc_amt + $hse_disc_amt + $tcp_disc_amt;
        }
    }

    $code = $_POST['code'];
    $rate = $_POST['rate'];
    $l_rate = $_POST['rate'];
    $l_agent = $_POST['agent_name'];
    $l_pos = $_POST['pos'];
    $l_name = $_POST['buyer1'];
    $l_location = substr($account_no, 0, 3) . ' ' . substr($account_no, 3, 3) . ' ' . substr($account_no, 6, 2);
    $reservation = mtof($_POST['reservation']);
    $l_net_tcp = mtof($_POST['net_tcp']);
    $l_commission = mtof($_POST['amount']);
    $l_comm_rate = $_POST['due_commission'];
    $l_withholding_tax = $_POST['whtax'];
    $l_net_commission = ($l_commission * ($l_comm_rate / 100) - (($l_commission * ($l_comm_rate / 100)) * mtof($l_withholding_tax) / 100));
    $l_ca = mtof($_POST['cash_advance']);
    $l_oth = mtof($_POST['others']);
    $l_total_deductions = $l_ca + $l_oth;
    $l_net_due_commission = $l_net_commission - $l_total_deductions;
    $l_date = "2023-05-30";
}

// Create the HTML content for the PDF
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Commission Voucher</title>
    <style>
        body {
            font-family: Times New Roman, sans-serif;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .column {
            flex: 1;
            margin: 5px;
        }
        .form-group {
            margin-bottom: 2px;
            font-size: 12px;
        }
        .bold {
            font-weight: bold;
        }
        .center {
            text-align: center;
        }
        .left-justified {
            text-align: left;
        }
        .details-header {
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }
        table {
            width: 90%;
            border-collapse: collapse;
            margin: auto;
        }
        th, td {
            border: .5px solid black;
            padding: 2px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='form-group'>
            <span>COMMISSION VOUCHER</span>
            <span style='float: right;'>Comm. Rate : $l_rate%</span>
        </div>
        <div class='form-group'>
            <span>Broker/Agent: <span class='bold'>$l_agent</span></span>
            <span style='float: right;'>Designation: <span class='bold'>$l_pos</span></span>
        </div>
        <div class='form-group'>
            <span>Buyer's Name: $l_name</span>
        </div>
        <div class='form-group'>
            <span>Property: $l_location</span>
        </div>
        <div class='form-group'>
            <span>FD Rate: " . ftom($l_down_percent) . "% ( P " . ftom(mtof($l_net_dp) + mtof($reservation)) . ")</span>
        </div>
        <div class='details-header'>
            ***** Details of Down Payment *****
        </div>
        <div class='container'>
            <table>
                <thead>
                    <tr>
                        <th>O.R. Date</th>
                        <th>O.R. No.</th>
                        <th>Amount paid</th>
                        <th>Principal Balance</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>$first_row_date</td>
                        <td>$first_row_or_no</td>
                        <td>" . ftom($first_row_amount_paid) . "</td>
                        <td>" . ftom($first_row_balance) . "</td>
                        <td>$first_row_status</td>
                    </tr>
                    <tr>
                        <td>$last_row_date</td>
                        <td>$last_row_or_no</td>
                        <td>" . ftom($first_row_balance - $last_row_balance) . "</td>
                        <td>" . ftom($last_row_balance) . "</td>
                        <td>$last_row_status</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class='details-header'>
            ***** Computation of Commission *****
        </div>
         <table>
        <tr>
            <td>
             <div class='form-group left-justified'>
                LCP: " . ftom($l_lot_area) . " sqm x " . ftom($l_price_sqm) . " = " . ftom($lcp) . "<br>
                HCP: " . ftom($l_floor_area) . " sqm x " . ftom($l_h_price_sqm) . " = " . ftom($hcp) . "<br>
                Less: Discount L " . ftom($lot_disc) . "% H " . ftom($house_disc) . "% T " . ftom($tcp_disc) . "% <br>
                Commission Rate: " . $l_rate . "%<br>
                Net Total Contract Price: " . ftom($l_net_tcp) . "
            </div>
            </td>
            <td>
             <div class='form-group left-justified'>
                <br>
                " . ftom($l_net_tcp) . "<br>
                (" . ftom($total_discount) . ")
                Withholding Tax: " . ftom($l_withholding_tax) . "%
                Net Commission: " . ftom($l_net_commission) . "
                Others: " . ftom($l_oth) . "
                Net Commission Due: <span class='bold'>" . ftom($l_net_due_commission) . "</span>
            </div>
            </td>
        </tr>
    </table>
                <div class='form-group left-justified'>
            *** Due Commission *** (" . $l_comm_rate . "% Commission)
        </div>
        <div class='form-group left-justified'>
            Less: " . $l_withholding_tax . "% Withholding Tax
        </div>
        <div class='form-group left-justified'>
            Net Commission: " . ftom($l_net_commission) . "
        </div>
        <div class='form-group left-justified'>
            Less: Cash Advance: " . ftom($l_ca) . "
        </div>
        <div class='form-group left-justified'>
            Others: " . ftom($l_oth) . "
        </div>
        <div class='form-group left-justified'>
            Net Commission Still Due: <span class='bold'>" . ftom($l_net_due_commission) . "</span>
        </div>
        <div class='form-group center'>
            ================================
        </div>
        <div class='form-group left-justified'>
            Prepared by: __________ Checked By: __________ Approved by: __________
        </div>
        <div class='form-group left-justified'>
            MMDP - $l_date __________ _____________________
        </div>
        <div class='form-group left-justified'>
            CDV No.:_________ CDV Date:____________ Rcvd by:_______________
        </div>
    </div>
</body>
</html>
";

// Initialize Dompdf with options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A5', 'landscape');

// Render the PDF
$dompdf->render();

// Output the generated PDF (force download)
$dompdf->stream('commission_details.pdf', array('Attachment' => 0));
?>
