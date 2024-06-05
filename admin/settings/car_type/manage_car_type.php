<?php 
    include('../../../config.php');

    $c_account_no = null;
    $c_payment_type = '';
    $status = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_type_query = "SELECT * FROM t_car_type WHERE id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_type_query);
        odbc_execute($stmt, array($accountId));

        if ($result = odbc_fetch_array($stmt)) {
            $id = $result["id"];
            $c_payment_type = $result["c_payment_type"];
            $status = $result["status"];
        }
    } 
?>
<form id="car-type-form">
    <input type="text" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="form-group">
        <label for="c_payment_type">Payment Type</label>
        <input type="text" class="form-control" id="c_payment_type" name="c_payment_type" value="<?php echo htmlspecialchars($c_payment_type) ?>" required>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status" required>
            <option value="0" <?php echo ($status == '0') ? 'selected' : ''; ?>>Active</option>
            <option value="1" <?php echo ($status == '1') ? 'selected' : ''; ?>>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<script>
   $(document).ready(function() {
    $('#car-type-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);

        var confirmed = confirm('Are you sure you want to save the changes?');
        if (!confirmed) {
            return false;
        }

        start_loader();

        $.ajax({
            url: _base_url_+"classes/Master.php?f=save_car_type",
            data: new FormData(_this[0]),
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
                        location.reload();
                    }, 2000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }

        });
    });
});
</script>
