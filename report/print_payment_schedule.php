 <!DOCTYPE html>
<?php require ('../config.php'); ?>
<?php include "../inc/header.php" ?>

<head>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
	<script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Armata&display=swap" rel="stylesheet">
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
        .page-number {
			position: absolute;
			bottom: 0;
			right: 0;
			padding: 10px;
			background-color: #eee;
			font-size: 14px;
		}
    </style>
</head>
<body>
<table class="report-container" style="margin-top:-10px;">
<div class="page-number"></div>
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
                                <thead> 
                                    <tr>
                                        <th>DUE DATE</th>
                                        <th>PAY DATE</th>
                                        <th>OR NO</th>
                                        <th>AMOUNT PAID</th> 
                                        <th>AMOUNT DUE</th>
                                        <th>INTEREST</th>
                                        <th>PRINCIPAL</th>
                                        <th>SURCHARGE</th>
                                        <th>REBATE</th>
                                        <th>PERIOD</th>
                                        <th>BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody>
                            
                                    <?php 
                                   
                                    include '../admin/clients/payment_schedule_to_fpd.php';
                                
                                    $id = $_GET['id'];   
                                    $all_payments = load_data($id); 
                                    $over_due    = $all_payments[0];
                                    $total_amt_due = $all_payments[1];
                                    $total_interest =  $all_payments[2];
                                    $total_principal = $all_payments[3];
                                    $total_surcharge = $all_payments[4]; 

                                    foreach ($over_due as $l_data): ?>

                                    <tr>
                                        <td class="text-center"><?php echo $l_data[0] ?></td> 
                                        <td class="text-center"><?php echo $l_data[1] ?></td> 
                                        <td class="text-center"><?php echo $l_data[2] ?> </td> 
                                        <td class="text-center"><?php echo $l_data[3] ?> </td> 
                                        <td class="text-center"><?php echo $l_data[4] ?> </td> 
                                        <td class="text-center"><?php echo $l_data[5] ?> </td> 
                                        <td class="text-center"><?php echo $l_data[6] ?> </td> 
                                        <td class="text-center"><?php echo $l_data[7] ?> </td> 
                                        <td class="text-center"><?php echo $l_data[8] ?> </td>  
                                        <td class="text-center"><?php echo $l_data[9] ?> </td>  
                                        <td class="text-center"><?php echo $l_data[10] ?> </td>  
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="main" style="margin-top:-30px;width:1100px;">
                        <div class="container" style="margin-top:-30px;width:1100px;">
                        <h6><b>SUMMARY OF AMOUNT DUE:</b> ===============================================================================================================================</h6>
                            <div id="tab-3" class="tab-content" style="border:solid 1px gainsboro;width:1100px;">  
                                <table style="text-align:center;font-size:11px;width:1100px;">
                                    <thead>
                                        <th style="width:110px;">OVERDUE AMOUNT</th>
                                        <th style="width:110px;">BASIC AMOUNT</th>
                                        <th style="width:110px;">SURCHARGES</th>
                                        <th style="width:110px;"><b>BASIC INTEREST</b></th>
                                        <th style="width:110px;"><b>BASIC PRINCIPAL</b></th>
                                        <th style="width:110px;"><b>O/S BALANCE</b></th>
                                        <th style="width:110px;"><b>PRINCIPAL PAYMT.</b></th>
                                    </thead>
                                    <tr>
                                    <td style="width:15%;">
                                            <?php echo $total_amt_due; ?>
                                        </td>
                                        <td style="width:15%;">
                                        
                                            <?php 
                                            $basic_amt = floatval(str_replace(',', '',$total_interest)) + floatval(str_replace(',', '',$total_principal));;
                                            echo number_format($basic_amt,2);?>
                                        </td>
                                        <td style="width:15%;">
                                            <?php  echo $total_surcharge; ?>
                                        </td>
                                        <td style="width:15%;">
                                            <?php echo $total_interest; ?>
                                        </td>
                                        <td style="width:15%;">
                                            <?php  echo $total_principal; ?>
                                        </td>
                                        <td style="width:15%;">
                                            <?php 
                                                $qry4 =mysqli_query($conn, "SELECT remaining_balance as bal FROM property_payments where md5(property_id) = '{$_GET['id']}' order by payment_count desc limit 1"); 
                                                while($rows = mysqli_fetch_array($qry4)){?>
                                                    <?php echo number_format($rows['bal'],2);?>
                                                <?php
                                                }   
                                            ?>
                                        </td>
                                        <td style="width:15%;">
                                             <?php 
                                                $qry4 =mysqli_query($conn, "SELECT sum(principal) as principal FROM property_payments where md5(property_id) = '{$_GET['id']}' "); 
                                                while($rows = mysqli_fetch_array($qry4)){?>
                                                    <?php echo number_format($rows['principal'],2);?>
                                                <?php
                                                }   
                                            ?>
                                        </td>
                                       
                                        <td style="width:15%;">
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
    window.onload = function() {
        var pageNumber = 1;
        var pageNumbers = document.getElementsByClassName('page-number');
        for (var i = 0; i < pageNumbers.length; i++) {
            pageNumbers[i].textContent = 'Page ' + pageNumber;
        }
        window.onbeforeprint = function() {
            pageNumber = 1;
        };
        window.onafterprint = function() {
            pageNumber++;
            for (var i = 0; i < pageNumbers.length; i++) {
                pageNumbers[i].textContent = 'Page ' + pageNumber;
            }
        };
    };
</script>
</html>

