<?php 
require_once('../../config.php');
include('../../inc/header.php');?>
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
                <h4 style="margin-top:-25px; text-align:center; font-weight:normal;">BUYER'S INFORMATION AND PAYMENT RECORDS</h4>

                    <div class="container" style="margin-top:15px;margin-left:0.2px;">
                        <div class="buyer_info">
                           
                        <?php
                            $id = pg_escape_string($_GET['id']);
                            $qry1 = pg_query($cnx, "SELECT *,substring(c_account_no::text FROM 1 FOR 3) as acr,
                            substring(c_account_no::text FROM 6 FOR 3) as blk,
                            substring(c_account_no::text FROM 9 FOR 2) as lot FROM t_buyers_account WHERE c_account_no = '$id'");
                            $row1 = pg_fetch_assoc($qry1);
                            $property_id = $row1['c_account_no'];
                            $lot_area = $row1['c_lot_area'];
                            $lot_price_sqm = $row1['c_price_sqm'];
                            $lot_total = (int) $lot_area * (int) $lot_price_sqm;
                            $floor_area = $row1['c_floor_area'];
                            $house_price_sqm = $row1['c_house_price_sqm'];
                            $house_total = (int) $floor_area * (int) $house_price_sqm;
                            $acr = $row1['acr'];
                            $blk = $row1['blk'];
                            $lot = $row1['lot'];
                            $qry2 = pg_query($cnx, "SELECT * FROM t_projects WHERE c_code = '$acr'");
                            $row2 = pg_fetch_assoc($qry2);
                            $name = $row2['c_name'];
                            ?>

                            <table style="font-size:13px;width:1100px;">
                                <tr>
                                    <th style="padding-left:5px; width:150px;">Account No. : </th><td><b><?php echo $row1['c_account_no'];?></b>
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
                                <tr><th style="padding-left:5px; width:150px;">Buyer's Name : </th><td><?php echo $row1['c_b1_first_name'];?> <?php echo $row1['c_b1_middle_name'];?> <?php echo $row1['c_b1_last_name'];?> </td></tr>
                                <tr><th style="padding-left:5px; width:150px;">Home Address : </th><td><?php echo $row1['c_address'];?><?php echo $row1['c_city_prov'];?> <?php echo $row1['c_zip_code'];?></td></tr>
                            </table>
                            <hr style="height:2px;margin-top:0px;margin-bottom:0px;">
                            <table style="font-size:13px;">
                                <tr>
                                    <th style="padding-left:5px; width:150px;">Price=LA*SQM : </th><td><?php echo number_format($lot_total,2); ?> = <?php echo $row1['c_lot_area'];?>.0 * <?php echo number_format($row1['c_price_sqm'],2); ?></td>
                                    <th style="padding-left:5px; width:150px;">Less Discount : </th><td>(<?php echo $row1['c_lot_discount'];?>%) <?php echo number_format ($row1['c_lot_discount_amount'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">Balance : </th><td><?php echo $row1['c_balance'];?></td>
                                </tr>
                                <tr>
                                    <th style="padding-left:5px; width:150px;">Price=FA*SQM : </th><td><?php echo number_format($house_total,2); ?> = <?php echo $row1['c_floor_area'];?>.0 * <?php echo number_format($row1['c_house_price_sqm'],2); ?></td>
                                    <th style="padding-left:5px; width:150px;">Disc. Amt. : </th><td>(<?php echo $row1['c_h_discount'];?>%) <?php echo number_format ($row1['c_h_discount_amount'],2);?></td>
                                    <th style="padding-left:5px; width:150px;">Rate : </th><td><?php echo $row1['c_fixed_factor'];?></td>
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
                                    <th style="padding-left:5px; width:150px;">Commencing on : </th><td>(<?php echo $row1['c_lot_discount'];?>%) <?php echo number_format ($row1['c_lot_discount_amount'],2);?></td>
                                </tr>   
                            </table>
                        </div>
                    </div>
                </div>
            </th>
        </tr>
    </thead>
    <tbody class="report-content">
        <tr>
            <td class="report-content-cell">
                <div class="main" style="margin-top:-30px;width:1100px;">
                    <div class="container">
                        <div id="tab-3" class="tab-content" style="border:solid 1px gainsboro;width:1100px;margin-left:21px;">  
                            <table class="table table-striped" style="text-align:right;font-size:11px;">
                            <?php
                            // Connection to PostgreSQL database
                

                            //$id ='15200202102';
                            $qry4 = pg_query($cnx, "SELECT * FROM t_payment WHERE c_account_no = '$id' ORDER BY c_payment_count,c_due_date ASC");

                            ?>
                       
                            <thead>
                                <tr>
                                    <!-- <th style="text-align:center;font-size:13px;">PROPERTY ID</th> -->
                                    <th style="text-align:center;font-size:13px;">DUE DATE</th>
                                    <th style="text-align:center;font-size:13px;">PAY DATE</th>
                                    <th style="text-align:center;font-size:13px;">OR NO</th>
                                    <th style="text-align:center;font-size:13px;">AMOUNT PAID</th>
                                    <th style="text-align:center;font-size:13px;">SURCHARGE</th>
                                    <th style="text-align:center;font-size:13px;">INTEREST</th>
                                    <th style="text-align:center;font-size:13px;">PRINCIPAL</th>
                                    <th style="text-align:center;font-size:13px;">REBATE</th>
                                    <th style="text-align:center;font-size:13px;">PERIOD</th>
                                    <th style="text-align:center;font-size:13px;">BALANCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (pg_num_rows($qry4) <= 0) {
                                    echo "<tr><td colspan='10' class='text-center' style='font-size:13px;'>No Payment Records</td></tr>";
                                } else {
                                    $total_rebate = 0;
                                    while ($row = pg_fetch_assoc($qry4)): 
                                        $due_dte = $row['c_due_date'];
                                        $pay_dte = $row['c_pay_date'];
                                        $or_no = $row['c_or_no'];
                                        $amt_paid = $row['c_amount_paid'];
                                        $interest = $row['c_interest'];
                                        $principal = $row['c_principal'];
                                        $surcharge = $row['c_surcharge'];
                                        $rebate = $row['c_rebate'];
                                        $period = $row['c_status'];
                                        $balance = $row['c_balance'];

                                        $total_rebate += $rebate;
                                ?>
                                <tr>
                                    <td class="text-center" style="font-size:13px;width:12%;"><?php echo $due_dte ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:12%;"><?php echo $pay_dte ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;">
                                    <?php
                                    if (strpos($or_no, 'RSTR') === 0) {
                                        echo '<a class="basic-link view_restruc" data-id="' . md5($row['property_id']) . '" cid="' . str_replace('RSTR-', '', $or_no) . '">' . $or_no . '</a>';
                                    } elseif (strpos($or_no, 'AV') === 0) {
                                        echo '<a class="basic-link view_av" data-id="' . md5($row['property_id']) . '" cid="' . $or_no . '">' . $or_no . '</a>';
                                    } elseif (strpos($or_no, 'CM') === 0 || strpos($or_no, 'DM') === 0) {
                                        $newId = substr($or_no, 2); 
                                        echo '<a class="basic-link view_cm" data-id="' . $or_no . '">' . $or_no . '</a>';
                                    } else {
                                        echo $or_no;
                                    }
                                    ?>
                                    </td> 
                                    <td class="text-center" style="font-size:13px;width:15%;"><?php echo number_format($amt_paid, 2) ?> </td>
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($surcharge, 2) ?> </td>  
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($interest, 2) ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($principal, 2) ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($rebate, 2) ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo $period ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($balance, 2) ?> </td>  
                                </tr>
                                <?php endwhile; } ?>
                            </tbody>
                       

                            </table>
                            </div>
                        </div>
                    </div>
                    <div class="main" style="margin-top:-30px;width:1100px;margin-left:21px;">
                        <div class="container" style="margin-top:-30px;width:1100px;">
                            <div id="tab-3" class="tab-content" style="gainsboro;width:1100px;">  
                            <?php  
                            $id = pg_escape_string($_GET['id']);
                            ?>

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
                                            $qry4 = pg_query($cnx, "SELECT sum(c_amount_paid) as totalpayment FROM t_payment WHERE c_account_no = '$id'");
                                            while ($rows = pg_fetch_assoc($qry4)) {
                                                echo number_format($rows['totalpayment'], 2);
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $qry4 = pg_query($cnx, "SELECT sum(c_rebate) as rebate FROM t_payment WHERE c_account_no = '$id'");
                                            while ($rows = pg_fetch_assoc($qry4)) {
                                                echo number_format($rows['rebate'], 2);
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $qry4 = pg_query($cnx, "SELECT sum(c_surcharge) as surcharge FROM t_payment WHERE c_account_no = '$id'");
                                            while ($rows = pg_fetch_assoc($qry4)) {
                                                echo number_format($rows['surcharge'], 2);
                                            }
                                        ?>
                                    </td>
                                    <td style="width:80px;">
                                        <?php 
                                            $qry4 = pg_query($cnx, "SELECT sum(c_interest) as interest FROM t_payment WHERE c_account_no = '$id'");
                                            while ($rows = pg_fetch_assoc($qry4)) {
                                                echo number_format($rows['interest'], 2);
                                            }
                                        ?>
                                    </td>
                                    <td style="width:120px;float:left;">
                                        <?php 
                                            $qry4 = pg_query($cnx, "SELECT sum(c_principal) as principal FROM t_payment WHERE c_account_no = '$id'");
                                            while ($rows = pg_fetch_assoc($qry4)) {
                                                echo number_format($rows['principal'], 2);
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

