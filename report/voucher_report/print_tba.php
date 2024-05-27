<?php require ('../../config.php'); ?>
<?php include "../../inc/header.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
	<script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Armata&display=swap" rel="stylesheet"> -->
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
    $qry = $conn->query("SELECT * FROM tbl_tba WHERE tba_no = '{$_GET['id']}' ");
    $row = $qry->fetch_assoc();
    $tba_no = $row['tba_no'];
    $description = $row['description'];
    $amt = $row['amount'];
    $acc_person = $row['acc_person'];
    $date_needed= $row['date_needed'];
    $remarks = $row['remarks'];
    $payment_form = $row['payment_form'];
    $req_dept = $row['req_dept'];
    $stats1 = $row['status1'];
    $stats2 = $row['status2'];
    $stats3 = $row['status3'];
    $stats4 = $row['status4'];
    $stats5 = $row['status5'];
    $stats6 = $row['status6'];
    $stats7 = $row['status7'];
    $preparer = $row['preparer'];
?>
<style>
.phase {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border:none;
    background-size: 15px; 
    background-position: right center;
    background-repeat: no-repeat;
    width:auto;
}
.txtborder{
    border-bottom:solid 1px black !important;
}
#amountToWords{
    text-transform: uppercase;
}

</style>
<table class="report-container" style="margin-top:-10px;width:100%;">
    <thead class="report-header">
        <tr>
            <th class="report-header-cell">
                <div class="header-info">
                    <img src="../images/Header.jpg" class="img-thumbnail" style="margin-left:120px;height:110px;width:750px;border:none;margin-bottom:-5px;z-index:-1;position:relative;margin-bottom:-35px;" alt="">
                    <h6 style="margin-top:-25px;margin-left:335px;font-weight:normal;">Grand Royale Subdivision, Bulihan, Malolos City, Bulacan, Philippines</h6>

                    <h4 style="margin-top:20px;margin-left:450px;font-weight:bold;font-family: 'Armata', sans-serif;">REQUEST FOR TBA</h4>
                    <div class="container" style="margin-top:15px;">
                    <table class="table" style="border: none!important;">
                            <tr style="border: none!important;">
                                <td style="border: none!important;">
                                    <div style="float:left;">TBA #: <input type="text" value="<?php echo $tba_no; ?>" style="text-align:center;border:none;" class="txtborder"></div>
                                </td>
                                <td style="border: none!important;">
                                    <div class="date" style="float:right;">Date: <input type="text" value="<?php echo date('Y-m-d'); ?>" style="text-align:center;border:none;" class="txtborder"></div>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <td style="width:10%;font-weight:bold; padding: 4px 10px;">Particulars:</td>
                                <td style="width:50%; padding: 4px 10px;"><?php echo $description; ?></td>
                            </tr>
                            <tr>
                                <td style="width:10%;font-weight:bold; padding: 4px 10px;">Amount:</td>
                                <td style="width:50%; padding: 4px 10px;"><?php echo number_format($amt,2); ?></td>
                            </tr>
                            <div class="col-md-12 form-group">
                            <tr>
                                <td style="width:10%;font-weight:bold; padding: 4px 10px;">Accountable Person:</td>
                                <td style="width:50%; padding: 4px 10px;"><?php echo $acc_person; ?></td>
                            </tr>
                            <tr>
                                <td style="width:10%;font-weight:bold; padding: 4px 10px;">Amount to Words:</td>
                                <input type="hidden" name="amount" id="amount" class="form-control rounded-0" value="<?php echo $amt; ?>" required>
                                <td style="width:50%; padding: 4px 10px;"><div id="amountToWords" class="text-display" style="font-weight:bold;"></div></td>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <td><b>Requesting Department:</b></td><td><?php echo $req_dept; ?></td>
                                <tr>
                                <td>
                                    <b>Preparer:</b>
                                </td>
                                <td>
                                    <?php
                                    $prep = $conn->query("SELECT * FROM users WHERE user_code = '" . $preparer . "'");
                                    while($row2 = $prep->fetch_assoc()): ?>
                                    
                                    <?php echo $row2['firstname']; ?> <?php echo $row2['lastname']; ?>
                                    <?php endwhile; ?>
                                </td>
                            </tr>
                            </tr>
                        </table>
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <b>Payment Form:</b></td><td>
                                    <?php echo $payment_form == 0 ? '<input type="checkbox" checked disabled> Cash' : '<input type="checkbox" disabled> Cash'; ?>
                                    <?php echo $payment_form == 1 ? '<input type="checkbox" checked disabled> Check' : '<input type="checkbox" disabled> Check'; ?>
                                </td>
                                <td><b>Date Needed:</b></td><td><?php echo $date_needed; ?></td>
                            </tr>
                        </table>
                        
                        <table class="table table-bordered">
                            <tr>
                                <td style="width:10%;font-weight:bold; padding: 4px 10px;text-align:center;">Remarks</td>
                            </tr>
                            <tr>
                                <td style="width:10%;padding: 4px 10px;text-align:center;"><?php echo $remarks; ?></td>
                            </tr>
                        </table>
    
                        <table>
                            <tr>
                                <i>Approved by:</i>
                            </tr>
                        </table>
                        <table class="table table-bordered" style="text-align:center;">
                        <tr>
                        <?php
                        for ($i = 1; $i <= 7; $i++) {
                            $statusField = $row['status' . $i];

                            if (!empty($statusField)) {
                                $qry = $conn->query("SELECT * FROM users WHERE user_code = '" . $statusField . "' ");
                                
                                $statusRow = $qry->fetch_assoc();
                                if ($statusRow) {
                                    $lastname = $statusRow['lastname'];
                                    $firstname = $statusRow['firstname'];
                                    ?>
                                                <td><?php echo $statusRow['firstname'] . ' ' . $statusRow['lastname']; ?></td>
                                    <?php
                                            } else {
                                    ?>
                                    <?php
                                            }
                                        } else {
                                    ?>
                                           
                                    <?php
                                        }
                                    }
                                    ?>
                            </tr>
                            <tr>
                                <?php
                                for ($x = 1; $x <= 7; $x++) {
                                    $statusField1 = $row['status' . $x];

                                    if (!empty($statusField1)) {
                                        $qry = $conn->query("SELECT * FROM tbl_tba_approvals WHERE tba_no = '" . $tba_no . "' ");
                                        
                                        $statusRow1 = $qry->fetch_assoc();
                                        if ($statusRow1) {
                                            $statusValue = $statusRow1['status' . $x];
                                            $statusText = ''; 
                                            if ($statusValue == 1) {
                                                $statusText = '<strong>Approved</strong>';
                                            } elseif ($statusValue == 2) {
                                                $statusText = '<strong>Disapproved</strong>';
                                            } else {
                                                $statusText = '<strong>Pending</strong>';
                                            }
                                            ?>
                                            <td><?php echo $statusText; ?></td>
                                            <?php
                                        } else {
                                            ?>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <?php
                                    }
                                }
                                ?>
                            </tr>
                        </table>
                        </div>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
</table>
</body>

<script type="text/javascript">
	function PrintPage() {
		window.print();
	}
	    document.loaded = function(){
	}
	window.addEventListener('DOMContentLoaded', (event) => {
   		PrintPage()
		setTimeout(function(){ window.close() },750)
	});
</script>
<script>
 document.addEventListener('DOMContentLoaded', function() {
        initialize();
    });

    function initialize() {
        var amountElement = document.getElementById('amount');
        amountElement.addEventListener('input', function() {
            var amount = this.value.trim();
            if (amount === '') {
                document.getElementById('amountToWords').innerText = '';
                return;
            }

            var amountInWords = convertAmountToWords(amount);
            document.getElementById('amountToWords').innerText =  amountInWords;
        });
        var initialAmount = amountElement.value.trim();
        if (initialAmount !== '') {
            var amountInWords = convertAmountToWords(initialAmount);
            document.getElementById('amountToWords').innerText = amountInWords;
        }
    }
    function convertAmountToWords(amount) {
        amount = amount.replace(/,/g, '');
        amount = parseFloat(amount).toFixed(2);
        var numArr = amount.split('.');

        var wholePart = numArr[0];
        var decimalPart = numArr[1] || '00';

        var words = convertToWords(wholePart);

        if (decimalPart != '00') {
            words += ' pesos and ' + convertToWords(decimalPart) + ' centavos ONLY';
        } else {
            words += ' pesos ONLY';
        }

        return words;
    }

    function convertToWords(number) {
        var units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        var teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        var tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        var words = '';

        if (number > 999999) {
            words += convertToWords(Math.floor(number / 1000000)) + ' Million ';
            number %= 1000000;
        }

        if (number > 999) {
            words += convertToWords(Math.floor(number / 1000)) + ' Thousand ';
            number %= 1000;
        }

        if (number > 99) {
            words += units[Math.floor(number / 100)] + ' Hundred ';
            number %= 100;
        }

        if (number > 10 && number < 20) {
            words += teens[number - 10];
        } else {
            words += tens[Math.floor(number / 10)] + ' ' + units[number % 10];
        }

        return words.trim();
    }

    document.getElementById('amount').addEventListener('input', function() {
        var amount = this.value.trim();
        if (amount === '') {
            document.getElementById('amountToWords').innerText = '';
            return;
        }

        var amountInWords = convertAmountToWords(amount);
      
        document.getElementById('amountToWords').innerText =  amountInWords;
    });
</script>
</html>

