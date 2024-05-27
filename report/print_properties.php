<?php require ('../config.php'); ?>
<?php include "../inc/header.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
	<script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Armata&display=swap" rel="stylesheet">
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>	
        .container{
            padding:25px;
            width:1100px;
            margin-left:-25px;
            margin-right:0px!important;
        }
        .buyer_info{
            border:black 2px solid;
            margin-bottom:-10px;
            width:1100px;
        }
        table.report-container{
            page-break-after:always;
        }
        thead.report-header{
            display:table-header-group;
        }
        tfoot.report-footer{
            display:table-footer-group;
        }
        body{
            font-family: 'Armata', sans-serif;
        }
        h6{
            font-family: 'Armata', sans-serif;
            font-size:13px;
        }
        td{
            font-weight:normal;
        }

    </style>
</head>
<body>
<table class="report-container" style="margin-top:-10px;">
    <thead class="report-header">
        <tr>
            <th class="report-header-cell">
                <div class="header-info">
                <img src="images/Header.jpg" class="img-thumbnail" style="height:110px;width:750px;margin-left:-105px;border:none;margin-bottom:-5px;z-index:-1;position: relative;margin-bottom:-35px;" alt="">
                <h6 style="margin-top:-25px;margin-left:120px;font-weight:normal;">OVERDUE AMOUNT AND LAST PAYMENT RECORDS</h6>

                    <div class="container" style="margin-top:15px;">
                        <div class="buyer_info">
                            <?php $qry = $conn->query("SELECT * FROM property_clients where md5(property_id) = '{$_GET['id']}' ");
                                $row= $qry->fetch_assoc();
                                $client_id = $row['client_id'];
                            ?>
                            <?php $qry1 = $conn->query("SELECT * FROM properties where md5(property_id) = '{$_GET['id']}' ");
                                $row1 = $qry1->fetch_assoc();
                                $property_id = $row1['property_id'];
                                $lot_area = $row1['c_lot_area'];
                                $lot_price_sqm = $row1['c_price_sqm'];
                                $lot_total = (int) $lot_area * (int) $lot_price_sqm;
                                $floor_area = $row1['c_floor_area'];
                                $house_price_sqm = $row1['c_house_price_sqm'];
                                $house_total = (int) $floor_area * (int) $house_price_sqm;
                                $acr = substr($property_id, 2, 3);
                                $blk = substr($property_id, 5, 3);
                                $lot = substr($property_id, 8, 2);
                            ?>
                            <?php $qry2 = $conn->query("SELECT * from t_projects where c_code = '$acr'");
                                $row2 = $qry2->fetch_assoc();
                                $name = $row2['c_name'];
                            ?>
                            <table style="font-size:13px;width:1100px;">
                                <tr>
                                    <th style="padding-left:5px; width:150px;">Account No. : </th><td><b><?php echo $row['client_id'];?></b>
                                    <th style="padding-left:5px; width:150px;">Project Site : </th><td><?php echo $name;?> <?php echo $blk;?> - <?php echo $lot;?>

                                    <?php if($row1['c_account_type1'] == 'REG'){ ?>
                                        <th style="padding-left:5px; width:150px;">**REGULAR**</th>
                                    <?php }elseif($row1['c_account_type1'] == 'LEG'){ ?>
                                        <th style="padding-left:5px; width:150px;">**SPECIAL ACCT**</th>
                                    <?php }elseif($row1['c_account_type1'] == 'OFF'){ ?>
                                        <th style="padding-left:5px; width:150px;">**OFFSETTING**</th>
                                    <?php }elseif($row1['c_account_type1'] == 'UHL'){ ?>
                                        <th style="padding-left:5px; width:150px;">**THRU LOAN**</th>
                                    <?php }elseif($row1['c_account_type1'] == 'OTH'){ ?>
                                        <th style="padding-left:5px; width:150px;">>**SPECIAL ACCT**</th>
                                    <?php }
                                    ?>
                                </tr>
                                <tr><th style="padding-left:5px; width:150px;">Buyer's Name : </th><td><?php echo $row['first_name'];?> <?php echo $row['middle_name'];?> <?php echo $row['last_name'];?> <?php echo $row['suffix_name'];?></td></tr>
                                <tr><th style="padding-left:5px; width:150px;">Home Address : </th><td><?php echo $row['address'];?> <?php echo $row['zip_code'];?></td></tr>
                            </table>
                            <hr style="height:2px;margin-top:0px;margin-bottom:0px;">
                            <table style="font-size:13px;">
                                <tr>
                                    <th style="padding-left:5px; width:150px;">Price=LA*SQM : </th><td><?php echo number_format($lot_total,2); ?> = <?php echo $row1['c_lot_area'];?>.0 * <?php echo number_format($row1['c_price_sqm'],2); ?></td>
                                    <th style="padding-left:5px; width:150px;">Less Discount : </th><td>(<?php echo $row1['c_lot_discount'];?>%) <?php echo number_format ($row1['c_lot_discount_amt'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">Balance : </th><td>(<?php echo $row1['c_lot_discount'];?>%) <?php echo number_format ($row1['c_lot_discount_amt'],2);?></td>
                                </tr>
                                <tr>
                                    <th style="padding-left:5px; width:150px;">Price=FA*SQM : </th><td><?php echo number_format($house_total,2); ?> = <?php echo $row1['c_floor_area'];?>.0 * <?php echo number_format($row1['c_house_price_sqm'],2); ?></td>
                                    <th style="padding-left:5px; width:150px;">Disc. Amt. : </th><td>(<?php echo $row1['c_house_discount'];?>%) <?php echo number_format ($row1['c_house_discount_amt'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">Rate : </th><td>(<?php echo $row1['c_lot_discount'];?>%) <?php echo number_format ($row1['c_lot_discount_amt'],2);?></td>
                                </tr>
                                <tr>
                                    <th style="padding-left:5px; width:150px;">Total C.Price: </th><td><?php echo number_format($row1['c_tcp'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">DP Amt. : </th><td><?php echo number_format($row1['c_down_percent'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">Terms to Pay : </th><td><?php echo $row1['c_terms'];?> mos.</td>
                                </tr>
                                <tr>
                                    <th style="padding-left:5px; width:150px;">VAT: </th><td><?php echo number_format($row1['c_vat_amount'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">DP / Month : </th><td><?php echo number_format($row1['c_monthly_down'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">Monthly Amort : </th><td><?php echo number_format ($row1['c_monthly_payment'],2);?></td>
                                </tr>
                                <tr>
                                    <th style="padding-left:5px; width:150px;">NET T.C.Price : </th><td><?php echo number_format($row1['c_net_tcp'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">House Model : </th><td><?php echo $row1['c_house_model'];?></td>
                                    <th style="padding-left:5px; width:150px;">Commencing on : </th><td>(<?php echo $row1['c_lot_discount'];?>%) <?php echo number_format ($row1['c_lot_discount_amt'],2);?></td>
                                </tr>   
                            </table>
                        </div>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
    <!-- <tfoot class="report-footer">
        <tr>
            <td class="report-footer-cell">
                <div class="footer-info">
                    <div class="main" style="margin-top:-30px;">
                        <div class="container">
                            <div id="tab-3" class="tab-content" style="border:solid 1px gainsboro;">  
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tfoot> -->

    <tbody class="report-content">
        <tr>
            <td class="report-content-cell">
                <div class="main" style="margin-top:-30px;width:1100px;">
                    <div class="container">
                        <div id="tab-3" class="tab-content" style="border:solid 1px gainsboro;width:1100px;">  
                            <table class="table table-striped" style="text-align:right;font-size:11px;">
                            <?php $qry4 = $conn->query("SELECT * FROM property_payments where md5(property_id) = '{$_GET['id']}' ");
                                if($qry4->num_rows <= 0){
                                    echo "No Payment Records";
                                }else{  ?>      
                                <thead> 
                                    <tr>
                                        <th>DUE DATE</th>
                                        <th>PAY DATE</th>
                                        <th>OR NO.</th>
                                        <th>PERIOD</th>
                                        <th>AMOUNT PAID</th>
                                        <th>REBATE</th>
                                        <th>SURCHARGE</th>
                                        <th>INTEREST</th>
                                        <th>PRINCIPAL</th>
                                        <th>BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        while($row= $qry4->fetch_assoc()): 
                                            $property_id = $row["property_id"];
                                            $property_id_part1 = substr($property_id, 0, 2);
                                            $property_id_part2 = substr($property_id, 2, 6);
                                            $property_id_part3 = substr($property_id, 8, 5);
                                            $payment_id = $row['payment_id'];
                                            $due_dte = $row['due_date'];
                                            $pay_dte = $row['pay_date'];
                                            $or_no = $row['or_no'];
                                            $period = $row['status'];
                                            $amt_paid = $row['payment_amount'];
                                            $interest = $row['interest'];
                                            $principal = $row['principal'];
                                            $surcharge = $row['surcharge'];
                                            $rebate = $row['rebate'];
                                            $balance = $row['remaining_balance'];
                                    ?>
                                    <tr>
                                        <td style="text-align:right"><?php echo $due_dte ?> </td> 
                                        <td style="text-align:right"><?php echo $pay_dte ?> </td> 
                                        <td style="text-align:right"><?php echo $or_no ?> </td> 
                                        <td style="text-align:right"><?php echo $period ?> </td> 
                                        <td style="text-align:right"><?php echo number_format($amt_paid,2) ?> </td> 
                                        <td style="text-align:right"><?php echo number_format($rebate,2) ?> </td> 
                                        <td style="text-align:right"><?php echo number_format($surcharge,2) ?> </td> 
                                        <td style="text-align:right"><?php echo number_format($interest,2) ?> </td> 
                                        <td style="text-align:right"><?php echo number_format($principal,2) ?> </td> 
                                        <td style="text-align:right"><?php echo number_format($balance,2) ?> </td>  
                                    </tr>
                                    <?php endwhile ; } ?>
                                </tbody>
                            </table>  
                            </div>
                        </div>
                    </div>
                    <div class="main" style="margin-top:-30px;width:1100px;">
                        <div class="container" style="margin-top:-30px;width:1100px;">
                            <div id="tab-3" class="tab-content" style="border:solid 1px gainsboro;width:1100px;">  
                                <table style="text-align:right;font-size:11px;width:1100px;">
                                    <thead>
                                        <th style="width:130px;"></th>
                                        <th style="width:130px;"></th>
                                        <th style="width:130px;"></th>
                                        <th style="width:165px;"><b>AMOUNT PAID</b></th>
                                        <th style="width:85px;"><b>REBATE</b></th>
                                        <th style="width:125px;"><b>SURCHARGE</b></th>
                                        <th style="width:90px;"><b>INTEREST</b></th>
                                        <th style="width:120px;float:left;"><b>PRINCIPAL</b></th>
                                    </thead>
                                    <tr>
                                        <td style="width:100px;"></td>
                                        <td style="width:100px;"></td>
                                        <td style="width:100px;"></td>
                                        <td style="width:165px;">
                                            <?php 
                                                $qry4 =mysqli_query($conn, "SELECT sum(payment_amount) as totalpayment FROM property_payments where md5(property_id) = '{$_GET['id']}' "); 
                                                while($rows = mysqli_fetch_array($qry4)){?>
                                                    <?php echo number_format($rows['totalpayment'],2);?>
                                                <?php
                                                }   
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $qry4 =mysqli_query($conn, "SELECT sum(rebate) as rebate FROM property_payments where md5(property_id) = '{$_GET['id']}' "); 
                                                while($rows = mysqli_fetch_array($qry4)){?>
                                                    <?php echo number_format($rows['rebate'],2);?>
                                                <?php
                                                }   
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $qry4 =mysqli_query($conn, "SELECT sum(surcharge) as surcharge FROM property_payments where md5(property_id) = '{$_GET['id']}' "); 
                                                while($rows = mysqli_fetch_array($qry4)){?>
                                                    <?php echo number_format($rows['surcharge'],2);?>
                                                <?php
                                                }   
                                            ?>
                                        </td>
                                        <td style="width:80px;">
                                            <?php 
                                                $qry4 =mysqli_query($conn, "SELECT sum(interest) as interest FROM property_payments where md5(property_id) = '{$_GET['id']}' "); 
                                                while($rows = mysqli_fetch_array($qry4)){?>
                                                    <?php echo number_format($rows['interest'],2);?>
                                                <?php
                                                }   
                                            ?>
                                        </td>
                                        <td style="width:120px;float:left;">
                                            <?php 
                                                $qry4 =mysqli_query($conn, "SELECT sum(principal) as principal FROM property_payments where md5(property_id) = '{$_GET['id']}' "); 
                                                while($rows = mysqli_fetch_array($qry4)){?>
                                                    <?php echo number_format($rows['principal'],2);?>
                                                <?php
                                                }   
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
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
</html>

