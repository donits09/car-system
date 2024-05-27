<head>
    <link rel="stylesheet" href="css/print_ra_b1_without_add_cost.css">
    <script src="../build/js/print.js" type="text/javascript"></script>
</head>
<style>
    .ref_no{
        float:right;
        font-size:10px;
    }
</style>
<body onload="loadAll()">
<div class="ref_no"><b>Reference No. : </b><?php echo $ref_no; ?></div>
<div class="card-body" style="margin-top:3px;">
    <div class="doc_title">Name and Contact details of Purchaser's Spouse or Co-Owner - Details must be consistent will all documents</div>
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
                        <input id="single" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">SINGLE<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="married" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">MARRIED<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="widowed" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">WIDOWED<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="separated" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">SEPARATED<label>
                        </div>
                        <input type="hidden" id="civil_status" value="<?php echo $c_civil_status; ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" id="small_title1" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:</label>
                    <div class="chkboxes">
                        <div style="float:left;margin-left:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="male" type="radio" name="gender1" />
                        </div>
                        <div style="float:left">
                            <label class="light">&nbsp;M<label>
                        </div>
                        <div style="float:left;margin-left:2px;margin-top:3px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input id="female" type="radio" name="gender1" />
                        </div>
                        <div style="float:left">
                            <label class="light">&nbsp;F<label>
                        </div>
                        <input type="hidden" id="gender" value="<?php echo $c_sex; ?>"> 
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
        <div class="col-md-12" style="margin-bottom:5px; margin-top:3px;">
            <div style="float:left;margin-right:2px;margin-top:3px;">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:5px;">And<label>
            </div>
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:5px;">Spouses<label>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
            <label style="font-weight:normal;margin-bottom:5px;">Married To<label>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:5px;">Minor/Represented by Legal Guardian<label>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="doc_title">Name and Contact details of Purchaser's Spouse or Co-Owner - Details must be consistent will all documents</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Last Name:</label>
                    <input type="text" value="" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Suffix Name:</label>
                    <input type="text" value="" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">First Name:</label>
                    <input type="text" value="" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Middle Name:</label>
                    <input type="text" value="" class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Citizenship:</label>
                    <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label" id="small_title">Civil Status:</label>
                    <div class="chkboxes">
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        <input id="single" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">SINGLE<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="married" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">MARRIED<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="widowed" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">WIDOWED<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="separated" type="radio" name="chkOption1">
                        </div>
                        <div style="float:left">
                            <label class="light">SEPARATED<label>
                        </div>
                        <input type="hidden" id="civil_status" value="<?php echo $c_civil_status; ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" id="small_title1" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gender:</label>
                    <div class="chkboxes">
                        <div style="float:left;margin-left:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="male" type="radio" name="gender1" />
                        </div>
                        <div style="float:left">
                            <label class="light">&nbsp;M<label>
                        </div>
                        <div style="float:left;margin-left:2px;margin-top:3px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input id="female" type="radio" name="gender1" />
                        </div>
                        <div style="float:left">
                            <label class="light">&nbsp;F<label>
                        </div>
                        <input type="hidden" id="gender" value="<?php echo $c_sex; ?>"> 
                    </div> 
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Age:</label>
                    <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Birthdate (YYYY-MM-DD):</label>
                    <input type="date" value=""  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Type of Valid ID presented:</label>
                    <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">TIN #:</label>
                    <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Email Address:</label>
                    <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Viber Account:</label>
                    <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                <label class="control-label" id="lbl_space">Contact Number:</label><br>	
                <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="form-group">
                    <label class="control-label">Residential/Billing Address:</label>
                    <input type="text" value="" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Area Code:</label>
                    <input type="text" value=""  class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom:-10px;">
        <div class="col-md-9">
            <div class="form-group">
                <label class="control-label">Address Abroad:</label>
                <input type="text" value=""  class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Contact Number Abroad:</label>
                <input type="text" value=""  class="form-control form-control-sm" style="margin-bottom:-10px;"> 
            </div>
        </div>
        <div class="col-md-12" style="margin-bottom:5px; margin-top:3px;">
            <div style="float:left;margin-right:2px;margin-top:3px;">
                <input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:5px;">And<label>
            </div>
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:5px;">Spouses<label>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
            <label style="font-weight:normal;margin-bottom:5px;">Married To<label>
            </div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <div style="float:left;margin-right:2px;margin-top:3px;">
            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
            </div>
            <div style="float:left">
                <label style="font-weight:normal;margin-bottom:5px;">Minor/Represented by Legal Guardian<label>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="card-body" id="card-payment">
    <input type="hidden" value="<?php echo $c_lid; ?>" id="lid">
    <?php
        $l_lid=$c_lid;
        $l_phase = intval(substr($l_lid, 0,3));
        $l_block = intval(substr($l_lid, 3,3));
        $l_lot = intval(substr($l_lid, 6,8));
    ?>

    <input type="hidden" value="<?php echo $l_phase; ?>" id="txtPhase">

    <div class="investment_value" id="bottom_space">INVESTMENT VALUE</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-0.5">
                <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp<label class="control-label" style="margin-top:-10px;">Project:</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" id="final_phase" style="font-size:8px;">
                </div>
            </div>
            <div class="col-md-0.5">
                <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp<label class="control-label" style="margin-top:-10px;">Block:</label>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" value="<?php echo $l_block; ?>">
                </div>
            </div>
            <div class="col-md-0.5">
                <div class="form-group">
                &nbsp&nbsp&nbsp&nbsp<label class="control-label" style="margin-top:-10px;">Lot:</label>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" value="<?php echo $l_lot; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="control-label" id="options" style="margin-top:-5px;">
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                            <input id="lotonly" type="checkbox" name="chkOption3" />
                        </div>
                        <div style="float:left">
                            <label class="light" style="font-weight:normal;">Lot Only<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="houseonly" type="checkbox" name="chkOption3" />
                        </div>
                        <div style="float:left">
                            <label class="light" style="font-weight:normal;">House Only<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="houseandlot" type="checkbox" name="chkOption3" />
                        </div>
                        <div style="float:left">
                            <label class="light" style="font-weight:normal;">House & Lot<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="fenceonly" type="checkbox" name="chkOption3" />
                        </div>
                        <div style="float:left">
                            <label class="light" style="font-weight:normal;">Fence<label>
                        </div>
                        <div style="float:left;margin-right:2px;margin-top:3px;">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="addcost" type="checkbox" name="chkOption3" />
                        </div>
                        <div style="float:left">
                            <label class="light" style="font-weight:normal;">Add Cost<label>
                        </div>
                        <input type="hidden" id="investment_type" name="investment_type" value="<?php echo isset($c_investment_type) ? $c_investment_type : ''; ?>" >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid" style="margin-top:-18px;">
        <div class="row">
            <div class="col-md-1" id="tcp_coverage">
                <label class="control-label" style="margin-bottom:5px;">LOT</label><br>
                <label class="control-label" style="margin-bottom:5px;">HOUSE</label><br>
                <label class="control-label" style="margin-bottom:5px;">FENCE</label><br>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label2" style="font-weight:normal;">Lot Area:</label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" id="c_lot_area" name="c_lot_area" value="<?php echo $c_lot_area; ?>" class="form-control form-control-sm">
                        <input type="text" id="c_lot_area_disp" name="c_lot_area_disp" value="<?php echo $c_lot_area; ?> SQM" class="form-control form-control-sm" style="margin-bottom:5px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label2" style="font-weight:normal;">Model:</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text"  id="c_mod" value="<?php echo $c_house; ?>" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label2" id="lbliv" style="font-weight:normal;">Linear Meter:</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="c_linear" value="<?php echo $c_linear; ?>" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                <div class="col-md-4">
                        <label class="control-label2" id="lbliv" style="font-weight:normal;">Price/SQM:</label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" id="c_lot_price_sqm" name="c_lot_price_sqm" value="<?php echo $c_price_sqm; ?>" class="form-control form-control-sm">
                        <input type="text" id="c_lot_price_sqm_disp" name="c_lot_price_sqm_disp" value="<?php echo number_format($c_price_sqm,2); ?>" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label2" id="lbliv" style="font-weight:normal;">Floor Area:</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="c_house_flr_area" name="c_house_flr_area" value="<?php echo $c_floor_area; ?>" class="form-control form-control-sm">
                        <input type="hidden" id="c_house_price_sqm" name="c_house_price_sqm" value="<?php echo $c_house_price_sqm; ?>" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label2" id="lbliv" style="font-weight:normal;">Price/LM:</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="c_fence_price_sqm" name="c_fence_price_sqm" value="<?php echo $c_fence_price_sqm; ?>" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                <div class="col-md-6">
                        <label class="control-label2" id="lbliv">Lot Contract Price: </label>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" id="c_lcp" name="c_lcp" class="form-control form-control-sm">
                        <input type="text" id="c_lcp_disp" name="c_lcp_disp" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label2" id="lbliv">House Contact Price: </label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="c_hcp" name="c_hcp" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label2" id="lbliv">Fence Contract Price: </label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="c_fcp" name="c_fcp" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card-body" style="margin-top:-1px;padding-top:3px; padding-bottom:0px;">
    <table>
        <tr>
        <!-- <div style="margin-top:10px;"></div> -->
        <div class="row" style="padding-top:5px;">
            <div class="col-md-2">
                <label class="control-label" style="margin-left:8px;font-size:10px;margin-bottom:-5px;">PROCESSING FEE:</label>
            </div>
            <div class="col-md-4">
                <input type="text" id="c_processing_fee" name="c_processing_fee" value="<?php echo number_format($c_processing_fee,2) ?>" class="form-control form-control-sm" style="margin-bottom:-5px;">
            </div>
            <div class="col-md-2">
                <label class="control-label" style="font-size:10px;">PF/mo.:</label>
            </div>
            <div class="col-md-4">
                <input type="text" id="c_pf_mo" name="c_pf_mo" value="<?php echo number_format($c_pf_mo,2) ?>" class="form-control form-control-sm" style="margin-right:25px;padding-left:10px;width:242px;">
            </div>
        </div>
        <div class="row" style="margin-top:5px;margin-bottom:-5px;padding-top:5px;">
            <div class="col-md-2.5">
                <label class="control-label" style="margin-left:16px;font-size:10px;margin-right:28px;">LESS: Applied Disc:</label>
            </div>
            <div class="col-md-4">
                <input type="text" id="c_less" name="c_less" value="<?php echo number_format($c_less,2) ?>" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="control-label" style="font-size:10px;">TOTAL CONTRACT PRICE: </label>
                <div class="vatlbl1" style="font-style:italic;margin-top:-5px;margin-left:25px;font-size:9px;">VAT Inclusive</div>
            </div>
            <div class="col-md-3" style="margin-left:-4px;">
                <input type="text" id="c_tcp" name="c_tcp" value="<?php echo number_format($c_tcp,2) ?>" class="form-control form-control-sm" style="padding-left:10px;width:180px;">
            </div>
        </div>
        </tr>
    </table>
</div>
<div class="card-body" id="payment_details">
    <div class="row">
        <div class="dp_sched">
            <div class="titles" style="margin-top:0px;">DOWN PAYMENT SCHEDULE</div>
            <div class="dp_container">
                <div class="row">
                    <input type="hidden" value="<?php echo $down_percent; ?>" id="down_percent">
                    <div style="float:left;margin-right:2px;margin-top:2px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;<input id="dp_20" type="checkbox" name="chkOption4"/>
                    </div>
                    <div style="float:left">
                        <label class="light">20%</label>
                    </div>
                    <div style="float:left;margin-right:2px;margin-top:2px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="dp_30" type="checkbox" name="chkOption4"/>
                    </div>
                    <div style="float:left">
                        <label class="light">30%<label>
                    </div>
                    <div style="float:left;margin-right:2px;margin-top:2px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="fdp" type="checkbox" name="chkOption4"/>
                    </div>
                    <div style="float:left">
                        <label class="light">FDP<label>
                    </div>
                    <div style="float:left;margin-right:2px;margin-top:2px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="others" type="checkbox" name="chkOption4"/>
                    </div>
                    <div style="float:left">
                        <label class="light"><input type="text" id="txtothers" class="form-control form-control-sm" style="margin-bottom:-18px;"><label>
                    </div>
                </div>
                <div class="row" style="margin-top:-5px;">
                    <div class="col-md-12">
                        <label class="control-label2" style="margin-bottom:5px;margin-top:0px;">Down Payment Amount:</label>
                        <input type="text" id="dp_amt" name="dp_amt" value="<?php echo number_format($c_net_dp,2) ?>" class="form-control form-control-sm" style="margin-bottom:-18px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label2" id="lbl_dp" style="margin-bottom:5px;">Less: Res. Money:</label>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label2" id="lbl_dp" style="margin-bottom:5px;">Payable in (mos):</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" value="<?php echo number_format($c_reservation,2) ?>" class="form-control form-control-sm" style="margin-bottom:-18px;">
                    </div>
                    <div class="col-md-6">
                        <input type="text" value="<?php echo $c_no_payment; ?>" class="form-control form-control-sm" style="margin-bottom:-18px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="lbl_dp" style="margin-bottom:5px;">Monthly Down Payment:</label>
                        <input type="text" value="<?php echo number_format($c_monthly_down,2) ?>" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="title_sub">Monthly Down Payments</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label2" id="lbl_dp" style="margin-top:3px;margin-bottom:5px;">PF/mo:</label>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label2" id="lbl_dp" style="margin-top:3px;margin-bottom:5px;">GCF/mo:</label>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label2" id="lbl_dp" style="margin-top:3px;margin-bottom:5px;">STL/mo:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" value="" class="form-control form-control-sm" style="margin-bottom:-18px;">
                    </div>
                    <div class="col-md-4">
                        <input type="text" value="" class="form-control form-control-sm" style="margin-bottom:-18px;">
                    </div>
                    <div class="col-md-4">
                        <input type="text" value="" class="form-control form-control-sm" style="margin-bottom:-18px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="lbl_dp" style="margin-bottom:5px;">Total Monthly Payment:</label>
                        <input type="text" value="<?php echo number_format($c_monthly_payment,2) ?>" class="form-control form-control-sm" style="margin-bottom:-18px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="lbl_dp" style="margin-bottom:5px;">Commencing Date:</label>
                        <input type="text" value="" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </div>
        <div class="ma">
        <div class="titles2">MONTHLY AMORTIZATION</div>
        <div class="ma_sub"> *Based on In-House Financing pending Bank approval of Housing Loan</div>
            <div class="dp_container2">
                <div class="row">
                <div style="float:left;margin-right:2px;margin-top:2px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
                    </div>
                    <div style="float:left">
                        <label class="light">MDP-BF<label>
                    </div>
                    <div style="float:left;margin-right:2px;margin-top:2px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
                    </div>
                    <div style="float:left">
                        <label class="light">FULL DP-DFC<label>
                    </div>
                    <div style="float:left;margin-right:2px;margin-top:2px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="chkOption1" type="checkbox" name="chkOption1" />
                    </div>
                    <div style="float:left">
                        <label class="light">CASH<label>
                    </div>
                </div>
                <div class="row" style="margin-top:-5px;">
                    <div class="col-md-8" >
                    <label class="control-label2" style="margin-top:-10px;margin-bottom:17px;"> &nbsp;Amount to be financed:</label>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label2" style="margin-top:-12px;margin-bottom:12px;">In Years:</label>
                    </div>
                </div>
                <div class="row" style="margin-top:-12px;margin-bottom:6px;">
                    <div class="col-md-8">
                        <input type="text" value="<?php echo number_format($amt_fnanced,2) ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <input type="text" value="<?php echo $c_terms; ?>" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <label class="control-label2" id="lbl_dp" style="margin-top:7px;margin-bottom:5px;"> Interest Rate:</label>
                    </div>
                    <div class="col-md-7">
                        <label class="control-label2" id="lbl_dp" style="margin-top:7px;margin-bottom:5px;">Fixed Factor:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" value="<?php echo $interest_rate; ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-7">
                        <input type="text" value="<?php echo $c_fixed_factor; ?>" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="lbl_dp" style="margin-top:15px;margin-bottom:5px;">Monthly Amortization:</label>
                        <input type="text" value="" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="title_sub">Monthly Amortization</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label2" id="lbl_dp" style="margin-top:3px;margin-bottom:5px;">PF/mo:</label>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label2" id="lbl_dp" style="margin-top:3px;margin-bottom:5px;">STL/mo:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" value="" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6">
                        <input type="text" value="" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="lbl_dp" style="margin-top:14px;margin-bottom:5px;">Total Monthly Amortization:</label>
                        <input type="text" value="" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label2" id="lbl_dp" style="margin-top:13px;margin-bottom:4px;">Commencing Date:</label>
                        <input type="text" value="" id="monthly_due" class="form-control form-control-sm" style="margin-bottom:-5px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="sales" style="font-weight:normal;">
                <div class="titles3">SALES</div>
                <div class="first_table">
                <?php

                // output any connection error
                if ($conn->connect_error) {
                    die('Error : ('. $conn->connect_errno .') '. $conn->connect_error);
                }

                // the query
                $query = "SELECT * FROM t_csr_commission WHERE c_csr_no = $c_csr_no";

                // mysqli select query
                $results = $conn->query($query);

                if($results) {

                    print '<table class="table-bordered" id="table-bordered1"><thead><tr>
                            <th class="agent_position2">POSITION</th>
                            <th>AGENT</th>
                            <th class="signature_width2">SIGNATURE</th>
                        </tr></thead><tbody>';

                    while($row = $results->fetch_assoc()) {

                        print '
                            <tr>
                                <td>'.$row["c_position"].'</td>
                                <td>'.$row["c_agent"].'</td>
                                <td id="border_right_none"></td>
                            </tr>
                        ';
                    }
                    print '</tr></tbody></table>';
                } else {
                    echo "<p>There are no project sites to display.</p>";
                }
                $results->free();
                // $conn->close();
                ?>
                </div>
                <div class="second_table">
                    <table class="table-bordered">

                    <tbody>

                        <tr>
                            <td style="width:100px;">
                                <div class="row" id="sales_checkbox">
                                    <div style="float:left;margin-right:2px;margin-top:6px;">
                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
                                    </div>
                                    <div style="float:left;margin-top:3px;">
                                        <label style="font-weight:normal">REB<label>
                                    </div>
                                    <div style="float:left;margin-right:2px;margin-top:6px;">
                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="chkOption1" type="checkbox" name="chkOption1" />
                                    </div>
                                    <div style="float:left;margin-top:3px;">
                                        <label style="font-weight:normal">PC<label>
                                    </div>
                                </div>
                            </td>
                            <td style="width:171px;"></td>
                            <td id="border_right_none"></td>
                        </tr>
                        <tr>
                            <td>Employee Referral</td>
                            <td></td>
                            <td id="border_right_none"></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            <table width="100%" id="tblborder">
                <tr><td><div class="notes">I have read and understood the Guidelines and Policies for In-House Financing and Data Privacy Consent at the back page. I certify that all information given are true and correct.</div></td></tr>
                <tr><td><div class="client_conforme" style="font-weight:normal;font-size:9px;margin-bottom:8px;">Conforme:</div><td></tr>
                <tr><td>
                        <br>
                        <!-- <input type="text" class="buyers_name" value="<?php echo $c_b1_last_name; ?>, <?php echo $c_b1_first_name; ?> <?php echo $c_b1_middle_name; ?>"> -->
                        <input type="text" class="txtSignature" value="Client's Signature Over Printed Name">
                </td></tr>
                <tr><td><div class="rec_app" style="font-weight:normal;font-size:9px;">Recommending Approval:</div><td></tr>
                <tr><td><div class="coo_name" style="margin-top:10px;">PIA MARIE ISABELLE B. MADRID</div></td></tr>
                <tr><td class="txtSignature" style="font-weight:bold;margin-top:-5px;height:19px;line-height:10px;">Chief Operating Officer</td></tr>
            </table>      
        </div>
    </div>
</div>
<div class="row">
    <table class="rem">
        <tr>
            <td width="50%"><div style="background-color:black;color:white;text-align:left;font-weight:bold;margin-top:0px;padding-left:8px;font-size:12px;height:auto;margin-right:-25px;">REMARKS</div></td>
            <td width="30%"><div style="background-color:black;color:white;padding:0px;text-align:center;font-weight:bold;margin-top:0px;font-size:12px;height:auto;margin-right:-25px;">Checked & Verified</div></td>
            <td width="20%"><div style="background-color:black;color:white;padding:0px;text-align:center;font-weight:bold;margin-top:0px;font-size:12px;height:auto;">Cashier Validation</div></td>
        </tr>
    </table>
    <table class="tablerem">
        <tr>
            <td width="50%" class="withbd">
                <textarea style="width: 100%; max-width:98%; border:none; height:100%; margin-left:1px; margin-top:3px;"><?php echo $remarks; ?></textarea>
            </td>
        </tr>
    </table>
    <table class="tablerem_checked_verfied">
        <tr>
            <td width="30%" class="withbd" style="padding-left:5px;">
                <label class="control-label" style="font-style:normal;font-size:12px;">Engineering</label><br>
                <label class="control-label" style="font-style:normal;font-weight:normal;">Date:</label>
            </td>
        </tr>
        <tr>
            <td width="30%" class="withbd" style="border-top:1px solid black;padding-left:5px;">
                <label class="control-label" style="font-style:normal;font-size:12px;">SMO</label><br>
                <label class="control-label" style="font-style:normal;font-weight:normal;padding-bottom:1.5px;">Date:</label>
            </td>
        </tr>
    </table>
    <table class="tablerem_cashier">
        <tr> 
        </tr>
    </table>
</div>
</body>

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

    let lcp = lot_price_sqm * lot_area;
    let lcp_disp = lcp.toLocaleString();
    

    document.getElementById('c_lcp').value = lcp;
    document.getElementById('c_lcp_disp').value = lcp_disp + ".00";
}

function getFCP(){
    var fence_price_sqm = document.getElementById('c_fence_price_sqm').value;
    var fence_area = document.getElementById('c_linear').value;

    var fcp = fence_price_sqm * fence_area;

    document.getElementById('c_fcp').value = fcp;
}

// function getDPAmout(){
//     var tcp = document.getElementById('c_tcp').value;
//     var ans=0;

//     if(document.getElementById('dp_20').checked==true){
//         ans = tcp * 0.20;
//     }
//     document.getElementById('dp_amt').value=ans;
// }

function loadAll(){
    getCivilStatus();
    getGender();
    getPhase();
    getLCP();
    getHCP();
    getFCP();
    getDP();
    investmentValue();
    printRA();
}
</script>
