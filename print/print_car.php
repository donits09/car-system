<?php

include('../config.php');
require_once('../classes/Master.php');
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
function fetchCarDetails($conn, $carNo) {
    $query = "SELECT * FROM t_other_car_payment WHERE c_car_no = ?";
    $stmt = odbc_prepare($conn, $query);
    if (odbc_execute($stmt, array($carNo))) {
        return odbc_fetch_array($stmt);
    }
    return false;
}
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $accountId = $_GET['id'];
    $get_car_query = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                      a.c_car_paydate,a.c_car_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop, b.c_name, b.c_phase,
                      b.c_block, b.c_lot, a.c_car_paydate, a.c_bank, a.c_check_no, a.c_remarks
                      FROM t_car_payment a
                      LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                      WHERE a.c_car_no = ?";
    $stmt = odbc_prepare($conn, $get_car_query);
    odbc_execute($stmt, array($accountId));
    $result = odbc_fetch_array($stmt);
    if ($result) {
        $row = $result;
        $c_account_no = $row['c_account_no'];
        $c_car_paydate = $row['c_car_paydate'];
        $buyerDetails = fetchBuyerDetails($conn, $c_account_no);
        $carDetails = fetchCarDetails($conn, $row['c_car_no']);
        $c_remarks = $row['c_remarks'];

        $master = new Master();
        $module = "Car Print Management";
        $notes = "PRINT CAR - " . "ACCT#" . $row['c_account_no'] . "  CAR#" . $row['c_car_no'];
        $master->car_logs($module, $notes);

        ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../dist/css/car_print.css">
    <style>
        body {
            position: relative;
            font-size: 8px !important;
        }
        .container {
            margin-top:-25px;
            margin-left: -40px;
            position: relative;
            width: 500px;
            padding: 20px;
            box-sizing: border-box;
            z-index: 2; 
        }
        .background-image {
            position: absolute;
            top: -10px;
            left: 0;
            height: 400px;
            width: 750px;
            z-index: 1;
            object-fit: cover;
            display:none;
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
            font-size: 12px !important;
            resize: none;
            overflow: hidden;
        }
        #c_car_amount_words {
            float: right;
            margin-top: 47px;
            margin-right: 95px;
            width: 340px;
            height: auto;
            line-height: 1.2em;
            overflow: hidden;
            white-space: pre-wrap;
            word-wrap: break-word;
          
        }
        /* #c_car_no {
            float: right;
            margin-top: 60px;
            margin-right: -410px;
            width: 80px;
        } */
        #c_received {
            text-transform: uppercase;
            float: right;
            margin-top: -270px;
            margin-right: -5px;
            width: 350px;
            padding:0px;
        }
        #c_address {
            text-transform: uppercase;
            float: right;
            margin-top: -245px;
            margin-right: -10px;
            width: 360px;
            text-align: center;
        }
        #c_current_date {
            float: right;
            margin-top: 75px;
            margin-right: -240px;
        }
        #c_acc_no{
            text-transform: uppercase;
            margin-top: 110px;
            margin-left:555px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
            
        }
        #c_encoded_by {
            text-transform: uppercase;
            float: left;
            margin-top: -75px;
            width: auto;
            margin-left:585px;
            text-align: center;
            font-size: 10px !important;
        }
        #c_loc{
            text-transform: uppercase;
            float:right;
            margin-top: -255px;
            margin-right: -250px;
            width: auto;
            text-align: center;
            font-size: 12px !important;
        }
        .dynamic-margin {
            width: 100px;
            height: 100px;
            margin-left:70px;
            position:absolute;
        }
        #c_bank{
            margin-top: -15px;
            width: auto;
            float:right;
            margin-left:290px;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
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
        #c_check_main{
            margin-top: -20px;
            width: 200px;
            text-align: center;
            font-size: 12px !important;
            position:absolute;
        }
        #c_car_type {
            /* float: right; */
            margin-top: 170px;
            margin-right: 100px;
            width: 400px;
            position:absolute;
            text-align: center;
        }
        #c_remarks{
            margin-top: -75px;
            margin-right: 100px;
            width: 400px;
            text-align: center;
            font-size: 12px !important;
            position: absolute;
        }
        #c_paydate_main {
            float: left;
            margin-top: -105px;
            width: auto;
            margin-left:170px;
            text-align: center;
            font-size: 10px !important;
        }
        #c_car_no {
            float: right;
            margin-top: 45px;
            margin-right: -430px;
            width: 80px;
        }
        #c_mop{
            margin-left:605px;
            margin-top:250px;
            position:relative;
        }
        #c_car_amount {
            float: right;
            margin-top: 165px;
            margin-right: -290px;
            /* width: 140px; */
        }
        #check_sym{
            width:auto;
            height:auto;
            position:absolute;
        }
    </style>
</head>
<body onload="initializePage()">
    <img src="<?php echo base_url ?>images/new_car.png" class="background-image" alt="Car Scanned Copy">
         <!-- <img src=""> -->
    <div class="container">
        <div class="box_middle">
            <input type="text" name="c_current_date" id="c_current_date" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <input type="text" name="c_acc_no" id="c_acc_no" value="<?php echo $c_account_no; ?>">
        <input type="text" name="c_car_type" id="c_car_type" value="<?php echo htmlspecialchars($row['c_car_type']); ?>">
        <input type="text" name="c_car_amount" id="c_car_amount" value="<?php echo number_format($row['c_car_amount'], 2); ?>">
        <input type="text" id="c_mop" value="<?php echo number_format($row['c_car_amount'], 2); ?>">
        <input type="hidden" id="c_mop_value" value="<?php echo ($row['c_mop']); ?>">
        
        <textarea name="c_car_amount_words" id="c_car_amount_words"></textarea>
        <input type="hidden" id="c_mop_value" value="<?php echo $row['c_mop']; ?>">
        <input type="text" name="c_paydate_main" id="c_paydate_main" value="<?php echo $c_car_paydate; ?>">
        <input type="text" id="check_sym" value="✓">
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const mopValue = document.getElementById("c_mop_value").value;
                const paydateMain = document.getElementById("c_paydate_main");
                const bankMain = document.getElementById("c_bank");
                const checkMain = document.getElementById("c_check_main");
                const checksym = document.getElementById("check_sym");
             
                if (mopValue === "1") {
                    paydateMain.style.display = "none";
                    checksym.style.marginTop = "-30px";
                    checksym.style.marginLeft = "5px";
                }else if(mopValue === "3"){
                    paydateMain.style.display = "block";
                    paydateMain.style.marginTop = "-75px";
                    bankMain.style.marginTop = "5px";
                    checkMain.style.marginTop = "0px";
                    checkMain.style.fontSize = "8px";
                    checksym.style.marginTop = "0px";
                    checksym.style.marginLeft = "8px";
                } else if(mopValue === "4"){
                    paydateMain.style.display = "block";
                    checkMain.style.display = "block";
                    paydateMain.style.marginTop = "-68px";
                    paydateMain.style.marginLeft = "50px";
                    bankMain.style.marginTop = "10px";
                    bankMain.style.marginLeft = "-10px";
                    checkMain.style.marginTop = "10px";
                    checkMain.style.fontSize = "8px";
                    checkMain.style.marginLeft = "90px";
                    checksym.style.marginTop = "10px";
                    checksym.style.marginLeft = "8px";
                }else {
                    paydateMain.style.display = "block";
                    paydateMain.style.marginTop = "-100px";
                    checksym.style.marginTop = "-15px";
                    checksym.style.marginLeft = "8px";
                }       
            });
        </script>

        

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
        <?php } else if ($carDetails) {
            $car_no = $carDetails["c_car_no"];
            $c_name = $carDetails["c_name"];
            $c_phase = $row['c_phase'];
            $c_block = $row['c_block'];
            $c_lot = $row['c_lot'];
            $loc ="";

            if (empty($c_phase) && empty($c_block) && empty($c_lot)) {
            } else {
                if (!empty($c_phase)) {
                    $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                    $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);
            
                    if (odbc_execute($phase_stmt, array($c_phase))) {
                        $phase_details = odbc_fetch_array($phase_stmt);
            
                        if ($phase_details) {
                            $loc = $phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot;
                        } else {
                            $loc = '----- B' . $c_block . ' L' . $c_lot;
                        }
                    }
                } else {
                    $loc = '----- B' . $c_block . ' L' . $c_lot;
                }
            }
        ?>
            <!-- <input type="text" name="c_car_no" id="c_car_no" value="<?php echo htmlspecialchars($row['c_car_no']); ?>"> -->
            <textarea name="c_received" id="c_received"><?php echo htmlspecialchars($c_name); ?></textarea>
            <textarea name="c_address" id="c_address">-----------------</textarea>
            <textarea name="c_loc" id="c_loc"><?php echo $loc; ?></textarea>
            <input type="text" name="c_bank" id="c_bank" value="<?php echo $row['c_bank']; ?>">
            <input type="text" name="c_check_main" id="c_check_main" value="<?php echo $row['c_check_no']; ?>">
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
    </div>
   
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
    </script>
</body>
</html>

<?php
    }
}
?>
