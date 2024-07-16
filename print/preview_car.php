<?php
include('../config.php');

$c_account_no = $_GET['c_account_no'] ?? '';
$c_car_no = $_GET['c_car_no'] ?? '';
$c_car_type = $_GET['c_car_type'] ?? '';
$c_car_amount = $_GET['c_car_amount'] ?? '';
$c_car_paydate = $_GET['c_car_paydate'] ?? '';
$c_encoded_by = $_GET['c_encoded_by'] ?? '';
$c_mop = $_GET['c_mop'] ?? '';
$c_bank_check = $_GET['c_bank_check'] ?? '';
$c_bank_online = $_GET['c_bank_online'] ?? '';
$c_check_no = $_GET['c_check_no'] ?? '';
$c_ref_no = $_GET['c_ref_no'] ?? '';

$c_bank = '';
$c_check = '';

if ($c_bank_check == '' || $c_bank_check == null){
    $c_bank = $c_bank_online;
}else{
    $c_bank = $c_bank_check;
}

if ($c_check_no == '' || $c_check_no == null){
    $c_check = $c_ref_no;
}else{
    $c_check = $c_check_no;
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

$buyerDetails = fetchBuyerDetails($conn, $c_account_no);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../dist/css/car_preview.css">
    <style>
        .small-font {
            font-size: 12px;
            white-space: pre-wrap; 
        }
        .normal-font {
            font-size: 18px!important;
        }
        textarea {
            width: 100%;
            overflow: hidden; 
            resize: none; 
        }
    </style>
</head>
<body onload="initializePage()">
    <img src="<?php echo base_url; ?>images/car.jpg" class="background-image" alt="Car Scanned Copy">
    <div class="container">
        <div class="box_middle">
            <input type="text" name="c_current_date" id="c_current_date" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <input type="text" name="c_acc_no" id="c_acc_no" value="<?php echo htmlspecialchars($c_account_no); ?>">
        <input type="text" name="c_car_type" id="c_car_type" value="<?php echo htmlspecialchars($c_car_type); ?>">
        <input type="text" name="c_car_amount" id="c_car_amount" value="<?php echo number_format((float)str_replace(',', '', $c_car_amount), 2); ?>">
        <textarea name="c_car_amount_words" id="c_car_amount_words"></textarea>
        <input type="text" name="c_car_no" id="c_car_no" value="<?php echo htmlspecialchars($c_car_no); ?>">
        
        <?php $c_mop = $c_mop ?? 0; ?>
        <div class="dynamic-margin" id="dynamicMarginDiv">
            <input type="text" id="c_mop" value="<?php echo number_format((float)str_replace(',', '', $c_car_amount), 2); ?>">
            <input type="hidden" id="c_mop_value" value="<?php echo $c_mop; ?>">
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
        <?php } ?>

        <?php
        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = ?";
        $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
        odbc_execute($encoder_stmt, array($c_encoded_by));
        if ($encoder = odbc_fetch_array($encoder_stmt)) {
            $realname = htmlspecialchars($encoder["c_realname"]);
        }
        ?>
        <input type="text" name="c_encoded_by" id="c_encoded_by" value="<?php echo $realname; ?>">
        <input type="text" name="c_paydate" id="c_paydate" value="<?php echo htmlspecialchars($c_car_paydate); ?>">
        <input type="text" name="c_bank_main" id="c_bank_main" value="<?php echo htmlspecialchars($c_bank); ?>">
        <input type="text" name="c_check_main" id="c_check_main" value="<?php echo htmlspecialchars($c_check); ?>">
    </div>
    <script>
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
            cPayDateField.style.display = 'block';
            cBankCheck.style.display = 'block';
            cCheckNo.style.display = 'block';
        }
    </script>
    <script>
        function initializePage() {
            adjustTextArea('c_received');
            convertCarAmountToWords();
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
            var carAmountElement = document.getElementById("c_car_amount");
            var carAmountWordsElement = document.getElementById("c_car_amount_words");

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
</body>
</html>
