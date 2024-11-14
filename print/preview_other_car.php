<?php
include('../config.php');

$c_car_no = $_GET['c_car_no'] ?? '';
$c_car_type = $_GET['atap_val'] ?? '';
$c_car_amount = $_GET['c_car_amount'] ?? '0.00';
$c_car_paydate = $_GET['c_car_paydate'] ?? date('Y-m-d');
$c_encoded_by = $_GET['c_encoded_by'] ?? '';
$c_name = $_GET['c_name'] ?? '';
$c_mop = $_GET['c_mop'] ?? '';
$c_bank_check = $_GET['c_bank_check'] ?? '';
$c_bank_online = $_GET['c_bank_online'] ?? '';
$c_bank_voucher = $_GET['c_bank_voucher'] ?? '';
$c_check_no = $_GET['c_check_no'] ?? '';
$c_ref_no = $_GET['c_ref_no'] ?? '';
$c_voucher_no = $_GET['c_voucher_no'] ?? '';
$c_remarks = $_GET['c_remarks'] ?? '';

$c_bank = '';
$c_check = '';

if (($c_bank_check == '' || $c_bank_check == null) && ($c_bank_online == '' || $c_bank_online == null)) {
    $c_bank = $c_bank_voucher;
} elseif ($c_bank_check == '' || $c_bank_check == null) {
    $c_bank = $c_bank_online;
} else {
    $c_bank = $c_bank_check;
}


if (($c_check_no == '' || $c_check_no == null) && ($c_ref_no == '' || $c_ref_no == null)){
    $c_check = $c_voucher_no;
} elseif ($c_check_no == '' || $c_check_no == null){
    $c_check = $c_ref_no;
} else{
    $c_check = $c_check_no;
}

$realname = '';
$get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = ?";
$encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
odbc_execute($encoder_stmt, array($c_encoded_by));
if ($encoder = odbc_fetch_array($encoder_stmt)) {
    $realname = htmlspecialchars($encoder["c_realname"]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- <link rel="stylesheet" href="../dist/css/car_preview.css"> -->
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
            height: 280px;
            width: 670px;
            z-index: 1;
            object-fit: cover;
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
        #c_current_date {
            float: right;
            margin-top: 83px;
            margin-right: -150px;
        }
        #c_car_type {
            float: right;
            margin-top: 190px;
            margin-right: -180px;
            width: 300px;
        }
        #c_car_amount {
            float: right;
            margin-top: 165px;
            margin-right: -300px;
            width: 140px;
        }
        #c_car_amount_words {
            float: right;
            margin-top: 150px;
            margin-right: -290px;
            width: 340px;
            height: auto;
            line-height: 1.2em;
            overflow: hidden;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        #c_car_no {
            float: right;
            margin-top: 62px;
            margin-right: -360px;
            width: 80px;
        }
        #c_received {
            text-transform: uppercase!important;
            float: right;
            margin-top: 100px;
            margin-right: -380px;
            width: 350px;
            padding:0px;
        }
        #c_address {
            text-transform: uppercase;
            float: right;
            margin-top: 120px;
            margin-right: -350px;
            width: 360px;
            text-align: center;
        }
        #c_encoded_by {
            text-transform: uppercase!important;
            float: left;
            margin-top: 5px;
            width: auto;
            margin-left:470px;
            text-align: center;
            font-size: 10px !important;
        }
        #c_paydate {
            float: left;
            margin-top: -5px;
            width: 150px;
            margin-left:70px;
            text-align: center;
            font-size: 10px !important;
        }
        #c_loc{
            text-transform: uppercase;
            float:left;
            margin-top: -45px;
            margin-right: 145px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
        }
        #c_acc_no{
            text-transform: uppercase;
            float: right;
            margin-top: 80px;
            margin-right: 100px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        #c_bank{
            margin-top: 150px;
            margin-right: 100px;
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
        #c_name {
            text-transform: uppercase;
            float: right;
            margin-top: 100px;
            margin-right: -380px;
            width: 350px;
            padding:0px;
        }
        #c_phase{
            text-transform: uppercase;
            float:left;
            margin-top: -800px;
            margin-right: 145px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
        }
        #location{
            text-transform: uppercase;
            margin-top: 100px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
        }
        #c_amount_words {
            float: right;
            margin-top: 140px;
            margin-right: -290px;
            width: 340px;
            height: auto;
            line-height: 1.2em;
            overflow: hidden;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        #c_paydate_prev {
            float: left;
            margin-top: 0px;
            width: 150px;
            margin-left:70px;
            text-align: center;
            font-size: 10px !important;
        }
        #c_bank{
            margin-top: 130px;
            margin-left: -160px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        #c_check{
            margin-top:160px;
            margin-left:-160px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        .btn-container {
            display: flex;
            float: right;
        }
        #btnSave {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 8px;
            border-radius: 5px;
            margin-top: 70px;
            font-size: 12px;
        }      
        #c_remarks{
            width: 440px;
            height:auto;
            text-align: center;
            font-size: 12px !important;
            margin-top:225px;
            position:absolute;
            margin-left:-100px;
        }
    </style>
</head>
<body onload="convertCarAmountToWords()" id="previewCarContent">
    <img src="<?php echo base_url; ?>images/car.jpg" class="background-image" alt="Car Scanned Copy">
    <div class="container">
        <div class="box_middle">
            <input type="text" name="c_current_date" id="c_current_date" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <input type="text" name="c_car_type" id="c_car_type" value="<?php echo htmlspecialchars($c_car_type); ?>">
        <input type="text" name="c_car_amount" id="c_car_amount" value="<?php echo number_format((float)str_replace(',', '', $c_car_amount), 2); ?>">
        <textarea name="c_amount_words" id="c_amount_words"></textarea>
        <input type="text" name="c_car_no" id="c_car_no" value="<?php echo htmlspecialchars($c_car_no); ?>">

        <?php $c_mop = $c_mop ?? 0; ?>
        <div class="dynamic-margin" id="dynamicMarginDiv">
            <input type="text" id="c_mop" value="<?php echo number_format((float)str_replace(',', '', $c_car_amount), 2); ?>">
            <input type="hidden" id="c_mop_value" value="<?php echo $c_mop; ?>">
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

        <input type="text" name="c_encoded_by" id="c_encoded_by" value="<?php echo $realname; ?>">
        <input type="text" name="c_remarks" id="c_remarks" value="<?php echo htmlspecialchars($c_remarks); ?>">
        <input type="text" name="c_paydate_prev" id="c_paydate_prev" value="<?php echo htmlspecialchars($c_car_paydate); ?>">
        <input type="text" name="c_bank" id="c_bank" value="<?php echo htmlspecialchars($c_bank); ?>">
        <input type="text" name="c_check" id="c_check" value="<?php echo htmlspecialchars($c_check); ?>">
    </div>
    <!-- <div class="btn-container">
        <button type="button" class="btn btn-primary" onclick="saveAsImage()" id="btnSave">Save as PNG</button> 
    </div> -->
    <script>
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
            var carAmountWordsElement = document.getElementById("c_amount_words");

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
    <script>
        var cMopValue = document.getElementById('c_mop_value').value;
        var cBankCheck = document.getElementById('c_bank').value;
        var cCheckNo = document.getElementById('c_check').value;
        var cPayDateField = document.getElementById('c_paydate_prev');
        var dynamicMarginDiv = document.getElementById('dynamicMarginDiv');

        if (cMopValue == '1') {
            dynamicMarginDiv.style.marginTop = '190px';
            cPayDateField.style.display = 'none';
            cBankCheck.style.display = 'none';
            cCheckNo.style.display = 'none';
        } else if (cMopValue == '3') {
            dynamicMarginDiv.style.marginTop = '190px';
            cPayDateField.style.display = 'block';
            cBankCheck.style.display = 'none';
            cCheckNo.style.display = 'none';
        } else if (cMopValue == '4') {
            dynamicMarginDiv.style.marginTop = '190px';
            cPayDateField.style.display = 'block';
            cBankCheck.style.display = 'block';
            cCheckNo.style.display = 'block';
        } else {
            dynamicMarginDiv.style.marginTop = '205px';
            cPayDateField.style.display = 'block';
            cBankCheck.style.display = 'block';
            cCheckNo.style.display = 'block';
        }

        // function initializePage() {
        //     adjustTextArea('c_received');
        //     convertCarAmountToWords();
        // }
        
        function adjustTextArea(id) {
            var receivedField = document.getElementById(id);
            if (receivedField.value.length > 35) {
                receivedField.classList.add('small-font');
                receivedField.value = receivedField.value.match(/.{1,55}/g).join('\n');
            } else {
                receivedField.classList.add('normal-font');
            }
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
    <script>
        function saveAsImage() {
            var btnSave = document.getElementById('btnSave');
            
            btnSave.style.display = 'none';

            var carNumber = document.getElementById('c_car_no').value || 'unknown_car';

            var today = new Date().toISOString().split('T')[0];

            var filename = 'CAR' + carNumber + '_' + today + '.png';

            html2canvas(document.getElementById('previewCarContent'), {
                scale: window.devicePixelRatio, 
                useCORS: true, 
                logging: true, 
            }).then(function(canvas) {
                var link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = filename;
                link.click();
                btnSave.style.display = 'block';
            }).catch(function(error) {
                console.error('Error saving image:', error);

                btnSave.style.display = 'block';
            });
        }
    </script>
    <script>
    window.addEventListener('load', function() {
        initializePage();
    });
</script>
<script src="<?php echo base_url; ?>dist/header_files/html2canvas.min.js_0.5.0-beta4/cdnjs/html2canvas.min.js"></script>
</body>
</html>
