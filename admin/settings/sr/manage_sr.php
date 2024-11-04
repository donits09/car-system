<?php 
    session_start(); // Start the session
    require_once('../../../inc/check_session.php');
    include('../../../config.php');

    $id = null;
    $date_created = date('Y-m-d');
    $c_time = date('H:i:s');
    $requestor = '';
    $nature_of_request = '';
    $assigned_to = '';
    $notes = '';
    $c_status = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_sr_query = "SELECT * FROM t_service_requests WHERE id = ?";
        $srId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_sr_query);
        odbc_execute($stmt, array($srId));

        if ($result = odbc_fetch_array($stmt)) {
            $id = $result["id"];
            $date_created =  $result["date_created"];
            $c_time =  $result["c_time"];
            $requestor =  $result["requestor"];
            $nature_of_request =  $result["nature_of_request"];
            $assigned_to =  $result["assigned_to"];
            $notes = $result["notes"];
            $c_status =  $result["c_status"];
        }
    } 
?>
<style>
#btnsave{
    width: 100% !important;
}
</style>
<form id="sr-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="form-group">
        <label for="assigned_to">Assigned to:</label>
        <input type="text" class="form-control" id="assigned_to" name="assigned_to" value="Information Technologies" readonly>
    </div>
    <div class="form-group">
        <label for="nature_of_request">Request for</label>
        <select class="form-control" id="nature_of_request" name="nature_of_request" required>
            <option value="">Select an Option</option> 
            <option value="1" <?php echo ($nature_of_request == '1') ? 'selected' : ''; ?>>Payment Transfer</option>
        </select>
    </div>
    <div class="form-group">
        <label for="c_status">Status</label>
        <select class="form-control" id="c_status" name="c_status" required>
            <option value="0" <?php echo ($c_status == '0') ? 'selected' : ''; ?>>Open</option>
            <option value="1" <?php echo ($c_status == '1') ? 'selected' : ''; ?>>Processing</option>
            <option value="2" <?php echo ($c_status == '2') ? 'selected' : ''; ?>>Resolved</option>
            <option value="3" <?php echo ($c_status == '3') ? 'selected' : ''; ?>>Closed</option>
        </select>
    </div>
    <div class="col-md-12">
        <label for="notes" class="form-label">Notes </label>
        <textarea class="form-control txt" rows="5" cols="50" id="notes" name="notes"><?php echo htmlspecialchars($notes) ?></textarea>
    </div>
    <div class="col-md-12">
        <label for="encoder">Requestor</label>
        <input type="hidden" id="requestor" name="requestor" value="<?php echo $_SESSION['username'] ?>" readonly>
        <?php
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            $requestor = $requestor;
        } else {
            $requestor = $_SESSION['username'];
        }
        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$requestor'";
        $results = odbc_exec($conn, $get_encoder_details_qry);

        if ($encoder = odbc_fetch_array($results)) {
            $realname = $encoder["c_realname"];
        }
        ?>
        <input type="text" class="form-control" value="<?php echo $realname ?>" readonly>
        <input type="hidden" class="form-control" id="date_created" name="date_created" value="<?php echo $date_created ?>" readonly>
        <input type="hidden" class="form-control" id="c_time" name="c_time" value="<?php echo $c_time ?>" readonly>
        <hr>
    </div>
    <button type="submit" class="btn btn-primary" id="btnsave">Save</button>
</form>
<script>
$(document).ready(function() {
        $('#sr-form').on('submit', function(e) {
            e.preventDefault(); 
            var formData = $(this).serialize(); 
            $.ajax({
            url: '<?php echo base_url; ?>classes/Master.php?f=save_sr',
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
                        $('#createCarModal').modal('hide');
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
                $('#sr-form').data('formSubmitting', false);
            }
        });
    });
});
</script>
<script src="../../../dist/js/manage_sr.js"></script>
