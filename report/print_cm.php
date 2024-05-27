<?php require_once('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js">

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Armata&display=swap" rel="stylesheet">
    <script src="../build/js/jquery.min.js" type="text/javascript"></script>
    <script src="../build/js/num-to-words.js" type="text/javascript"></script>
</head>
<?php $qry = $conn->query("SELECT x.*,y.*,z.* FROM t_credit_memo x inner join property_payments
y on x.reference = y.or_no inner join property_clients z on y.property_id = z.property_id where x.reference = '{$_GET['id']}' ");
$row = $qry->fetch_assoc();
$cm_id = $row['cm_id'];
$client_id = md5($row['property_id']);
$property_id = $row['property_id'];
$reason = $row['reason'];
$p_last_name = $row['last_name'];
$p_first_name = $row['first_name'];
$p_middle_name = $row['middle_name'];
$p_address = $row['address'];
$p_zipcode = $row['zip_code'];
$memo_stats= $row['memo_status'];
$credit_amt = $row['credit_amount'];
$or_no= $row['or_no'];
$acr = substr($property_id, 2, 3);
$blk = substr($property_id, 5, 3);
$lot = substr($property_id, 8, 2); ?>
<?php $qry2 = $conn->query("SELECT * from t_projects where c_code = '$acr'");
$row2 = $qry2->fetch_assoc();
$name = $row2['c_acronym']; ?>
<style>
        @media print {
        @page {
            size: 8.5in 5.5in;
            margin: 10mm;
        }
        body {
            font-size: 12px;
        }
        .hide-on-print {
            display: none;
        }
    }
    body {
        font-family: 'Armata', sans-serif;
        font-size: 12px;
    }

    #amt_title{
        width:100%;
        text-align:center;
        float:right;

        font-size:14px;
        font-weight:bold;
        padding-top:5px;
        height:30px;
        margin-bottom:25px;
    }
    .number_box {
        font-family: 'Armata', sans-serif;
        font-size: 22px;
        float: right;
        padding: 10px;
        margin-right: -160px;
        margin-top:50px;
        width:177px;
        text-align: center;
    }

    .or_box {
        font-family: 'Armata', sans-serif;
        font-size: 20px;
        background-color: black;
        color: white;
        float: right;
        padding: 10px;
        margin-right: 25px;
    }

    .or_address {
        font-family: 'Armata', sans-serif;
        font-size: 11px;
        font-weight: light !important;
        margin-top: 0px;
        /* position:absolute; */
    }

    .comp_name {
        font-family: 'Armata', sans-serif;
        font-size: 18px;
        font-weight: bold;
        margin-top: 0px;
        /* position:absolute; */
        float: left;
    }

    .cust_info {
        float: left;
        width: 100%;
    }

    .div_particulars {
        text-align: center;
    }

    #numtowords{
        width:auto;
        margin-left:25px;
        height:45px;
        margin-top:-10px;
        position:absolute;
        width:650px;
        border:none;
    }
    @media print {
        @page {
            margin: 5mm;
            orientation: portrait;
            transform: scale(0.8) !important;
        }
    }
</style>

<body onload="per()">
    <div class="whole_content">
        <!-- <div class="text-center" style="padding:20px;"> <input type="button" id="rep" value="Print" class="btn btn-info btn_print"> </div> -->
        <div class="container_content" id="container_content"> <img src="../images/ALSC_BnW.jpg"
                style="height:115px;width:135px;float:left;margin-top:-10px;z-index:-1;margin-bottom:2px;margin-left:15px;">
            <div class="comp_name">ASIAN LAND STRATEGIES CORPORATION</div>
            <?php 
                if ($memo_stats == 'CM') {
                    echo '<div class="or_box">CREDIT MEMO</div>';
                } else {
                    echo '<div class="or_box">DEBIT MEMO</div>';
                }
            ?>
            <?php
                if ($memo_stats == 'CM') {
                    echo '<div class="number_box">NO. CM' . $cm_id . '</div>';
                } else {
                    echo '<div class="number_box">NO. DM' . $cm_id . '</div>';
                }
            ?>

            </div> 
            <div class="or_address"> <br> <br> Asian Land Corporate Center, Mc Arthur Highway <br> Grand Royale Subd.,
                Bulihan, City of Malolos, (Capital), Bulacan 3000 <br> VAT REG. TIN: 003-873-255-00000 <br> Tel. No.
                (044) 791 2508 - 09 </div> <br> <br>
            <div class="card-body" style="margin-top:-25px;">
                <div class="cust_info">
                    <table style="width:100%;border:solid 1px black;">
                        <tr>
                            <td style="padding-right:5px;padding-left:5px;"> Received From: </td>
                            <td>
                                <div class="rcvd_frm" style="text-transform:uppercase;font-weight:bold;">
                                    <?php echo $p_last_name; ?>,
                                    <?php echo $p_first_name; ?>
                                    <?php echo $p_middle_name; ?>
                                </div>
                            </td>
                            <td> Date: </td>
                            <td>
                                <?php echo date("F j, Y"); ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:5px;padding-left:5px;"> Address: </td>
                            <td>
                                <div class="rcvd_frm" style="text-transform:uppercase;">
                                    <?php echo $p_address; ?>,
                                    <?php echo $p_zipcode; ?>
                                </div>
                            </td>
                            <td> Location: </td>
                            <td>
                                <?php echo $name; ?>
                                <?php echo $blk; ?> /
                                <?php echo $lot; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-right:5px;padding-left:5px;"> Client ID: </td>
                            <td>
                                <?php echo $property_id; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div> <br> <br> <br>

            <table id="amt_title">
                <tr>
                    <td style="width:75%;">
                        PARTICULARS
                    </td>
                    <td>
                    AMOUNT
                    </td>
                </tr>
                <tr>
                    <td style="text-align:left;padding-left:45px;margin-top:10px;font-weight:normal;">
                        <?php echo ($reason) ?>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
            
            <div class="total_table" style="width:100%;height:250px;float:right;margin-top:-25px;">
                <table
                    style="width:35%;background-color:#F8F8F8;margin-top:10px;float:right;text-align:right;margin-right:20px;">
                    <tr>
                        <td style="width:10%;"><label class="control-label"><b>Adjustment 1: </b></label></td>
                        <td style="width:10%;"><input type="text" class="form-control-sm" name="cm_amt" id="cm_amt"
                                value="<?php echo number_format($credit_amt, 2) ?>"
                                style="width:125px;border:none;font-family: 'Armata', sans-serif;font-size:12px;text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:10%;"><label class="control-label"><b>Adjustment 2: </b></label></td>
                        <td style="width:10%;"><input type="text" class="form-control-sm"
                                value=""
                                style="width:125px;border:none;font-family: 'Armata', sans-serif;font-size:12px;text-align:right;">
                        </td>
                    </tr>
                    <tr>
                    <?php
                        if ($memo_stats == 'CM') {
                            $new_cm_id = 'CM' . $cm_id;
                        } else {
                            $new_cm_id = 'DM' . $cm_id;
                        }
                    ?>
                    <?php $qry = $conn->query("SELECT * FROM property_payments where or_no = '".$new_cm_id."' ");
                    $row = $qry->fetch_assoc();
                    $rem_bal = $row['remaining_balance'];
                    ?>
                    </tr>
                    <tr>
                    <td style="padding-top:35px;width:10%;"><label class="control-label"><b>Total Adjustment: </b></label>
                        </td>
                        <td style="width:10%;padding-top:35px;"><input type="text" class="form-control-sm" name="tot_adj_amt"
                                id="tot_adj_amt" value="<?php echo number_format($rem_bal, 2) ?>"
                                style="width:125px;border:none;font-family: 'Armata', sans-serif;font-size:12px;text-align:right;">
                        </td>
                    </tr>
                    <tr>
                        <td style="width:10%;"><label class="control-label"><b>Prepared by: </b></label></td>
                        <td style="width:10%;"><input type="text" class="form-control-sm"
                                value="<?php echo $_settings->userdata('firstname') ?> <?php echo $_settings->userdata('lastname') ?>"
                                style="width:200px;border:none;font-family: 'Armata', sans-serif;font-size:12px;text-align:right;">
                        </td>
                    </tr>
                </table>
                <table style="width:100%;margin-top:90px;position:absolute;">
                    <tr>
                        <td style="width:75%;">
                            <label class="control-label" style="text-align:left;padding-left:25px;font-size:12px;
        font-weight:bold;">Amount in words:</label>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <textarea id="numtowords"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
           
        </div>
    </div>
    <?php $current_date_time = date('Y/m/d H:i:s A'); ?>
    <div class="total_table" style="width:100%;height:250px;float:right;margin-top:-75px;">
        <hr style="margin-top:-5px;">
        <table
            style="width:40%;background-color:#F8F8F8;float:right;text-align:right;margin-right:20px;">
            <tr>
                <td style="width:10%;"><label class="control-label"><b>Acknowledged by: </b></label></td>
                <td style="width:10%;"><input type="text" class="form-control-sm"
                        value=""
                        style="width:200px;border:none;font-family: 'Armata', sans-serif;font-size:12px;text-align:right;">
                </td>
            </tr>
            <tr>
                <td style="width:10%;"><label class="control-label" style="font-size:12px;overflow:visible;"><b>Date/Time Generated: </b></label></td>
                <td style="width:10%;"><input type="text" class="form-control-sm"
                        value="<?php echo $current_date_time; ?>"
                        style="width:200px;border:none;font-family: 'Armata', sans-serif;font-size:12px;text-align:right;">
                </td>
            </tr>
        </table>
    </div> <br>
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
    function per() {
    var words="";
    var totalamount = document.getElementById('tot_adj_amt').value;
    
    words = toWords(totalamount);
    $("#numtowords").val(words + "Pesos Only");
}
</script> 
</html>
