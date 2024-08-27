<?php

include('../config.php');
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
function fetchORDetails($conn, $orNo) {
    $query = "SELECT * FROM t_other_or_payment WHERE c_or_no = ?";
    $stmt = odbc_prepare($conn, $query);
    if (odbc_execute($stmt, array($orNo))) {
        return odbc_fetch_array($stmt);
    }
    return false;
}

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $accountId = $_GET['id'];
    $get_or_query = "SELECT a.id, a.c_account_no, a.c_or_no, a.c_or_type,
                      a.c_or_paydate,a.c_or_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop, b.c_name, b.c_phase,
                      b.c_block, b.c_lot, a.c_or_paydate, a.c_bank, a.c_check_no, a.c_remarks
                      FROM t_or_payment a
                      LEFT JOIN t_other_or_payment b ON a.c_or_no = b.c_or_no
                      WHERE a.c_or_no = ?";
    $stmt = odbc_prepare($conn, $get_or_query);
    odbc_execute($stmt, array($accountId));
    $result = odbc_fetch_array($stmt);

    if ($result) {
        $row = $result;
        $c_account_no = $row['c_account_no'];
        $c_or_paydate = $row['c_or_paydate'];
        $buyerDetails = fetchBuyerDetails($conn, $c_account_no);
        $orDetails = fetchORDetails($conn, $row['c_or_no']);
        $c_remarks = $row['c_remarks'];
        ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../dist/css/car_print.css">
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
            float: right;
            margin-top: 185px;
            margin-right: -180px;
            width: 300px;
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
        #c_check_main{
            margin-top:303px;
            margin-left:10px;
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
            margin-top:175px;
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
        #c_mop{
            font-size: 10px !important;
            margin-top:100px;
            margin-left:-15px;
        }
        #c_paydate {
            float: left;
            margin-top: 168px;
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
<body onload="initializePage()">
    <img src="<?php echo base_url ?>images/ALSC_OR.jpg" class="background-image" alt="OR Scanned Copy">
         <!-- <img src=""> -->
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
                $c_account_no = $buyerDetails["c_account_no"];

                $c_phase = substr($c_account_no, 0, 3);
                $c_block = ltrim(substr($c_account_no, 3, 3), '0'); 
                $c_lot = substr($c_account_no, 6, 2);

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
            
            <input type="text" name="c_bank" id="c_bank" value="<?php echo $row['c_bank']; ?>">
            <input type="text" name="c_check_main" id="c_check_main" value="<?php echo $row['c_check_no']; ?>">
        <?php } else if ($orDetails) {
            $car_no = $orDetails["c_or_no"];
            $c_name = $orDetails["c_name"];
            $c_phase = $row['c_phase'];
            $c_block = $row['c_block'];
            $c_lot = $row['c_lot'];
            $loc ="";

            if (empty($c_phase) && empty($c_block) && empty($c_lot)) {
            } else {
                $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                if (odbc_execute($phase_stmt, array($c_phase))) {
                    $phase_details = odbc_fetch_array($phase_stmt);

                    if ($phase_details) {
                        $loc = $phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot;
                    }
                }
            }
        ?>
            <textarea name="c_received" id="c_received"><?php echo htmlspecialchars($c_name); ?></textarea>
            <textarea name="c_address" id="c_address">-----------------</textarea>
            <textarea name="c_loc" id="c_loc"><?php echo $loc; ?></textarea>
        <?php } ?>

        <?php
            $c_encoded_by = $row['c_encoded_by'];
            $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = ?";
            $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
            odbc_execute($encoder_stmt, array($c_encoded_by));
            if ($encoder = odbc_fetch_array($encoder_stmt)) {
                $realname = htmlspecialchars($encoder["c_realname"]);
            }
        ?>
        <input type="text" name="c_encoded_by" id="c_encoded_by" value="<?php echo $realname; ?>">
        <input type="text" name="c_remarks" id="c_remarks" value="<?php echo htmlspecialchars($c_remarks); ?>">
        <input type="text" name="c_paydate_main" id="c_paydate_main" value="<?php echo $c_or_paydate; ?>">
    </div>
    <input type="text" name="c_acc_no" id="c_acc_no" value="<?php echo $c_account_no; ?>">
        <input type="text" name="c_or_type" id="c_or_type" value="<?php echo htmlspecialchars($row['c_or_type']); ?>">
        <input type="text" name="c_or_amount" id="c_or_amount" value="<?php echo number_format($row['c_or_amount'], 2); ?>">
        <textarea name="c_or_amount_words" id="c_or_amount_words"></textarea>
        
        <!-- <input type="text" name="c_or_no" id="c_or_no" value="<?php echo htmlspecialchars($row['c_or_no']); ?>"> -->
        
        <?php $c_mop = isset($row['c_mop']) ? $row['c_mop'] : 0; ?>
        <div class="dynamic-margin" id="dynamicMarginDiv">
            <input type="text" id="c_mop" value="<?php echo number_format($row['c_or_amount'], 2); ?>">
            <input type="hidden" id="c_mop_value" value="<?php echo ($row['c_mop']); ?>">
        </div>

        

    <script>
        var cMopValue = document.getElementById('c_mop_value').value;
        var cPayDateField = document.getElementById('c_paydate');
        var dynamicMarginDiv = document.getElementById('dynamicMarginDiv');

        if (cMopValue == '1') {
            dynamicMarginDiv.style.marginTop = '195px';
            cPayDateField.style.display = 'none';
        } else {
            dynamicMarginDiv.style.marginTop = '210px';
            cPayDateField.style.display = 'block';
        }
    </script>
    <script>
        function initializePage() {
            adjustTextArea('c_received');
            convertCarAmountToWords();
            window.print(); 
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

        function convertCarAmountToWords() {
            var carAmountElement = document.getElementById("c_or_amount");
            var carAmountWordsElement = document.getElementById("c_or_amount_words");

            var carAmount = parseFloat(carAmountElement.value.replace(/,/g, ''));
            var carAmountWords = convertToWords(carAmount);

            carAmountWordsElement.value = carAmountWords;

            var lineHeight = parseInt(window.getComputedStyle(carAmountWordsElement).lineHeight);
            var lines = carAmountWordsElement.scrollHeight / lineHeight;
            var fontSize = 15;
            if (lines > 1) {
                fontSize -= (lines - 1) * 2;
            }
            carAmountWordsElement.style.fontSize = fontSize + "px";
        }
    </script>
</body>
</html>

<?php
    }
}
?>
