<?php require_once('../config.php'); ?>
<?php
//--->get app url > start

if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $ssl = 'https';
}
else {
  $ssl = 'http';
}

$app_url = ($ssl  )
          . "://".$_SERVER['HTTP_HOST']
          //. $_SERVER["SERVER_NAME"]
          . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
          . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");
//--->get app url > end
header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE html>
<head>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
	<script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
</head>
<!DOCTYPE html>
<html lang="en">
<?php include "../inc/header.php";?>
  <?php
    $query = "SELECT * from `t_csr` where c_csr_no = '{$_GET['id']}' ";
    $result = mysqli_query($conn, $query);
    // mysqli select query
    if($result) {
        while ($row = mysqli_fetch_assoc($result)) {
        
        $c_csr_no = $row['c_csr_no']; 
        $c_lot_area = $row['c_lot_area'];
        $c_price_sqm = $row['c_price_sqm'];
        $c_lot_discount_amt = $row['c_lot_discount_amt'];
        $c_lot_discount = $row['c_lot_discount'];
        $c_house_model = $row['c_house_model'];
        $c_date_created = $row['c_date_created'];
        $c_floor_area = $row['c_floor_area'];
        $c_house_discount = $row['c_house_discount'];
        $c_house_discount_amt = $row['c_house_discount_amt'];
        $c_house_price_sqm = $row['c_house_price_sqm'];
        $c_tcp = $row['c_tcp'];
        $remarks = $row['c_remarks'];
        $c_lot_discount_percentage = $row['c_lot_discount'];

        $c_reservation = $row['c_reservation'];
        $c_terms = $row['c_terms'];
        $c_monthly_payment = $row['c_monthly_payment'];
        $amt_fnanced = $row['c_amt_financed'];
        $interest_rate = $row['c_interest_rate'];
        $down_percent = $row['c_down_percent'];
	}
}
/* close connection */
$conn->close();
?>
<head>
    <link rel="stylesheet" href="css/print_ra_back.css">
</head>
<style>
.watermark_sample{
    background-image: url("images/4.png");
    width:400px;
    background-repeat:no-repeat;
    position:absolute;
    height:350px;
    margin-top:960px;
    margin-left:285px;
    opacity:0.1;
}
.whole_content{
    visibility:hidden;
}
</style>
<body onload="printRABack()">
<div class="whole_content">
<div class="text-center" style="padding:20px;">
	<input type="button" id="rep" value="Print" class="btn btn-info btn_print">
</div>
<div class="container_content" id="container_content">
    <div class="card-body">
    <div class="watermark_sample"></div>
    <div class="doc_title">PURCHASE GUIDELINES AND POLICIES</div>
    Provisions in the Reservation Agreement shall apply.<br>
1.&nbsp;&nbsp;RESERVATION FEE<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;Reservation money shall be paid to reserve the chosen lot of the buyer. Asian Land shall issue a corresponding Official Receipt for the application.<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;Reservation Fee for approved sale shall be valid for 45 days only. Any failure to make the necessary payments within the prescribed period shall &#8195;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cause the forfeiture of my reservation fee.<br>
2.&nbsp;&nbsp;PAYMENT METHOD<br>
&#8195;&nbsp;A.&nbsp;&nbsp;BANK FINANCING<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;The required down payment shall be payable in equal monthly installments based on the down schedule payment with issued post-dated checks<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;The remaining balance is subject for application for bank financing. The client should provide the loan application requirements five (5) months prior &#8195;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to Full downpayment.<br>
&#8195;&nbsp;B.&nbsp;&nbsp;DEFERRED CASH PAYMENT<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;The 20% minimum required full down payment shall be considered as reservation fee, the balance of the contract price is payable within a maximum &#8195;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;period of two (2) years for House & Lot: one (1) year for Lot only; and six (6) months for Fence only at zero<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;Client shall issue post-dated checks.<br>
3.&nbsp;&nbsp;PENALTIES & FEES<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;Past due shall be subject to 5% surcharge per month.<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;Should any one (1) of the issued post - dated checks be dishonored by the drawee bank, a fee of PhP 500.00 will be charged.<br>
&#8195;&nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;All payments shall be subject to the provisions of Republic Act No. 6552.<br>
<b>PAYMENTS</b><br>
A. LOCAL&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;Over-the-counter at our office located in Malolos. Checks should be made payable to ASIAN LAND STRATEGIES CORPORATION<br>
B. BANK - TO - BANK<br>

&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;Banco de Oro (BDO) &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;Account #: 002 - 550 - 023 - 753<br>
&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;Bank of Philippines Islands (BPI) &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;Account #: 460 - 1000 - 143<br>
&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;China Bank Savings (CBS) &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;Account #: 623 - 300 - 010 - 276<br>
&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;Security Bank (SB) &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;Account #: 0682 - 2486 - 59 - 001
<br>
&#8195;&nbsp;For payment related inquiries, please visit:&nbsp; <u><a href='#'>www.asianland.ph</a></u><br>
<div class="purchasers_profile" style="margin-bottom:-10px;">PURCHASER'S PROFILE</div><br>
<div style="text-align:center">In connection with my reservation and purchase of the Property, I/ We would like the purchase to be registered as follows:
</div>
<table>
    <tr>
        <td class="tdwidth1">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">Solely in my...</label>
            </div>
        </td>
        <td class="tdwidth1">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">In our names</label>
            </div>
        </td>
        <td class="tdwidth1">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">In the name of</label>
            </div>
        </td>
    </tr>
</table>
<table style="margin-top:-10px;margin-left:15px;">
    <tr>
        <td class="tdwidth1">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="radio" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">Individual</label>
            </div>
        </td>
        <td style="float:left;margin-right:70px;">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="radio" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">Spouses</label>
            </div>
        </td>
    </tr>
</table>
<table style="margin-top:18px;margin-left:15px;">
    <input type="text" class="form-control" style="position:absolute;width:170px;margin-left:255px;margin-top:-8px;padding-top:-5px;"> 
    <tr>
        <td class="tdwidth1">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="radio" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">Married to</label>
            </div>
        </td>
        <td class="tdwidth" style="position:absolute;margin-left:-515px;">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="radio" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">Co-owners</label>
            </div>
        </td>

    </tr>
</table>
<table style="margin-top:-10px;margin-left:5px;">
    <tr>
        <td class="tdwidth1">
            <input type="text" class="form-control" style="margin-left:20px;width:170px;margin-bottom:5px;margin-top:3px;padding-top:-5px;">
        </td>
        <td class="tdwidth1">
            <input type="text" class="form-control" style="margin-left:-425px;width:170px;margin-bottom:5px;margin-top:3px;padding-top:-5px;">
        </td>
    </tr>
</table>
<div class="rightside_part" style="float:left;margin-left:68%;height:auto;margin-top:-87px;">
    <table>
        <tr>
            <td>
                <div style="float:left;margin-right:2px;">
                    <input id="chkOption1" type="radio" name="chkOption1" />
                </div>
                <div style="float:left;margin-right:2px;">
                    <label class="label_notb">A Corporate</label>
                </div>
                <input type="text" class="form-control" style="width:145px; padding-left:3px;">
            </td>
        </tr>
        <tr>
            <td style="margin-top:5px;position:absolute;">
                <div style="float:left;margin-right:2px;">
                    <input id="chkOption1" type="radio" name="chkOption1" />
                </div>
                <div style="float:left;margin-right:2px;">
                    <label class="label_notb">A Partnership</label>
                </div>
                <input type="text" class="form-control" style="width:140px;">
            </td>
        </tr>
        <tr>
            <td style="margin-top:35px;position:absolute;">
                <div style="float:left;margin-right:2px;">
                    <input id="chkOption1" type="radio" name="chkOption1" />
                </div>
                <div style="float:left;margin-right:2px;">
                    <label class="label_notb">Trust Account</label>
                </div>
                <input type="text" class="form-control" style="width:140px;">
            </td>
        </tr>
    </table>
</div>
<div class="doc_title1">SOURCE OF FUNDING (Kindly check one)</div>
<table>
    <tr>
        <td class="tdwidth">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">LOCAL</label>
            </div>
        </td>
        <td class="tdwidth">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">OVERSEAS FILIPINO</label>
            </div>
        </td>
        <td class="tdwidth">
            <div style="float:left;margin-right:2px">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label class="label_notb">FOREIGNER</label>
            </div>
        </td>
    </tr>
</table>
<div class="doc_title1">MONTHLY HOUSEHOLD INCOME (Kindly check one) &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;OCCUPATION (Kindly check & must consistent with validated TIN</div>
<div class="left_table" style="font-size:9px;">
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Below P/ 40,000.00<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 150,000.00 -199,999.00<label>
        </div><br>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 40,000.00 - 59,999.00<label>
        </div>

        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 200,000.00 - 249,999.00<label>
        </div><br>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 60,000.00 - 79,999.00<label>
        </div>

        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 250,000.00 - 299,000.00<label>
        </div><br>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 80,000.00 - 99,999.00<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 300,000.00 - 399,999.00<label>
        </div><br>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 100,000.00 - 149,999.00<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">P/ 400,000.00 - or more<label>
        </div>
    </div>

</div>
<div class="right_table" style="font-size:9px;">
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Mixed Income Earner (Business Income & Compensation)<label>
        </div>
        <div style="float:left;margin-right:2px">
        
        </div>
        <div style="float:left">
            <label class="label_notb"><label>
        </div><br>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Business / Entrepreneur<label>
        </div>

        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Homebased<label>
        </div><br>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Locally Employed<label>
        </div>

        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Retired<label>
        </div><br>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Licensed Professional<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Others<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">OFW<label>
        </div>
    </div>  
    <div class="cont">
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;<input id="chkOption1" type="radio" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Landbased<label>
        </div>
    </div>
    <div class="cont">
    &#8195;&#8195;
        <div style="float:left;margin-right:2px">
            &#8195;&#8195;<input id="chkOption1" type="radio" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Seafarer<label>
        </div>
    </div>
</div>

<div class="doc_title" style="margin-top:5px">CREDIT ASSESSMENT CHECKLIST</div>
<div class="doc_title2">A. MANDATORY REQUIREMENTS</div>
<div class="doc_title2">IF SELF-EMPLOYED</div>
<div class="whole_table" style="font-size:9px;">
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Birth Certificate<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Income Tax Return w/ Audited Financial Statement (Latest)<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Marriage Certificate<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Business Registration / Permits (Latest)<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Any Government issued ID<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Latest three (3) months Bank Statement / Passbook<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Checking Account Passbook & Check Booklet<label>
        </div>
        <div style="float:left;margin-right:2px">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">List of Suppliers & Customers with contact persons and numbers<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Latest Utility bill in the name of the applicant(i.e. Meralco, Telephone, cable, water bill...)<label>
        </div>
    </div>    
</div>
<div class="doc_title3">B. INCOME REQUIREMENTS</div>
<div class="doc_title2">IF LOCALLY EMPLOYED: any one (1) of the following</div>
<div class="doc_title2">IF OFW: any one (1) of the following</div>
<div class="whole_table" style="font-size:9px;">
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Income Tax Returns or BIR Form 2316 (Latest)<label>
        </div>
    
        <div style="float:left;margin-right:2px">
        &nbsp;&nbsp;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Contract of Employment / Cert. of Employment w/ Compensation (Latest)<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Certificate of Employment and Compensation (Latest)<label>
        </div>
    
        <div style="float:left;margin-right:2px">
        &nbsp;&nbsp;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Latest three (3) months payslip<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Three (3) months payslip (Latest)<label>
        </div>
    </div>
    <div class="doc_title3">C. ADDITIONAL REQUIREMENTS</div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Letter of Authority<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Working Visa / Immigrant Visa / Visa Card / iCARD<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left;margin-right:2px">
            <input id="chkOption1" type="checkbox" name="chkOption1" />
        </div>
        <div style="float:left">
            <label class="label_notb">Crew Service Record (IF SEAFARER)<label>
        </div>
    </div>
</div>
<div class="doc_titlerem">REMARKS</div>
<div class="whole">
&#8195;&#8195;&#8195;1.&nbsp;&nbsp;Miscellaneous Fee is payable upon Full Downpayment<br>
&#8195;&#8195;&#8195;2.&nbsp;&nbsp;A Maintenance Fee shall be charged to client, commencing on the first monthly downpayment, such as:
</div>
<div class="whole_table" style="margin-bottom:-75px;">
    <div class="cont" style="margin-top:5px;">
        <div style="float:left">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;<label class="label_notb">a. Streetlight (STL)<label>
        </div>
        <div style="float:left">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;<label class="label_notb">Php 100.00 / month<label>
        </div>
    </div>
    <div class="cont">
        <div style="float:left">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;<label class="label_notb">b. Grass cutting (GCF)<label>
        </div>
        <div style="float:left">
        &#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&nbsp;&nbsp;<label class="label_notb">Php 2.00 per sq.m. / month<label>
        </div>
    </div>
</div>

<div class="doc_title">DATA PRIVACY CONTENT</div>

In compliance with the Data Privacy Act (DPA) of 2012, and its implementing Rules and Regulations (IRR) effective since September 8, 2016, I allow
Asian Land Strategies Corporation (ALSC) to provide me certain services declared in relation to this closed sales report. As such, I agree and authorize
ALSC to:<br><br>

&#8195;&#8195;&#8195;1.&nbsp;&nbsp;Retain  my  information for a period  of three (3)  years for the  date of cancellation of my account, or at such time that I submit to ALSC a written <br>&#8195;&#8195;&#8195;&#8195; cancellation of this consent, whichever is earlier. I agree that my information will be deleted/destroyed after this period.<br>
&#8195;&#8195;&#8195;2.&nbsp;&nbsp;Share my  information to  affiliates and  necessary third parties for any  legitimate  business  purpose. I am assured that security systems are <br>&#8195;&#8195;&#8195;&#8195; employed to protect my information.<br>
&#8195;&#8195;&#8195;3.&nbsp;&nbsp;Inform me of future customer campaigns, billing notifications and updates using the personal information I shared with the company. I
have <br>&#8195;&#8195;&#8195;&#8195; read this form, understood its contents and consent to the processing of my personal data. I understand that my consent does not
preclude the <br>&#8195;&#8195;&#8195;&#8195; existence of other criteria for the lawful processing of personal data, and does not waive any rights under the Data Privacy Act of
2012 and other <br>&#8195;&#8195;&#8195;&#8195; applicable laws.<br><br><br>

&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;___________________________________________&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195; ___________________________________________<br>
&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;Client Signature over Printed Name/Date&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;&#8195;Client Signature over Printed Name/Date
    </div>

</div>
</div>
</body>
</html>



<script type="text/javascript">
    function printRABack(){             
			var element = document.getElementById('container_content'); 

			var opt = 
			{
			  margin:       [5,20,0,5],
			  filename:    'RA<?php echo $c_csr_no; ?>_BACKPAGE'+'.pdf',
              
			  image:        { type: 'jpeg', quality: 2 },
			  html2canvas:  { dpi: 300, letterRendering: true, width: 780, height: 1500, scale:2},
			  jsPDF:        { unit: 'mm', format: 'legal', orientation: 'portrait' }
			};

			html2pdf().set(opt).from(element).save();

            window.setTimeout(function(){
            window.history.back();
           }, 500);
        };
	</script>
 
