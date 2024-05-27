<?php require_once('../config.php'); ?>
<?php
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $ssl = 'https';
}
else {
  $ssl = 'http';
}
$app_url = ($ssl  )
          . "://".$_SERVER['HTTP_HOST']
          . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
          . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");
header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE html>
<head>
    <!-- <link rel="stylesheet" href="css/print_payment.css"> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
	<script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script> 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
</head>
<style>
    .watermark_sample{
        background-image: url("images/4.png");
        width:400px;
        background-repeat:no-repeat;
        position:absolute;
        height:350px;
        margin-top:900px;
        margin-left:375px;
        opacity:0.1;
    }
    .ref_no{
        float:left;
        margin-top:-20px;
        margin-left:642px;
        font-size:11px;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<?php 
        include "../inc/header.php";
?> 
<style>
.whole_content{
    visibility:hidden;
}
</style>

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" ></script>
</head>
<body onload="loadAll()">
<div class="whole_content">
    <div class="text-center" style="padding:20px;">
	<input type="button" id="rep" value="Print" class="btn btn-info btn_print">
</div>
<div class="container_content" id="container_content" style="margin-top:5px;font-size:9px;">
    <img src="images/Header.jpg" class="img-thumbnail" style="height:80px;width:500px;margin-left:142px;border:none;margin-bottom:-5px;z-index:-1;position: relative;" alt="">
    <h6 class="text-center" style="position:absolute;margin-top:-40px;margin-left:330px;"><b>RESERVATION APPLICATION</b></h6>
    
    
    <!-- "SELECT x.*, y.ra_id, y.c_csr_status, y.c_reserve_status, 
                        y.c_ca_status, y.c_duration, z.*,
                        y.c_csr_no as csr_num FROM t_approval_csr y 
                        inner join t_csr x on x.c_csr_no = y.c_csr_no 
                        inner join t_additional_cost z on z.c_csr_no = x.c_csr_no
                        where md5(y.c_csr_no) = '{$_GET['id']}'"  -->
    
    <?php
    $query = "SELECT w.*,x.*,y.*, w.c_csr_no as csr_num FROM t_csr_buyers w inner join t_additional_cost x on x.c_csr_no = w.c_csr_no inner join t_csr y on y.c_csr_no=w.c_csr_no WHERE y.c_csr_no = '{$_GET['id']}'";
    $result = mysqli_query($conn, $query);
    $count=$result->num_rows;
    // mysqli select query
    // echo $count;
    while ($row = mysqli_fetch_assoc($result)) {
        $aircon_outlets = $row['aircon_outlets'];
        $aircon_grill = $row['aircon_grill'];
        $conv_outlet = $row['conv_outlet'];
        $service_area = $row['service_area'];
        $others = $row['others'];
        $aircon_outlet_price = $row['aircon_outlet_price'];
        $aircon_grill_price = $row['aircon_grill_price'];
        $conv_outlet_price = $row['conv_outlet_price'];
        $service_area_price = $row['service_area_price'];
        $others_price = $row['others_price'];
        $floor_elev_price = $row['floor_elev_price'];
        $floor_elevation =$row['floor_elevation'];


        $c_date_created = $row['c_date_created'];
        $c_csr_no = $row['c_csr_no']; 
        $c_b1_last_name = $row['last_name']; 
        $c_b1_first_name = $row['first_name']; 
        $c_b1_middle_name = $row['middle_name']; 
        $c_suffix = $row['suffix_name']; 
        $c_citizenship = $row['citizenship']; 
        $c_address = $row['address']; 
        $c_mobile_no = $row['contact_no']; 
        $c_viber_no = $row['viber']; 
        $c_address_abroad = $row['address_abroad']; 
        $c_mobile_abroad = $row['contact_abroad']; 
        
        $ref_no = $row['ref_no'];
        $c_lid = $row['c_lot_lid']; 
        $c_birthday = $row['birthdate'];  
        $c_age = $row['age'];  
        $c_tin = $row['tin_no'];  
        $c_zip = $row['zip_code'];  
        $c_id_presented = $row['id_presented'];
        $c_sex = $row['gender']; 
        $c_civil_status = $row['civil_status'];  
        $c_email = $row['email'];  
        $c_lot_area = $row['c_lot_area'];
        $c_price_sqm = $row['c_price_sqm'];
        $c_lot_discount_amt = $row['c_lot_discount_amt'];
        $c_lot_discount = $row['c_lot_discount'];
        $c_house = $row['c_house_model'];
        $c_date_created = $row['c_date_created'];
        $c_floor_area = $row['c_floor_area'];
        $c_house_discount = $row['c_house_discount'];
        $c_house_discount_amt = $row['c_house_discount_amt'];
        $c_house_price_sqm = $row['c_house_price_sqm'];
        $c_linear = $row['c_linear'];
        $c_fence_price_sqm = $row['c_fence_price_sqm'];
        $c_tcp = $row['c_tcp'];
        $remarks = $row['c_remarks'];
        // $c_employment_status = $row['c_employment_status'];
        $c_lot_discount_percentage = $row['c_lot_discount'];
        $c_net_dp = $row['c_net_dp'];
        $c_reservation = $row['c_reservation'];
        $c_terms = $row['c_terms'];
        $c_monthly_payment = $row['c_monthly_payment'];
        $c_monthly_down = $row['c_monthly_down'];
        $amt_fnanced = $row['c_amt_financed'];
        $interest_rate = $row['c_interest_rate'];
        $c_fixed_factor = $row['c_fixed_factor'];
        $down_percent = $row['c_down_percent'];
        $c_relation = $row['relationship'];
        $c_processing_fee = $row['c_processing_fee'];
        $c_less = $row['c_less'];
        $c_pf_mo = $row['pf_mo'];
        $c_no_payment = $row['c_no_payments'];
        ?>
        <div class="text-center" id="dateofsale" style="margin-top:-22px;margin-bottom:-5px;margin-left:85px;"><b>Date of Sale:</b> <?php echo date("F d, Y",strtotime($c_date_created)) ?></div>
        <br>
        <!-- <div class="watermark_sample"></div> -->
        <?php
     }
    ?>
<?php
    if ($count == 1 && $c_floor_area == 0){
        include('print_1buyer_without_add_cost.php');
        return;
    }else if($count == 1 && $c_floor_area != 0){
        include('print_1buyer_with_add_cost.php');
        return;
    }else if($count == 2 && $c_floor_area != 0){
        include('print_2buyers_with_add_cost.php');
        include('print_payment_with_add_cost.php');
    }else if($count == 2 && $c_floor_area == 0){
        include('print_2buyers_without_add_cost.php');
        include('print_payment_without_add_cost.php');
    }else if($count == 3 && $c_floor_area == 0){
        include('print_3buyers_without_add_cost.php');
        include('print3_payment_without_add_cost.php');
    ?>
        <!-- <p style="page-break-after:always;"></p> -->
    <?php
        // include('print_payment_without_add_cost.php');
    }else if($count == 3 && $c_floor_area != 0){
        ?>
        <br>
        <?php
        include('print_3buyers_with_add_cost.php');
        include('print3_payment_with_add_cost.php');
        ?>
        <!-- <p style="page-break-after:always;"></p> -->
    <?php
        
        
    }else if($count == 4 && $c_floor_area != 0){
        ?>
        <br>
        <?php
        include('print_4buyers_with_add_cost.php');
        ?>
        <p style="page-break-after:always;"></p>
        <?php
            include('print4_payment_with_add_cost.php');
        ?>
        <!-- <p style="page-break-after:always;"></p> -->
    <?php
        
        
    }else if($count == 4 && $c_floor_area == 0){
        
        ?>
        <br>
        <?php
        include('print_4buyers_with_add_cost.php');
        ?>
        <p style="page-break-after:always;"></p>
        <?php
            include('print4_payment_without_add_cost.php');
        ?>
        <?php
    }
?>
</body>
</html>
</div>
<!-- 
<script type="text/javascript">
function getCivilStatus(){
	var civil=document.getElementById("civil_status").value;
	if(civil=="Married"){
		document.getElementById('married').checked=true;
	}else if(civil=="Separated"){
		document.getElementById('separated').checked=true;
	}else if(civil=="Single"){
		document.getElementById('single').checked=true;
	}else{
		document.getElementById("widowed").checked=true;
	}
}
function getGender(){
    var sex=document.getElementById("gender").value;
    if(sex=="male"){
        document.getElementById('male').checked=true;
    }else{
        document.getElementById('female').checked=true;
    }
}
function getPhase(){
    var phase=document.getElementById("txtPhase").value;

    if(phase==152){
        document.getElementById('final_phase').value="CBP";
    }else if(phase==161){
        document.getElementById('final_phase').value="CBP-1A";
    }else if(phase==180){
        document.getElementById('final_phase').value="CBP-1B";
    }else if(phase==157){
        document.getElementById('final_phase').value="CBP-2";
    }else if(phase==166){
        document.getElementById('final_phase').value="CBP-2A";
    }else if(phase==170){
        document.getElementById('final_phase').value="CBP-2B";
    }else if(phase==168){
        document.getElementById('final_phase').value="CBP-3";
    }else if(phase==182){
        document.getElementById('final_phase').value="CBP-3A";
    }else if(phase==186){
        document.getElementById('final_phase').value="CBP-3B";
    }else if(phase==189){
        document.getElementById('final_phase').value="CBP-3C";
    }else if(phase==169){
        document.getElementById('final_phase').value="CBP-4";
    }else if(phase==172){
        document.getElementById('final_phase').value="CBP-5";
    }else if(phase==183){
        document.getElementById('final_phase').value="CBP-5A";
    }else if(phase==187){
        document.getElementById('final_phase').value="CBP-5B";
    }else if(phase==102){
        document.getElementById('final_phase').value="CR";
    }else if(phase==191){
        document.getElementById('final_phase').value="CR-AH";
    }else if(phase==104){
        document.getElementById('final_phase').value="DCH-1";
    }else if(phase==114){
        document.getElementById('final_phase').value="DCH-1A";
    }else if(phase==107){
        document.getElementById('final_phase').value="DCH-2A";
    }else if(phase==109){
        document.getElementById('final_phase').value="DCH-2B";
    }else if(phase==137){
        document.getElementById('final_phase').value="DCH-2C";
    }else if(phase==158){
        document.getElementById('final_phase').value="DCH-2D";
    }else if(phase==112){
        document.getElementById('final_phase').value="DCH-3";
    }else if(phase==116){
        document.getElementById('final_phase').value="DCH-4";
    }else if(phase==117){
        document.getElementById('final_phase').value="DCH-5";
    }else if(phase==138){
        document.getElementById('final_phase').value="DCH-5A";
    }else if(phase==145){
        document.getElementById('final_phase').value="DCH-5B";
    }else if(phase==147){
        document.getElementById('final_phase').value="DCH-5C";
    }else if(phase==162){
        document.getElementById('final_phase').value="DCH-5D";
    }else if(phase==185){
        document.getElementById('final_phase').value="DCH-5E";
    }else if(phase==192){
        document.getElementById('final_phase').value="DCH-AH";
    }else if(phase==106){
        document.getElementById('final_phase').value="GIE";
    }else if(phase==103){
        document.getElementById('final_phase').value="GR-1";
    }else if(phase==128){
        document.getElementById('final_phase').value="GR-10";
    }else if(phase==110){
        document.getElementById('final_phase').value="GR-1A";
    }else if(phase==133){
        document.getElementById('final_phase').value="GR-1B";
    }else if(phase==134){
        document.getElementById('final_phase').value="GR-1C";
    }else if(phase==153){
        document.getElementById('final_phase').value="GR-1D";
    }else if(phase==154){
        document.getElementById('final_phase').value="GR-1E";
    }else if(phase==160){
        document.getElementById('final_phase').value="GR-1F";
    }else if(phase==105){
        document.getElementById('final_phase').value="GR-2";
    }else if(phase==108){
        document.getElementById('final_phase').value="GR-2A";
    }else if(phase==111){
        document.getElementById('final_phase').value="GR-3";
    }else if(phase==139){
        document.getElementById('final_phase').value="GR-3A";
    }else if(phase==165){
        document.getElementById('final_phase').value="GR-3B";
    }else if(phase==113){
        document.getElementById('final_phase').value="GR-4";
    }else if(phase==136){
        document.getElementById('final_phase').value="GR-4A";
    }else if(phase==115){
        document.getElementById('final_phase').value="GR-5";
    }else if(phase==118){
        document.getElementById('final_phase').value="GR-5A";
    }else if(phase==142){
        document.getElementById('final_phase').value="GR-5B";
    }else if(phase==144){
        document.getElementById('final_phase').value="GR-5C";
    }else if(phase==151){
        document.getElementById('final_phase').value="GR-5D";
    }else if(phase==119){
        document.getElementById('final_phase').value="GR-6";
    }else if(phase==143){
        document.getElementById('final_phase').value="GR-6A";
    }else if(phase==148){
        document.getElementById('final_phase').value="GR-6B";
    }else if(phase==173){
        document.getElementById('final_phase').value="GR-6C";
    }else if(phase==184){
        document.getElementById('final_phase').value="GR-6D";
    }else if(phase==179){
        document.getElementById('final_phase').value="GR-6E";
    }else if(phase==120){
        document.getElementById('final_phase').value="GR-7";
    }else if(phase==130){
        document.getElementById('final_phase').value="GR-7A";
    }else if(phase==132){
        document.getElementById('final_phase').value="GR-7B";
    }else if(phase==135){
        document.getElementById('final_phase').value="GR-7C";
    }else if(phase==140){
        document.getElementById('final_phase').value="GR-7D";
    }else if(phase==141){
        document.getElementById('final_phase').value="GR-7E";
    }else if(phase==146){
        document.getElementById('final_phase').value="GR-7F";
    }else if(phase==155){
        document.getElementById('final_phase').value="GR-7G";
    }else if(phase==159){
        document.getElementById('final_phase').value="GR-7H";
    }else if(phase==171){
        document.getElementById('final_phase').value="GR-7I";
    }else if(phase==188){
        document.getElementById('final_phase').value="GR-7J";
    }else if(phase==121){
        document.getElementById('final_phase').value="GR-8";
    }else if(phase==124){
        document.getElementById('final_phase').value="GR-8A";
    }else if(phase==125){
        document.getElementById('final_phase').value="GR-8B";
    }else if(phase==126){
        document.getElementById('final_phase').value="GR-8C";
    }else if(phase==129){
        document.getElementById('final_phase').value="GR-8D";
    }else if(phase==131){
        document.getElementById('final_phase').value="GR-8E";
    }else if(phase==178){
        document.getElementById('final_phase').value="GR-8F";
    }else if(phase==122){
        document.getElementById('final_phase').value="GR-9";
    }else if(phase==127){
        document.getElementById('final_phase').value="GR-9A";
    }else if(phase==175){
        document.getElementById('final_phase').value="GR-9B";
    }else if(phase==193){
        document.getElementById('final_phase').value="GR-AH";
    }else if(phase==194){
        document.getElementById('final_phase').value="MEAD-AH";
    }else if(phase==123){
        document.getElementById('final_phase').value="MEADOWS";
    }else if(phase==164){
        document.getElementById('final_phase').value="MEADOWS-2";
    }else if(phase==101){
        document.getElementById('final_phase').value="RE";
    }else if(phase==150){
        document.getElementById('final_phase').value="RE-2";
    }else if(phase==190){
        document.getElementById('final_phase').value="RE-AH";
    }else if(phase==149){
        document.getElementById('final_phase').value="WGR";
    }else if(phase==167){
        document.getElementById('final_phase').value="WGR-1A";
    }else if(phase==177){
        document.getElementById('final_phase').value="WGR-1B";
    }else if(phase==156){
        document.getElementById('final_phase').value="WGR-2";
    }else if(phase==176){
        document.getElementById('final_phase').value="WGR-2A";
    }else if(phase==181){
        document.getElementById('final_phase').value="WGR-2B";
    }else if(phase==163){
        document.getElementById('final_phase').value="WGR-3";
    }else if(phase==174){
        document.getElementById('final_phase').value="WGR-4";
    }
}

///////////////////////////////COMPUTATIONS////////////////////////////////////////

function getHCP(){
    var house_price_sqm = document.getElementById('c_house_price_sqm').value;
    var house_floor_area = document.getElementById('c_house_flr_area').value;

    var hcp = house_price_sqm * house_floor_area;

    document.getElementById('c_hcp').value = hcp;
}

function getLCP(){
    var lot_price_sqm = document.getElementById('c_lot_price_sqm').value;
    var lot_area = document.getElementById('c_lot_area').value;

    var lcp = lot_price_sqm * lot_area;

    document.getElementById('c_lcp').value = lcp;
}

function printRA(){
        var element = document.getElementById('container_content'); 
        var opt = 
        {
        margin:       [0,5,0,5],
        filename:    'RA<?php echo $c_csr_no; ?>-'+'<?php echo $c_b1_last_name; ?>_'+'<?php echo $c_b1_first_name; ?>_'+'<?php echo $c_b1_middle_name; ?>'+'.pdf',
        
        image:        { type: 'jpeg', quality: 2 },
        html2canvas:  { dpi: 300, letterRendering: true, width: 780, height: 2500, scale:2},
        //html2canvas:  { dpi: 2000, letterRendering: true, width: 216, height: 356, scale:2},
        jsPDF:        { unit: 'mm', format: 'legal', orientation: 'portrait' }
        };
        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
        // window.setTimeout(function(){
        // window.history.back();
        // }, 500);
} -->

</script>