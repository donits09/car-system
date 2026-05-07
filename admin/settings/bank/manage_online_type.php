<?php 
    include('../../../config.php');

    $c_account_no = null;
    $c_bank_type = '';
    $status = '';
    $c_name = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_type_query = "SELECT * FROM t_car_online WHERE id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_type_query);
        odbc_execute($stmt, array($accountId));

        if ($result = odbc_fetch_array($stmt)) {
            $id = $result["id"];
            $c_bank_type = $result["c_bank_type"];
            $status = $result["status"];
            $c_name = $result["c_name"];
        }
    } 
?>

<style>
#btnsave{
    width: 100% !important;
}
</style>
<form id="bank-type-form">
<!-- <form id="car-type-form"> -->
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="form-group">
        <label for="c_bank_type">Acronym</label>
        <input type="text" class="form-control" id="c_bank_type" name="c_bank_type" value="<?php echo htmlspecialchars($c_bank_type) ?>" oninput="validateAlphaNumericInput(event)" required>
    </div>
    <div class="form-group">
        <label for="c_name">Name</label>
        <input type="text" class="form-control" id="c_name" name="c_name" value="<?php echo htmlspecialchars($c_name) ?>" oninput="validateAlphaNumericInput(event)" required>
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
<script src="../../../dist/js/manage_online_type.js"></script>