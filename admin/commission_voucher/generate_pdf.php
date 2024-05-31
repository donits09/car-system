<?php
require '../../dompdf/autoload.inc.php';


use Dompdf\Dompdf;
use Dompdf\Options;

// Collect form data
$account_no = $_POST['account_no'];
$buyer1 = $_POST['buyer1'];
$due_commission = $_POST['due_commission'];
$others = $_POST['others'];

$l_rate = "5";
$l_agent = "John Doe";
$l_pos = "Senior Broker";
$l_name = "Jane Smith";
$l_project = "Sunrise Villas";
$l_block = "Block A";
$l_lot = "Lot 12";
$l_sum_res = 100000;
$l_rs = [["2023-05-15", "12345", 20000, 80000, "First Payment"]];
$l_last = 0;
$l_rst = [[0, 0, 0, 0, 0, 0, 0, 100000, 5, 20000, 5000, 1000, 10, 5000, 20, 3000, 4000, 5000, 0, 5, 5]];
$l_pay_date = "05/15/2023";
$l_paid = 20000;
$l_bal = 80000;
$l_val2 = 20000;
$l_total_lot_price = 100000;
$l_total_lot_discount = 10000;
$l_net_tcp = 90000;
$l_commission = 4500;
$l_comm_rate = "10";
$l_due_commission = 4500;
$l_withholding_tax = 450;
$l_net_commission = 4050;
$l_ca = 500;
$l_oth = 200;
$l_total_deductions = 700;
$l_net_due_commission = 3350;
$l_date = "2023-05-30";

// Convert numbers to money format
function ftom($value) {
    return number_format($value, 2);
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
            width: 100%;
            padding: 10px;
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
            <span>Buyer's Name: ".(strlen($l_name) > 65 ? substr($l_name, 0, 64) : $l_name)."</span>
        </div>
        <div class='form-group'>
            <span>Property: $l_project $l_block $l_lot</span>
        </div>
        <div class='form-group'>
            <span>FD Rate: ".ftom($l_rst[0][8])."% ( P ".ftom($l_rst[0][9] + $l_sum_res).")</span>
        </div>
        <div class='details-header'>
            ***** Details of Down Payment *****
        </div>
        <div class='details-header'>
            O.R. Date O.R. No. Amount Paid Prin. Balance Remarks
        </div>
        <div class='form-group'>
            <span>$l_pay_date </span>
        </div>
        <div class='form-group'>
            <span>$l_pay_date </span>
        </div>
        <hr>
        <div class='form-group'>
            <span>Total Amount Paid: $l_val2</span>
        </div>
        <div class='details-header'>
            ***** Computation of Commission *****
        </div>
        <div class='form-group left-justified'>
            LCP: ".ftom($l_rst[0][10])." sqm x ".ftom($l_rst[0][11])." = ".ftom($l_total_lot_price)." &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; 
            HCP: ".ftom($l_rst[0][13])." sqm x ".ftom($l_rst[0][14])." = ".ftom($l_total_lot_discount)."<br>
            Less: Discount L ".ftom($l_rst[0][12])."% H ".ftom($l_rst[0][19])."% T ".ftom($l_rst[0][20])." (".ftom($l_total_lot_discount).")&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;
            Commission Rate: $l_rate% ".ftom($l_commission)." <br>
            Net Total Contract Price: ".ftom($l_net_tcp)."
      
           
        </div>
        <div class='form-group left-justified'>
            *** Due Commission *** ($l_comm_rate% Commission) ".ftom($l_due_commission)."
        </div>
        <div class='form-group left-justified'>
            Less: 10% Withholding Tax ".ftom($l_withholding_tax)."
        </div>
        <div class='form-group left-justified'>
            Net Commission: ".ftom($l_net_commission)."
        </div>
        <div class='form-group left-justified'>
            Less: Cash Advance: ".ftom($l_ca)."
        </div>
        <div class='form-group left-justified'>
            Others: ".ftom($l_oth)." (".ftom($l_total_deductions).")
        </div>
        <div class='form-group left-justified'>
            Net Commission Still Due: <span class='bold'>".ftom($l_net_due_commission)."</span>
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
