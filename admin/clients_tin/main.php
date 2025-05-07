<?php
session_start();
require_once('../../inc/check_session.php');
require_once('../../config.php');

$c_account_no = null;
$c_b1_lname = '';
$c_b1_fname = '';
$c_b1_mname = '';
$c_b1_tin   = '';
$c_b1_status = '';
$c_b2_lname = '';
$c_b2_fname = '';
$c_b2_mname = '';
$c_b2_tin   = '';
$c_b2_status = '';
?>

<?php 
if (isset($_GET['c_account_no']) && $_GET['c_account_no'] > 0) {
    $get_tenant_query = "
        SELECT 
            t_buyers_account.*,
            t_clients_tin.*
        FROM t_buyers_account 
        LEFT JOIN t_clients_tin 
        ON CAST(t_buyers_account.c_account_no AS TEXT) = t_clients_tin.c_account_no
        WHERE t_buyers_account.c_account_no = ?
    ";

    $stmt = odbc_prepare($conn, $get_tenant_query);
    odbc_execute($stmt, [$_GET['c_account_no']]);

    if ($result = odbc_fetch_array($stmt)) {
        $c_account_no = $result["c_account_no"];
        $c_b1_lname = $result["c_b1_last_name"];
        $c_b1_fname = $result["c_b1_first_name"];
        $c_b1_mname = $result["c_b1_middle_name"];
        $c_b1_tin   = $result["c_b1_tin"];
        $c_b1_status = $result["c_b1_status"];
        $c_b2_lname = $result["c_b2_last_name"];
        $c_b2_fname = $result["c_b2_first_name"];
        $c_b2_mname = $result["c_b2_middle_name"];
        $c_b2_tin   = $result["c_b2_tin"];
        $c_b2_status = $result["c_b2_status"];
    }
}
?>

<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">

<body>
<div class="card mt-3">
    <form class="row g-3" id="tinForm">
        <div class="col-md-2">
            <label for="acc_no" class="form-label">Account No.</label>
            <input type="text" class="form-control txt" id="tin_acc_no" name="tin_acc_no" value="<?php echo htmlspecialchars($c_account_no) ?>" readonly required>
        </div>

        <!-- Buyer 1 Fields -->
        <div class="col-md-12 mt-3">
        </div>

        <div class="col-md-2">
            <label for="tin_b1_lname" class="form-label">Last Name (Buyer 1)</label>
            <input type="text" class="form-control txt" id="tin_b1_lname" name="tin_b1_lname" value="<?php echo htmlspecialchars($c_b1_lname) ?>" readonly required>
        </div>
        <div class="col-md-2">
            <label for="tin_b1_fname" class="form-label">First Name (Buyer 1)</label>
            <input type="text" class="form-control txt" id="tin_b1_fname" name="tin_b1_fname" value="<?php echo htmlspecialchars($c_b1_fname) ?>" readonly required>
        </div>
        <div class="col-md-2">
            <label for="tin_b1_mname" class="form-label">Middle Name (Buyer 1)</label>
            <input type="text" class="form-control txt" id="tin_b1_mname" name="tin_b1_mname" value="<?php echo htmlspecialchars($c_b1_mname) ?>" readonly required>
        </div>
        <div class="col-md-2">
            <label for="tin_b1_no" class="form-label">TIN Number</label>
            <input type="text" class="form-control txt" id="tin_b1_no" name="tin_b1_no" value="<?php echo htmlspecialchars($c_b1_tin) ?>" required>
        </div>
        <div class="col-md-2">
            <label for="tin_b1_status" class="form-label">Status</label>
            <input type="text" class="form-control txt" id="tin_b1_status" name="tin_b1_status" value="<?php echo htmlspecialchars($c_b1_status) ?>" readonly required>
        </div>

        <!-- Buyer 2 Fields -->
        <div class="col-md-12 mt-3">
        </div>
        
        <div class="col-md-2">
            <label for="tin_b2_lname" class="form-label">Last Name (Buyer 2)</label>
            <input type="text" class="form-control txt" id="tin_b2_lname" name="tin_b2_lname" value="<?php echo htmlspecialchars($c_b2_lname) ?>" readonly required>
        </div>
        <div class="col-md-2">
            <label for="tin_b2_fname" class="form-label">First Name (Buyer 2)</label>
            <input type="text" class="form-control txt" id="tin_b2_fname" name="tin_b2_fname" value="<?php echo htmlspecialchars($c_b2_fname) ?>" readonly required>
        </div>
        <div class="col-md-2">
            <label for="tin_b2_mname" class="form-label">Middle Name (Buyer 2)</label>
            <input type="text" class="form-control txt" id="tin_b2_mname" name="tin_b2_mname" value="<?php echo htmlspecialchars($c_b2_mname) ?>" readonly required>
        </div>
        <div class="col-md-2">
            <label for="tin_b2_no" class="form-label">TIN Number</label>
            <input type="text" class="form-control txt" id="tin_b2_no" name="tin_b2_no" value="<?php echo htmlspecialchars($c_b2_tin) ?>" required>
        </div>
        <div class="col-md-2">
            <label for="tin_b2_status" class="form-label">Status</label>
            <input type="text" class="form-control txt" id="tin_b2_status" name="tin_b2_status" value="<?php echo htmlspecialchars($c_b2_status) ?>" readonly required>
        </div>

        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary" id="btnsave" style="float:right;">Save</button>
        </div>
    </form>
</div>


<?php include ('../modals/main_modals.php'); ?>

<script>
$(document).ready(function() {
    $('#tinForm').on('submit', function(e) {
        e.preventDefault(); 
        var formData = $(this).serialize(); 
        $.ajax({
            url: '<?php echo base_url; ?>classes/Master.php?f=save_tin',
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
                        /* $('#createTinModal').modal('hide'); */
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        location.reload();
                    }, 1000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
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
