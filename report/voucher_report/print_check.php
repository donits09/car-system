<?php require ('../../config.php'); ?>
<?php include "../../inc/header.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
$qry = $conn->query("SELECT * FROM check_details WHERE check_id = '{$_GET['id']}'");
$row = $qry->fetch_assoc();
$check_num = $row['check_num'];
$name = $row['check_name'];
$amount = $row['amount'];
$checkdate = date('m-d-Y', strtotime($row['check_date']));
function convertAmountToWords($amount) {
    $number = number_format($amount, 2, '.', '');
    $numArr = explode('.', $number);

    $wholePart = $numArr[0];
    $decimalPart = isset($numArr[1]) ? $numArr[1] : '00';

    $words = convertToWords($wholePart);

    if ($decimalPart != '00') {
        $words .= ' pesos and ' . convertToWords($decimalPart) . ' centavos ONLY';
    } else {
        $words .= ' pesos ONLY';
    }

    return $words;
}

function convertToWords($number) {
    $units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    $teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    $tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    $words = '';

    if ($number > 999999) {
        $words .= convertToWords(floor($number / 1000000)) . ' Million ';
        $number %= 1000000;
    }

    if ($number > 999) {
        $words .= convertToWords(floor($number / 1000)) . ' Thousand ';
        $number %= 1000;
    }

    if ($number > 99) {
        $words .= $units[floor($number / 100)] . ' Hundred ';
        $number %= 100;
    }

    if ($number > 10 && $number < 20) {
        $words .= $teens[$number - 10];
    } else {
        $words .= $tens[floor($number / 10)] . ' ' . $units[$number % 10];
    }

    return rtrim($words);
}

$amountInWords = convertAmountToWords($amount);
//echo $amountInWords;
?>

<style>
@font-face {
    font-family: 'MICRFont';
    src: url('../images/micr-encoding.regular.ttf') format('truetype');
}
.img-thumbnail {
    margin-left: 0px;
    height: 625px;
    width: 1600px;
    border: none;
    margin-bottom: -5px;
    z-index: -1;
    position: relative;
    margin-bottom: -35px;
}
#checkname {
    text-transform: uppercase;
    font-size: 28px;
    font-weight: bold;
    margin-left: 190px;
    position: absolute;
    z-index: 1;
    word-wrap: break-word;
    width: 900px;
    text-align: center;
}
#check_date {
    position: relative; 
    top: -500px; 
    left:1180px;
    z-index: 1;
    font-weight: bold;
    font-family: 'MICRFont', sans-serif;
    letter-spacing: 15px;
    width:auto;
}
#amount {
    position: relative; 
    top: -500px; 
    left:1120px;
    z-index: 1;
    font-weight: bold;
    font-family: 'MICRFont', sans-serif;
    font-size:40px;
    width:390px;
    text-align: center;
}
.text-display {
    border: none;
    background-color: transparent;
    position: absolute;
    font-size: 40px;
  }
  #amountToWords {
    font-size: 28px;
    font-weight: bold;
    top: -500px; 
    left: 125px;
    position: relative; 
    z-index: 1;
    word-wrap: break-word;
    width: 1400px; 
    text-align: center;
}
</style>

<img src="../images/check_format.png" class="img-thumbnail" alt="">
<div id="checkname" class="text-display">**<?php echo $name; ?>**</div>
<div id="check_date" class="text-display"><?php echo $checkdate; ?></div>
<div id="amount" class="text-display">**<?php echo number_format($amount, 2); ?>**</div>
<div id="amountToWords" class="text-display">**<?php echo $amountInWords; ?>**</div>

</body>
<script type="text/javascript">
    function PrintPage() {
        window.print();
    }

    document.addEventListener('DOMContentLoaded', function () {
        PrintPage();
        setTimeout(function () { window.close() }, 750);
    });
    document.addEventListener("DOMContentLoaded", function() {
    var checknameElement = document.getElementById("checkname");

    var numRows = Math.ceil(checknameElement.clientHeight / parseInt(getComputedStyle(checknameElement).lineHeight));

    if (numRows === 2) {
        checknameElement.style.marginTop = "-460px";
        checknameElement.style.fontSize = "25px";
    } else {
        checknameElement.style.marginTop = "-430px";
    }
    
});
document.addEventListener("DOMContentLoaded", function() {
    var amountToWordsElement = document.getElementById("amountToWords");

    var numRows = Math.ceil(amountToWordsElement.clientHeight / parseInt(getComputedStyle(amountToWordsElement).lineHeight));

    if (numRows === 2) {
        amountToWordsElement.style.top = "-500px";
        amountToWordsElement.style.fontSize = "25px";
    } else {
        amountToWordsElement.style.top = "-480px";
    }
});
</script>
</html>
