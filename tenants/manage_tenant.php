<?php
session_start();
require_once('../inc/check_session.php');
require_once('../config.php');

$tenantId = '';
$c_account_no = null;
$c_last_name = '';
$c_first_name = '';
$c_middle_name = '';
$c_address = '';
$c_city_prov = '';
$c_zip_code = '';
$c_tel_no = '';
$c_lot_area = '';
$c_price_sqm = '';
$c_mobile_no = '';
$c_email = '';
$c_civil_status = '';
$c_birthday = date('Y-m-d');
$c_sex = '';
$c_employment_status = '';
$c_remarks = '';

    $l_site = isset($_GET["phase"]) ? $_GET["phase"] : '';
    $l_block = isset($_GET["block"]) ? $_GET["block"] : '';
    $l_lot = isset($_GET["lot"]) ? $_GET["lot"] : '' ;

    $l_acc_no = isset($_GET["acc_no"]) ? $_GET["acc_no"] : '' ;
    $last_name = isset($_GET["last_name"]) ? $_GET["last_name"] : '' ;

    if ($l_acc_no != ''){
        $l_find = $l_acc_no;
        
    }elseif($last_name != ''){
        $l_find = $last_name;
    }else{
    if ($l_block == ''):
        $l_find = sprintf("%03d", (int)$l_site);
    else:
        if ($l_lot == ''):
            $l_find = sprintf("%03d%03d", (int)$l_site, (int)$l_block);	
        else:
            $l_find = sprintf("%03d%03d%02d", (int)$l_site, (int)$l_block, (int)$l_lot);
            
        endif;
    endif;
    }
?>
<?php 
if (isset($_GET['id']) && $_GET['id'] > 0) {
    echo htmlspecialchars($_GET['id']);
    $get_tenant_query = "SELECT * FROM t_tenant_accounts WHERE id = ?";
    $tenantId = $_GET['id'];
    $stmt = odbc_prepare($conn, $get_tenant_query);
    odbc_execute($stmt, array($tenantId));

    if ($result = odbc_fetch_array($stmt)) {
        $c_account_no = $result["c_account_no"];
        $c_last_name = $result["c_last_name"];
        $c_first_name = $result["c_first_name"];
        $c_middle_name = $result["c_middle_name"];
        $c_address = $result["c_address"];
        $c_city_prov = $result["c_city_prov"];
        $c_zip_code = $result["c_zip_code"];
        $c_tel_no = $result["c_tel_no"];
        $c_mobile_no = $result["c_mobile_no"];
        $c_lot_area = $result["c_lot_area"];
        $c_price_sqm = $result["c_price_sqm"];
        $c_email = $result["c_email"];
        $c_civil_status = $result["c_civil_status"];
        $c_sex = $result["c_sex"];
        $c_birthday = $result["c_birthday"];
        $c_remarks = $result["c_remarks"];
        $c_employment_status = $result["c_employment_status"];
        $c_encoded_by = $result["c_encoded_by"];
        $c_phase = $result["c_phase"];
        $c_block = $result["c_block"];
        $c_lot = $result["c_lot"];
    }
} else if (isset($_GET['c_account_no']) && $_GET['c_account_no'] > 0) {
    $c_account_no = $_GET['c_account_no'];
}
?>

<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
<body>
<div class="card mt-3">
    <form class="row g-3" id="tenantForm">
    <div class="col-md-12">
    <label for="acc_no" class="form-label">Account No.</label>
    <input type="text" class="form-control txt" id="tenant_acc_no" name="tenant_acc_no" value="<?php echo htmlspecialchars($c_account_no) ?>" readonly>
</div>
<div class="col-md-4 form-group">
    <label for="c_phase" class="control-label">Phase</label>
    <select name="c_phase" id="c_phase" class="custom-select form-control" autocomplete="off">
        <option value="" selected>--SELECT--</option>
        <?php
        $sql = "SELECT * FROM t_projects ORDER BY c_acronym";
        $results = odbc_exec($conn, $sql);
        while ($row = odbc_fetch_array($results)) {
            $selected = ''; 
            if ($row['c_code'] == $c_phase) {
                $selected = 'selected'; 
            }
            echo '<option value="' . $row['c_code'] . '" data-code="' . $row['c_code'] . '" ' . $selected . '>' . $row['c_acronym'] . '</option>';
        }
        ?>
    </select>
</div>
<div class="col-md-4 form-group">
    <label for="c_block" class="control-label">Block</label>
    <input type="number" id="c_block" name="c_block" class="form-control" value="<?php echo htmlspecialchars($c_block); ?>" oninput="concatenateValues();" onblur="padBlockValue(); limitInputLength(this, 3);">
</div>
<div class="col-md-4 form-group">
    <label for="c_lot" class="control-label">Lot</label>
    <input type="number" id="c_lot" name="c_lot" class="form-control" 
           value="<?php echo htmlspecialchars($c_lot); ?>" 
           oninput="concatenateValues();" 
           onblur="padBlockValue(); limitInputLength(this, 2);">
</div>
<script>
    function limitInputLength(element, maxLength) {
        if (element.value.length > maxLength) {
            element.value = element.value.slice(0, maxLength);
        }
    }
    document.getElementById('c_block').addEventListener('input', function() {
        limitInputLength(this, 3);
    });

    document.getElementById('c_lot').addEventListener('input', function() {
        limitInputLength(this, 2);
    });
    function padBlockValue(value) {
        return value.toString().padStart(3, '0');
    }
    function padLotValue(value) {
        return value.toString().padStart(2, '0');
    }

    function concatenateValues() {
        var selectedPhaseOption = document.getElementById('c_phase').options[document.getElementById('c_phase').selectedIndex];
        var selectedCode = selectedPhaseOption.getAttribute('data-code') || '';
        var block = padBlockValue(document.getElementById('c_block').value || '');
        var lot = padLotValue(document.getElementById('c_lot').value || ''); 
        var concatenatedValue = selectedCode + block + lot + 800;
        document.getElementById('tenant_acc_no').value = concatenatedValue;
    }
    document.getElementById('c_phase').addEventListener('change', function() {
        concatenateValues();
    });
    document.getElementById('c_block').addEventListener('input', concatenateValues);
    document.getElementById('c_lot').addEventListener('input', concatenateValues);
</script>
<form id="tenant-form" method="post" action="">
        <input type="hidden" name="id" value="<?php echo $tenantId ? $tenantId : '' ?>">
        <div class="col-md-4">
            <label for="lname" class="form-label">Last Name</label>
            <input type="text" class="form-control txt" id="tenant_lname" name="tenant_lname" value="<?php echo htmlspecialchars($c_last_name) ?>">
        </div>
        <div class="col-md-4">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" class="form-control txt" id="tenant_fname" name="tenant_fname" value="<?php echo htmlspecialchars($c_first_name) ?>">
        </div>
        <div class="col-md-4">
            <label for="mname" class="form-label">Middle Name</label>
            <input type="text" class="form-control txt" id="tenant_mname" name="tenant_mname" value="<?php echo htmlspecialchars($c_middle_name) ?>">
        </div>
        <div class="col-md-4">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control txt" id="tenant_address" name="tenant_address" value="<?php echo htmlspecialchars($c_address) ?>">
        </div>
        <div class="col-md-4">
            <label for="city_prov" class="form-label">City/Province</label>
            <input type="text" class="form-control txt" id="tenant_city_prov" name="tenant_city_prov" value="<?php echo htmlspecialchars($c_city_prov) ?>">
        </div>
        <div class="col-md-4">
            <label for="zip" class="form-label">Zip Code</label>
            <input type="text" class="form-control txt" id="tenant_zip" name="tenant_zip" value="<?php echo htmlspecialchars($c_zip_code) ?>">
        </div>
        <div class="col-md-4">
            <label for="tel_no" class="form-label">Tel No.</label>
            <input type="text" class="form-control txt" id="tenant_tel" name="tenant_tel" value="<?php echo htmlspecialchars($c_tel_no) ?>">
        </div>
        <div class="col-md-4">
            <label for="mobile" class="form-label">Mobile #</label>
            <input type="text" class="form-control txt" id="tenant_mobile" name="tenant_mobile" value="<?php echo htmlspecialchars($c_mobile_no) ?>">
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email Address</label>
            <input type="text" class="form-control txt" id="tenant_email" name="tenant_email" value="<?php echo htmlspecialchars($c_email) ?>">
        </div>
        <div class="col-md-4">
            <label for="civil" class="form-label">Civil Status</label>
            <select class="form-control" id="tenant_civil" name="tenant_civil">
                <option value="">Select Civil Status</option>
                <option value="1" <?php echo $c_civil_status == 1 ? 'selected' : ''; ?>>Married</option>
                <option value="2" <?php echo $c_civil_status == 2 ? 'selected' : ''; ?>>Separated</option>
                <option value="3" <?php echo $c_civil_status == 3 ? 'selected' : ''; ?>>Single</option>
                <option value="4" <?php echo $c_civil_status == 4 ? 'selected' : ''; ?>>Widowed</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="birthday" class="form-label">Birthday</label>
            <input type="date" class="form-control txt" id="tenant_birthday" name="tenant_birthday" value="<?php echo htmlspecialchars($c_birthday) ?>">
        </div>
        <div class="col-md-4">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-control" id="tenant_gender" name="tenant_gender">
                <option value="">Select Gender</option>
                <option value="1" <?php echo $c_sex == 1 ? 'selected' : ''; ?>>Female</option>
                <option value="2" <?php echo $c_sex == 2 ? 'selected' : ''; ?>>Male</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="emp_status" class="form-label">Employment Status</label>
            <select class="form-control" id="tenant_emp_status" name="tenant_emp_status">
                <option value="">Select Employment Status</option> <!-- Blank option -->
                <option value="1" <?php echo $c_employment_status == 1 ? 'selected' : ''; ?>>Unemployed</option>
                <option value="2" <?php echo $c_employment_status == 2 ? 'selected' : ''; ?>>Employed</option>
                <option value="3" <?php echo $c_employment_status == 3 ? 'selected' : ''; ?>>Self-employed</option>
                <option value="4" <?php echo $c_employment_status == 4 ? 'selected' : ''; ?>>OCW</option>
                <option value="5" <?php echo $c_employment_status == 5 ? 'selected' : ''; ?>>Retired</option>
                <option value="6" <?php echo $c_employment_status == 6 ? 'selected' : ''; ?>>Others</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="area" class="form-label">Lot Area</label>
            <input type="text" class="form-control txt" id="tenant_area" name="tenant_area" value="<?php echo htmlspecialchars($c_lot_area) ?>">
        </div>
        <div class="col-md-4">
            <label for="price_sqm" class="form-label">Price Sqm</label>
            <input type="text" class="form-control txt" id="tenant_sqm" name="tenant_sqm" value="<?php echo htmlspecialchars($c_price_sqm) ?>">
        </div>
        <div class="col-md-12">
            <label for="remarks" class="form-label">
                Remarks 
            </label>
            <textarea class="form-control txt" rows="5" cols="50" id="tenant_remarks" name="tenant_remarks"><?php echo htmlspecialchars($c_remarks) ?></textarea>
        </div>
        <div class="col-md-6">
            <label for="encoder">Encoded by</label>
            <input type="hidden" id="c_encoded_by" name="c_encoded_by" value="<?php echo  $_SESSION['username'] ?>" readonly>
            <?php
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                $c_encoded_by == $c_encoded_by;
            }else{
                $c_encoded_by = $_SESSION['username'];
            }
            $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
            $results = odbc_exec($conn, $get_encoder_details_qry);

            if ($encoder = odbc_fetch_array($results)) {
                $realname = $encoder["c_realname"];
            }
            ?>
            <input type="text" class="form-control" value="<?php echo $realname ?>" readonly>
        </div>
        <br>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary" id="btnsave" style="width:100%;">Save</button>
        </div>
    </form>
</div>
<?php include ('modals/main_modals.php'); ?>
</body>
<script>
$(document).ready(function() {
        $('#tenantForm').on('submit', function(e) {
            e.preventDefault(); 
            var formData = $(this).serialize(); 
            $.ajax({
            url: '<?php echo base_url; ?>classes/Master.php?f=save_tenant',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(err) {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                console.log(resp);
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    setTimeout(function() {
                        $('#createTenantModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        location.reload();
                    }, 1000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else if (resp && resp.status === 'not_found') {
                    alert_toast("An error occurred.", 'error');
                } else {
                    alert_toast("An error occurred.", 'error');
                }
                end_loader();
            },
            complete: function() {
                $('#atap-form').data('formSubmitting', false);
            }
        });
    });
});
</script>
<script src="../dist/js/table.js"></script>
<script src="../dist/js/index.js"></script>
<script src="../dist/js/export_scripts.js"></script>
</body>