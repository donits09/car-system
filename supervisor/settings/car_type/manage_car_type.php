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
<style>
#btnsave{
    width: 100% !important;
}
</style>
<form id="car-type-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="form-group">
        <label for="c_payment_type">Payment Type</label>
        <input type="text" class="form-control" id="c_payment_type" name="c_payment_type" value="<?php echo htmlspecialchars($c_payment_type) ?>" oninput="validateAlphaNumericInput(event)" required>
    </div>
    <div class="form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status" required>
            <option value="0" <?php echo ($status == '0') ? 'selected' : ''; ?>>Active</option>
            <option value="1" <?php echo ($status == '1') ? 'selected' : ''; ?>>Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" id="btnsave">Save</button>
</form>
<script src="../../../dist/js/manage_car_type.js"></script>