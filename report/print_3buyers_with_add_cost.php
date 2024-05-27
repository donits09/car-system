<?php require_once('../config.php'); ?>
<link rel="stylesheet" href="css/print_ra_b3_with_add_cost.css">
<script src="../build/js/print.js" type="text/javascript"></script>
<?php $query2 = "SELECT x.*, y.*, y.c_csr_no as csr_num FROM t_csr_buyers y inner join t_csr x on x.c_csr_no = y.c_csr_no WHERE y.c_csr_no = '{$_GET['id']}' ";
$result2 = mysqli_query($conn, $query2);
$count = 0;
if($result2) {
    while ($row = mysqli_fetch_assoc($result2)) { 
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
        $c_tcp = $row['c_tcp'];
        $remarks = $row['c_remarks'];
        // $c_employment_status = $row['c_employment_status'];
        $c_lot_discount_percentage = $row['c_lot_discount'];

        $c_reservation = $row['c_reservation'];
        $c_terms = $row['c_terms'];
        $c_monthly_payment = $row['c_monthly_payment'];
        $amt_fnanced = $row['c_amt_financed'];
        $interest_rate = $row['c_interest_rate'];
        $down_percent = $row['c_down_percent'];
        $c_relation = $row['relationship'];
        $count = $count + 1;
        ?>                
<body onload="getRelation()">
<div class="card-body">
    <div class="doc_title" style="padding-bottom:3px;padding-top:3px;">Name and Contact details of Purchaser's Spouse or Co-Owner - Details must be consistent will all documents</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Last Name:</label>
                    <input type="text" value="<?php echo $c_b1_last_name; ?>" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Suffix Name:</label>
                    <input type="text" value="<?php echo $c_suffix; ?>" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">First Name:</label>
                    <input type="text" value="<?php echo $c_b1_first_name; ?>" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Middle Name:</label>
                    <input type="text" value="<?php echo $c_b1_middle_name; ?>" class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Citizenship:</label>
                    <input type="text" value="<?php echo $c_citizenship; ?>"  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label" id="small_title">Civil Status:</label>
                    <div class="chkboxes">
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        <input id="single<?php echo $count; ?>" type="checkbox" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">SINGLE<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="married<?php echo $count; ?>" type="checkbox" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">MARRIED<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="widowed<?php echo $count; ?>" type="checkbox" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">WIDOWED<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="separated<?php echo $count; ?>" type="checkbox" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">SEPARATED<label>
                        </div>
                        <input type="hidden" id="civil_status<?php echo $count; ?>" value="<?php echo $c_civil_status; ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" id="small_title1" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:</label>
                    <div class="chkboxes">
                        <div style="float:left;margin-left:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="male<?php echo $count; ?>" type="checkbox" name="gender" />
                        </div>
                        <div style="float:left">
                            <label class="light">&nbsp;M<label>
                        </div>
                        <div style="float:left;margin-left:2px;margin-top:3px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input id="female<?php echo $count; ?>" type="checkbox" name="gender" />
                        </div>
                        <div style="float:left">
                            <label class="light">&nbsp;F<label>
                        </div>
                        <input type="hidden" id="gender_txt<?php echo $count; ?>" value="<?php echo $c_sex; ?>"> 
                    </div> 
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Age:</label>
                    <input type="text" value="<?php echo $c_age; ?>"  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Birthdate (YYYY-MM-DD):</label>
                    <input type="date" value="<?php echo $c_birthday; ?>"  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Type of Valid ID presented:</label>
                    <input type="text" value="<?php echo $c_id_presented; ?>"  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">TIN #:</label>
                    <input type="text" value="<?php echo $c_tin; ?>"  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Email Address:</label>
                    <input type="text" value="<?php echo $c_email; ?>"  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Viber Account:</label>
                    <input type="text" value="<?php echo $c_viber_no; ?>"  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                <label class="control-label" id="lbl_space">Contact Number:</label><br>	
                <input type="text" value="<?php echo $c_mobile_no; ?>"  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label class="control-label">Residential/Billing Address:</label>
                    <input type="text" value="<?php echo $c_address; ?>" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Area Code:</label>
                    <input type="text" value="<?php echo $c_zip; ?>"  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom:-10px;">
        <div class="col-md-9">
            <div class="form-group">
                <label class="control-label">Address Abroad:</label>
                <input type="text" value="<?php echo $c_address_abroad; ?>"  class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Contact Number Abroad:</label>
                <input type="text" value="<?php echo $c_mobile_abroad; ?>"  class="form-control form-control-sm" style="margin-bottom:-10px;"> 
            </div>
        </div>
        <div class="col-md-12">
            <div style="float:left;margin-right:2px;margin-top:3px;">
                <input id="and<?php echo $count; ?>" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:-5px;">And<label>
            </div>
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="spouses<?php echo $count; ?>" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:-5px;">Spouses<label>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="marriedto<?php echo $count; ?>" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
            <label style="font-weight:normal;margin-bottom:-5px;">Married To<label>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="minor<?php echo $count; ?>" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:-5px;">Minor/Represented by Legal Guardian<label>
            </div>
            <br><br>
            <input type="hidden" value="<?php echo $c_relation; ?>" id="rel<?php echo $count; ?>"> 
        </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function getCivilStatus(){
	var civil=document.getElementById("civil_status1").value;
	if(civil=="Married"){
		document.getElementById('married1').checked=true;
	}else if(civil=="Separated"){
		document.getElementById('separated1').checked=true;
	}else if(civil=="Single"){
		document.getElementById('single1').checked=true;
	}else{
		document.getElementById('widowed1').checked=true;
	}
    getCivilStatus2();
    getCivilStatus3();
}
function getCivilStatus2(){
	var civil=document.getElementById("civil_status2").value;
	if(civil=="Married"){
		document.getElementById('married2').checked=true;
	}else if(civil=="Separated"){
		document.getElementById('separated2').checked=true;
	}else if(civil=="Single"){
		document.getElementById('single2').checked=true;
	}else{
		document.getElementById('widowed2').checked=true;
	}
}
function getCivilStatus3(){
	var civil=document.getElementById("civil_status3").value;
	if(civil=="Married"){
		document.getElementById('married3').checked=true;
	}else if(civil=="Separated"){
		document.getElementById('separated3').checked=true;
	}else if(civil=="Single"){
		document.getElementById('single3').checked=true;
	}else{
		document.getElementById('widowed3').checked=true;
	}
}
function getGender(){
    var sex=document.getElementById("gender_txt1").value;
    if(sex=="M"){
        document.getElementById('male1').checked=true;
    }else{
        document.getElementById('female1').checked=true;
    }
    getGender2();
    getGender3();
}
function getGender2(){
    var sex=document.getElementById("gender_txt2").value;
    if(sex=="M"){
        document.getElementById('male2').checked=true;
    }else{
        document.getElementById('female2').checked=true;
    }
}
function getGender3(){
    var sex=document.getElementById("gender_txt3").value;
    if(sex=="M"){
        document.getElementById('male3').checked=true;
    }else{
        document.getElementById('female3').checked=true;
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
    window.setTimeout(function(){
    window.history.back();
    }, 500);
}

function investmentValue(){
    var lot = document.getElementById('c_lot_price_sqm').value;
    var house = document.getElementById('c_house_price_sqm').value;
    var fence = document.getElementById('c_fence_price_sqm').value;

    if (lot != "0" && house == "0" && fence == "0"){
        document.getElementById('lotonly').checked=true;
        return;
    }else if (lot == "0" && house != "0" && fence == "0"){
        document.getElementById('houseonly').checked=true;
        return;
    }else if (lot == "0" && house == "0" && fence != "0"){
        document.getElementById('fenceonly').checked=true;
        return;
    }else if (lot != "0" && house != "0" && fence == "0"){
        document.getElementById('houseandlot').checked=true;
        return;
    }else if (lot != "0" && house != "0" && fence != "0"){
        document.getElementById('houseandlot').checked=true;
        document.getElementById('fenceonly').checked=true;
        return;
    }else if (lot != "0" && house == "0" && fence != "0"){
        document.getElementById('lotonly').checked=true;
        document.getElementById('fenceonly').checked=true;
        return;
    }else if (lot == "0" && house != "0" && fence != "0"){
        document.getElementById('houseonly').checked=true;
        document.getElementById('fenceonly').checked=true;
        return;
    }
}

function getDP(){
    var dp = document.getElementById('down_percent').value;

    if(dp == 20){
        document.getElementById('dp_20').checked=true;
    }else if(dp == 30){
        document.getElementById('dp_30').checked=true;
    }else if(dp == 'FDP'){
        document.getElementById('fdp').checked=true;
    }else{
        document.getElementById('others').checked=true;
        document.getElementById('txtothers').value=dp+"%";
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

function getFCP(){
    var fence_price_sqm = document.getElementById('c_fence_price_sqm').value;
    var fence_area = document.getElementById('c_linear').value;

    var fcp = fence_price_sqm * fence_area;

    document.getElementById('c_fcp').value = fcp;
}

function getRelationship(){
    var rel = document.getElementById('rel1').value;
    if(rel==1){
        document.getElementById('and1').checked=true;
    }else if(rel==2){
        document.getElementById('spouses1').checked=true;
    }else if(rel==3){
        document.getElementById('marriedto1').checked=true;
    }else if(rel==4){
        document.getElementById('minor1').checked=true;
    }else{
        document.getElementById('and1').checked=false;
        document.getElementById('spouses1').checked=false;
        document.getElementById('marriedto1').checked=false;
        document.getElementById('minor1').checked=false;
    }
    getRelationship2();
    getRelationship3();
}
function getRelationship2(){
    var rel = document.getElementById('rel2').value;
    if(rel==1){
        document.getElementById('and2').checked=true;
    }else if(rel==2){
        document.getElementById('spouses2').checked=true;
    }else if(rel==3){
        document.getElementById('marriedto2').checked=true;
    }else if(rel==4){
        document.getElementById('minor2').checked=true;
    }else{
        document.getElementById('and2').checked=false;
        document.getElementById('spouses2').checked=false;
        document.getElementById('marriedto2').checked=false;
        document.getElementById('minor2').checked=false;
    }
}
function getRelationship3(){
    var rel = document.getElementById('rel3').value;
    if(rel==1){
        document.getElementById('and3').checked=true;
    }else if(rel==2){
        document.getElementById('spouses3').checked=true;
    }else if(rel==3){
        document.getElementById('marriedto3').checked=true;
    }else if(rel==4){
        document.getElementById('minor3').checked=true;
    }else{
        document.getElementById('and3').checked=false;
        document.getElementById('spouses3').checked=false;
        document.getElementById('marriedto3').checked=false;
        document.getElementById('minor3').checked=false;
    }
}
</script>

</body>
<?php
}} 