<?php
include('../config.php');

$c_or_no = $_GET['c_or_no'] ?? '';
$c_or_type = $_GET['atap_val'] ?? '';
$c_or_amount = $_GET['c_or_amount'] ?? '';
$c_or_paydate = $_GET['c_or_paydate'] ?? '';
$c_encoded_by = $_GET['c_encoded_by'] ?? '';
$c_mop = $_GET['c_mop_or'] ?? '';
$c_bank_check = $_GET['c_bank_check_or'] ?? '';
$c_bank_online = $_GET['c_bank_online_or'] ?? '';
$c_check_no = $_GET['c_check_no'] ?? '';
$c_ref_no = $_GET['c_ref_no'] ?? '';
$c_remarks = $_GET['c_remarks'] ?? '';
$c_name = $_GET['c_name'] ?? '';
$c_vat_sales = $_GET['c_vat_sales'] ?? '';
$c_vat_amount = $_GET['c_vat_amount'] ?? '';
$c_ewt = $_GET['c_ewt'] ?? '';

$c_bank = '';
$c_bank_2 = '';
$c_check = '';
$c_ref = '';
$c_or_paydate_1 = '';
$c_or_paydate_2 = '';

if ($c_bank_check == '' || $c_bank_check == null){
    $c_bank_2 = $c_bank_online;
}else{
    $c_bank = $c_bank_check;
}

if ($c_check_no == '' || $c_check_no == null){
    $c_ref = $c_ref_no;
}else{
    $c_check = $c_check_no;
}

if ($c_mop == 3) {
    $c_or_paydate_2 = $c_or_paydate;
} elseif ($c_mop == 2) {
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
            margin-top:303px;
            margin-left:10px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        #sign_cash{
            margin-top:290px;
            margin-left:-65px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
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
        



        #c_name {
            text-transform: uppercase!important;
            float: right;
            margin-top: 85px;
            margin-right:-10px!important;
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
        #location{
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
        #c_mop{
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
            padding-left:35px;
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
            padding-left:35px;
            padding-top:5px;
            line-height: 15px;
            float:left;
            margin-left:560px;
        }
        #c_sales{
            float: right;
            margin-top: 310px;
            margin-right: -530px;
            width: 140px;
            text-align: right;
        }
        #c_vat{
            float: right;
            margin-top: 340px;
            margin-right: -530px;
            width: 140px;
            text-align: right;
        }
        #c_ewt{
            float: right;
            margin-top: 355px;
            margin-right: -530px;
            width: 140px;
            text-align: right;
        }
        #c_or_amount {
            float: right;
            margin-top: 280px;
            margin-right: -530px;
            width: 140px;
            text-align: right;
        }
        #c_or_amount2 {
            float: right;
            margin-top: 375px;
            margin-right: -530px;
            width: 140px;
            text-align: right;
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
            $c_name = $c_name ?? '';

            if (strpos($c_name, 'Spouses ') === 0) {
                $c_name = substr($c_name, strlen('Spouses '));
            }

            $c_phase = isset($_GET['c_phase']) ? $_GET['c_phase'] : '';
            $c_block = isset($_GET['c_block']) ? $_GET['c_block'] : '';
            $c_lot = isset($_GET['c_lot']) ? $_GET['c_lot'] : '';

            $c_loc = "----------------------";

            if (!empty($c_phase) && !empty($c_block) && !empty($c_lot)) {
                $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                if ($phase_stmt && odbc_execute($phase_stmt, array($c_phase))) {
                    $phase_details = odbc_fetch_array($phase_stmt);

                    if ($phase_details) {
                        $c_loc = $phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot;
                    }
                }
            }
        ?>
            <textarea name="c_name" id="c_name"><?php echo htmlspecialchars($c_name); ?></textarea>
            <textarea name="c_address" id="c_address">--------------</textarea>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($c_loc); ?>">
            <input type="text" name="c_acc_no" id="c_acc_no" value="--------------">

            <input type="text" name="c_or_type" id="c_or_type" value="<?php echo htmlspecialchars($c_or_type); ?>">
           
            <input type="text" name="c_bank_main" id="c_bank_main" value="<?php echo htmlspecialchars($c_bank); ?>">
            <input type="text" name="c_bank_main_2" id="c_bank_main_2" value="<?php echo htmlspecialchars($c_bank_2); ?>">

            <?php if ($c_mop == 2): ?>
                <input type="text" name="sign_check" id="sign_check" value="✓">
            <?php elseif ($c_mop == 3): ?>
                <input type="text" name="sign_ref" id="sign_ref" value="✓">
            <?php elseif ($c_mop == 1): ?>
                <input type="text" name="sign_cash" id="sign_cash" value="✓">
            <?php endif; ?>

            <input type="text" name="c_check_main" id="c_check_main" value="<?php echo htmlspecialchars($c_check); ?>">
            <input type="text" name="c_ref_main" id="c_ref_main" value="<?php echo htmlspecialchars($c_ref); ?>">
        
        <textarea rows="6" name="c_remarks" id="c_remarks"><?php echo htmlspecialchars($c_remarks); ?></textarea>
        <input type="text" name="c_or_amount" id="c_or_amount" value="<?php echo number_format((float)str_replace(',', '', $c_or_amount), 2); ?>">
        <input type="text" name="c_or_amount2" id="c_or_amount2" value="<?php echo number_format((float)str_replace(',', '', $c_or_amount), 2); ?>">
        <textarea name="c_or_amount_words" id="c_or_amount_words"></textarea>
        <input type="text" name="c_or_no" id="c_or_no" value="<?php echo htmlspecialchars($c_or_no); ?>">
        
        <!-- formulaaaaaaaaaaaaaa -->
        <input type="text" name="c_or_amount" id="c_or_amount" 
            value="<?php echo number_format((float)str_replace(',', '', $c_or_amount), 2); ?>">

        <!-- Revised VAT -DHEN -->
        <input type="text" name="c_vat_sales" id="c_sales" 
            value="<?php echo ($c_vat_sales == 0 || $c_vat_sales == '0.00') ? '' : number_format((float)str_replace(',', '', $c_vat_sales), 2); ?>">
        
        <input type="text" name="c_vat_amount" id="c_vat" 
            value="<?php echo ($c_vat_amount == 0 || $c_vat_amount == '0.00') ? '' : number_format((float)str_replace(',', '', $c_vat_amount), 2); ?>">

        <input type="text" name="c_ewt" id="c_ewt" 
            value="<?php echo ($c_ewt == 0 || $c_ewt == '0.00') ? '' : number_format((float)str_replace(',', '', $c_ewt), 2); ?>">
            
        <!-- <input type="text" name="c_sales" id="c_sales" readonly>
        <input type="text" name="c_vat" id="c_vat" readonly>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var orAmountInput = document.getElementById('c_or_amount');
                var salesInput = document.getElementById('c_sales');
                var vatInput = document.getElementById('c_vat');

                function formatNumber(num) {
                    return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                function calculateSalesAndVAT() {
                    var or_amount = orAmountInput.value.replace(/,/g, '').trim();

                    if (or_amount !== '' && !isNaN(or_amount)) {
                        var amount = parseFloat(or_amount) || 0;
                        var sales = amount / 1.12;
                        var vat = amount - sales;

                        salesInput.value = formatNumber(sales);
                        vatInput.value = formatNumber(vat);
                    } else {
                        salesInput.value = '';
                        vatInput.value = '';
                    }
                }
                orAmountInput.addEventListener('input', calculateSalesAndVAT);
                calculateSalesAndVAT();
            });
        </script> -->
        
        <?php
            $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = ?";
            $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
            odbc_execute($encoder_stmt, array($c_encoded_by));
            if ($encoder = odbc_fetch_array($encoder_stmt)) {
                $realname = htmlspecialchars($encoder["c_realname"]);
            }
        ?>
        <input type="text" name="c_paydate_check" id="c_paydate_check" value="<?php echo htmlspecialchars($c_or_paydate_1); ?>">
        <input type="text" name="c_paydate_online" id="c_paydate_online" value="<?php echo htmlspecialchars($c_or_paydate_2); ?>">

        <input type="text" name="c_encoded_by" id="c_encoded_by" value="<?php echo $realname; ?>">

        <?php $c_mop = $c_mop ?? 0; ?>
        <div class="dynamic-margin" id="dynamicMarginDiv">
            <input type="hidden" id="c_mop" value="<?php echo number_format((float)str_replace(',', '', $c_or_amount), 2); ?>">
            <input type="hidden" id="c_mop_value" value="<?php echo $c_mop; ?>">
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
        <br>
        --------------<br>
        </div>
        
    </div>
    <!-- <div class="btn-container">
        <button type="button" class="btn btn-primary" onclick="saveAsImage()" id="btnSave">Save as PNG</button> 
    </div> -->
    <!-- <script>
        var cMopValue = document.getElementById('c_mop_value').value;
        var cBankCheck = document.getElementById('c_bank_main').value;
        var cCheckNo = document.getElementById('c_check_main').value;
        var cPayDateField = document.getElementById('c_paydate');
        var dynamicMarginDiv = document.getElementById('dynamicMarginDiv');

        if (cMopValue == '1') {
            dynamicMarginDiv.style.marginTop = '190px';
            cPayDateField.style.display = 'none';
            cBankCheck.style.display = 'none';
            cCheckNo.style.display = 'none';
        } else {
            dynamicMarginDiv.style.marginTop = '205px';
            dynamicMarginDiv.style.marginRight = '-205px';
            cPayDateField.style.display = 'block';
            cBankCheck.style.display = 'block';
            cCheckNo.style.display = 'block';
        }
    </script> -->
    <script>
        function initializePage() {
            adjustTextArea('c_name');
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