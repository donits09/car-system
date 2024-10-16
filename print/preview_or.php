<?php
include('../config.php');

$c_account_no_or = $_GET['c_account_no_or'] ?? '';
$c_or_no = $_GET['c_or_no'] ?? '';
$c_or_type = $_GET['atap_val'] ?? '';
$c_or_amount = $_GET['c_or_amount'] ?? '';
$c_or_paydate = $_GET['c_or_paydate'] ?? '';
$c_encoded_by = $_GET['c_encoded_by'] ?? '';
$c_mop_or = $_GET['c_mop_or'] ?? '';
$c_bank_check_or = $_GET['c_bank_check_or'] ?? '';
$c_bank_online_or = $_GET['c_bank_online_or'] ?? '';
$c_check_no = $_GET['c_check_no'] ?? '';
$c_ref_no = $_GET['c_ref_no'] ?? '';
$c_remarks = $_GET['c_remarks'] ?? '';

$c_bank = '';
$c_bank_2 = '';
$c_check = '';
$c_ref = '';
$c_or_paydate_1 = '';
$c_or_paydate_2 = '';

if ($c_bank_check_or == '' || $c_bank_check_or == null){
    $c_bank_2 = $c_bank_online_or;
}else{
    $c_bank = $c_bank_check_or;
}

if ($c_check_no == '' || $c_check_no == null){
    $c_ref = $c_ref_no;
}else{
    $c_check = $c_check_no;
}

if ($c_mop_or == 3) {
    $c_or_paydate_2 = $c_or_paydate;
} elseif ($c_mop_or == 2) {
    $c_or_paydate_1 = $c_or_paydate;
}

function fetchBuyerDetails($conn, $accountNo) {
    if (empty($accountNo)) {
        return false;
    }
    $query = "SELECT * FROM t_buyers_account WHERE c_account_no = ?";
    $stmt = odbc_prepare($conn, $query);

    if (odbc_execute($stmt, array($accountNo))) {
        return odbc_fetch_array($stmt);
    }
    return false;
}

function format_value($value) {
    if ($value === null || $value == 0) {
        return '';
    } else {
        return number_format((float)str_replace(',', '', $value), 2);
    }
}
$buyerDetails = fetchBuyerDetails($conn, $c_account_no_or);

?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .small-font {
            font-size: 12px;
            white-space: pre-wrap; 
            margin-top: 85px!important;
            margin-right:-5px!important;
        }
        .normal-font {
            font-size: 18px!important;
        }
        textarea {
            width: 100%;
            overflow: hidden; 
            resize: none; 
        }
        body {
            position: relative;
            font-size: 8px !important;
        }
        .container {
            position: relative;
            width: 500px;
            padding: 20px;
            box-sizing: border-box;
            z-index: 2; 
        }
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            height: 470px;
            width: 720px;
            z-index: 1;
            object-fit: cover;
            border:black solid 1px;
        }
        input {
            border: none;
            width: 100px;
            text-align: left;
            background-color: transparent;
        }
        textarea{
            width: 100%;
            text-align: left;
            font-weight: 300;
            border: none;
            background-color: transparent;
            font-size: 11px !important;
            resize: none;
            overflow: hidden;
        }
        
        #c_or_type {
            width: 440px;
            height:auto;
            text-align: center;
            font-size: 12px !important;
            margin-top:185px;
            position:absolute;
        }
       
        #c_or_no {
            float: right;
            margin-top: 50px;
            margin-right: -540px;
            width: 80px;
            font-size:16px;
        }
        
        #c_bank{
            margin-top: 150px;
            margin-right: 50px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        .dynamic-margin {
            width: 100px;
            height: 100px;
            margin-left:70px;
            position:absolute;
        }
        #c_bank_main{
            margin-top: 302px;
            margin-left: 325px;
            width: auto;
            text-align: left;
            font-size: 12px !important;
            position:absolute;
        }
        #c_bank_main_2{
            margin-top: 318px;
            margin-left: 325px;
            width: auto;
            text-align: left;
            font-size: 12px !important;
            position:absolute;
        }
        #c_check_main{
            margin-top:318px;
            margin-left:10px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;

        }
        #c_check_main2{
            margin-top:305px;
            margin-left:10px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
            /* background-color: red; */
        }
        #sign_check{
            margin-top:303px;
            margin-left:-65px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        #c_ref_main{
            margin-top:318px;
            margin-left:15px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        #sign_ref{
            margin-top:318px;
            margin-left:-65px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        .btn-container {
            display: flex;
            justify-content: flex-end;
            margin-top: -40px;
        }
        #btnSave {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 12px;
            margin-top:300px;
            position:absolute;
        }
        #c_received {
            text-transform: uppercase!important;
            float: right;
            margin-right:-10px;
            margin-top: 85px;
            width: 350px;
            padding:0px;
        }
        #c_current_date {
            float: right;
            margin-top: 90px;
            margin-right: -200px;
        }
        #c_address {
            text-transform: uppercase;
            float: right;
            margin-top: 108px;
            margin-right: -360px;
            width: 360px;
            text-align: left;
        }
        #c_loc{
            text-transform: uppercase;
            width: auto;
            text-align: left;
            font-size: 12px !important;
            float:right;
            margin-left:555px;
            margin-top:107px;
            position:absolute;
        }
        #c_acc_no{
            text-transform: uppercase;
            float: right;
            margin-left:555px;
            margin-top:124px;
            width: auto;
            text-align: left;
            font-size: 12px !important;
            position:absolute;
        }
        #c_remarks{
            width: 440px;
            height:auto;
            text-align: center;
            font-size: 12px !important;
            margin-top:230px;
            position:absolute;
        }
        #c_or_amount_words {
            float: right;
            margin-top: 370px;
            width: 420px;
            height: auto;
            line-height: 1.2em;
            overflow: hidden;
            white-space: pre-wrap;
            word-wrap: break-word;
            position:absolute;
            padding-left:5px;
        }
        #c_or_amount {
            float: right;
            margin-top: 280px;
            margin-right: -600px;
            width: 140px;
        }
        #c_or_amount2 {
            float: right;
            margin-top: 365px;
            margin-right: -600px;
            width: 140px;
        }
        #c_encoded_by {
            text-transform: uppercase!important;
            float: left;
            margin-top: 393px;
            width: auto;
            margin-left:550px;
            text-align: left;
            font-size: 10px !important;
            position:absolute;
        }
        #c_mop_or{
            font-size: 10px !important;
            margin-top:100px;
            margin-left:-15px;
        }
        #c_paydate_check {
            float: left;
            margin-top: 168px;
            width: auto;
            margin-left:200px;
            text-left: center;
            font-size: 10px !important;
        }
        #c_paydate_online {
            float: left;
            margin-top: 2px;
            width: auto;
            margin-left:200px;
            text-left: center;
            font-size: 10px !important;
        }
        .dashes{
            font-size: 10px !important;
            height:110px;
            width:100px;
            position: fixed;
            margin-top:170px;
            padding-left:25px;
            padding-top:5px;
            line-height: 15px;
            float:left;
            margin-left:560px;
        }
        .dashes2{
            font-size: 10px !important;
            height:50px;
            width:100px;
            position: fixed;
            margin-top:310px;
            padding-left:25px;
            padding-top:5px;
            line-height: 15px;
            float:left;
            margin-left:560px;
        }
    </style>
</head>

<body onload="initializePage()" id="previewORContent">
    <img src="<?php echo base_url; ?>images/ALSC_OR.jpg" class="background-image" alt="OR Scanned Copy">
     
    <div class="container">
        <div class="box_middle">
            <input type="text" name="c_current_date" id="c_current_date" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <?php
        if ($buyerDetails) {
            $lname = $buyerDetails["c_b1_last_name"];
            $fname = $buyerDetails["c_b1_first_name"];
            $mname = $buyerDetails["c_b1_middle_name"];
            $address = $buyerDetails["c_address"];
            $prov = $buyerDetails["c_city_prov"];
            $zip = $buyerDetails["c_zip_code"];
            $c_account_no_or = $buyerDetails["c_account_no"];

            $c_phase = substr($c_account_no_or, 0, 3);
            $c_block = ltrim(substr($c_account_no_or, 3, 3), '0'); 
            $c_lot = substr($c_account_no_or, 6, 2);

            if (strpos($fname, 'Spouses ') === 0) {
                $fname = substr($fname, strlen('Spouses '));
            }

            $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
            $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

            if (odbc_execute($phase_stmt, array($c_phase))) {
                $phase_details = odbc_fetch_array($phase_stmt);

                if ($phase_details) {
                    $loc = $phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot;
                }
            }
            $full_address = trim($address);
            if ($prov || $zip) {
                $full_address .= ($prov ? ', ' . trim($prov) : '') . ($zip ? ', ' . trim($zip) : '');
            }
            $fullName = htmlspecialchars(trim($fname) . ' ' . trim($mname) . ' ' . trim($lname));
        ?>
            <textarea name="c_received" id="c_received"><?php echo $fullName; ?></textarea>
            <textarea name="c_address" id="c_address"><?php echo $full_address; ?></textarea>
            <textarea name="c_loc" id="c_loc"><?php echo $loc; ?></textarea>
            <input type="text" name="c_acc_no" id="c_acc_no" value="<?php echo htmlspecialchars($c_account_no_or); ?>">

            <input type="text" name="c_or_type" id="c_or_type" value="<?php echo htmlspecialchars($c_or_type); ?>">
           
            <input type="text" name="c_bank_main" id="c_bank_main" value="<?php echo htmlspecialchars($c_bank); ?>">
            <input type="text" name="c_bank_main_2" id="c_bank_main_2" value="<?php echo htmlspecialchars($c_bank_2); ?>">

            <?php if ($c_mop_or == 2): ?>
                <input type="text" name="sign_check" id="sign_check" value="✓">
            <?php elseif ($c_mop_or == 3): ?>
                <input type="text" name="sign_ref" id="sign_ref" value="✓">
            <?php endif; ?>
                
            <input type="text" name="c_check_main" id="c_check_main" value="<?php echo htmlspecialchars($c_check); ?>">
            <input type="text" name="c_ref_main" id="c_ref_main" value="<?php echo htmlspecialchars($c_ref); ?>">

           
        <?php } ?>

        <textarea rows="6" name="c_remarks" id="c_remarks"><?php echo htmlspecialchars($c_remarks); ?></textarea>
        <input type="text" name="c_or_amount" id="c_or_amount" value="<?php echo number_format((float)str_replace(',', '', $c_or_amount), 2); ?>">
        <input type="text" name="c_or_amount2" id="c_or_amount2" value="<?php echo number_format((float)str_replace(',', '', $c_or_amount), 2); ?>">
        <textarea name="c_or_amount_words" id="c_or_amount_words"></textarea>
        <input type="text" name="c_or_no" id="c_or_no" value="<?php echo htmlspecialchars($c_or_no); ?>">

        <?php
            $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = ?";  /* ddd */
            $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
            odbc_execute($encoder_stmt, array($c_encoded_by));
            if ($encoder = odbc_fetch_array($encoder_stmt)) {
                $realname = htmlspecialchars($encoder["c_realname"]);
            }
        ?>
        <input type="text" name="c_paydate_check" id="c_paydate_check" value="<?php echo htmlspecialchars($c_or_paydate_1); ?>">
        <input type="text" name="c_paydate_online" id="c_paydate_online" value="<?php echo htmlspecialchars($c_or_paydate_2); ?>">

        <input type="text" name="c_encoded_by" id="c_encoded_by" value="<?php echo $realname; ?>">

        <?php $c_mop_or = $c_mop_or ?? 0; ?>
        <div class="dynamic-margin" id="dynamicMarginDiv">
            <input type="hidden" id="c_mop_or" value="<?php echo number_format((float)str_replace(',', '', $c_or_amount), 2); ?>">
            <input type="hidden" id="c_mop_value" value="<?php echo $c_mop_or; ?>">
        </div>
        <div class="dashes">
        --------------<br>
        --------------<br>
        --------------<br>
        --------------<br>
        --------------<br>
        --------------<br>
        --------------<br>
        </div>
        <div class="dashes2">
        --------------<br>
        --------------<br>
        --------------<br>
        </div>
    </div>
    <!-- <div class="btn-container">
        <button type="button" class="btn btn-primary" onclick="saveAsImage()" id="btnSave">Save as PNG</button> 
    </div> -->
    <!-- <script>
        var cMopValue = document.getElementById('c_mop_value').value;
        var cBankCheck = document.getElementById('c_bank_main').value;
        var cBankCheck2 = document.getElementById('c_bank_main_2').value;
        var cCheckNo = document.getElementById('c_check_main').value;
        var cPayDateField = document.getElementById('c_paydate');
        var dynamicMarginDiv = document.getElementById('dynamicMarginDiv');

        if (cMopValue == '1' || cMopValue == '2') {
            dynamicMarginDiv.style.marginTop = '180px';
            cPayDateField.style.display = 'none';
            cBankCheck.style.display = 'block';
            cCheckNo.style.display = 'block';
        } else {

            dynamicMarginDiv.style.marginTop = '500px';
            dynamicMarginDiv.style.marginRight = '-205px';

            cPayDateField.style.display = 'block';
            cBankCheck2.style.display = 'block';
            cCheckNo.style.display = 'block';
        }

    </script> -->
    <script>
        function initializePage() {
            adjustTextArea('c_received');
            convertORAmountToWords();
        }

        function adjustTextArea(id) {
            var receivedField = document.getElementById(id);
            if (receivedField.value.length > 35) {
                receivedField.classList.add('small-font');
                receivedField.value = receivedField.value.match(/.{1,55}/g).join('\n');
            } else {
                receivedField.classList.add('normal-font');
            }
        }

        function convertToWords(number) {
            var ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
            var teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
            var tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

            function convertGroup(num) {
                var result = "";
                if (num >= 100) {
                    result += ones[Math.floor(num / 100)] + " Hundred ";
                    num %= 100;
                }
                if (num >= 10 && num <= 19) {
                    result += teens[num - 10];
                } else if (num >= 20) {
                    result += tens[Math.floor(num / 10)];
                    if (num % 10 > 0) {
                        result += " " + ones[num % 10];
                    }
                } else if (num > 0) {
                    result += ones[num];
                }
                return result;
            }

            var result = "";
            if (number >= 1000000) {
                result += convertGroup(Math.floor(number / 1000000)) + " Million ";
                number %= 1000000;
            }
            if (number >= 1000) {
                result += convertGroup(Math.floor(number / 1000)) + " Thousand ";
                number %= 1000;
            }
            if (number >= 1) {
                result += convertGroup(Math.floor(number));
            }

            var decimalPart = number % 1;
            if (decimalPart > 0) {
                result += " and " + (decimalPart * 100).toFixed(0) + "/100";
            }

            result = result.trim() + " Pesos Only";

            return result;
        }

        function convertORAmountToWords() {
            var orAmountElement = document.getElementById("c_or_amount");
            var orAmountWordsElement = document.getElementById("c_or_amount_words");

            var orAmount = parseFloat(orAmountElement.value.replace(/,/g, ''));
            var orAmountWords = convertToWords(orAmount);

            orAmountWordsElement.value = orAmountWords;

            var lineHeight = parseInt(window.getComputedStyle(orAmountWordsElement).lineHeight);
            var lines = orAmountWordsElement.scrollHeight / lineHeight;
            var fontSize = 15;
            if (lines > 1) {
                fontSize -= (lines - 1) * 2;
            }
            orAmountWordsElement.style.fontSize = fontSize + "px";
        }

        window.onbeforeprint = function() {
            return false;
        };

        window.print = function() {
        };

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    </script>
    </div>
<script src="<?php echo base_url; ?>dist/header_files/html2canvas.min.js_0.5.0-beta4/cdnjs/html2canvas.min.js"></script>
</body>
</html>