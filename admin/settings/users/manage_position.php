<?php 
    include('../../../config.php');

    $c_position = '';
    $status = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_emp_position = "SELECT * FROM t_emp_position WHERE id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_emp_position);
        odbc_execute($stmt, array($accountId));

        if ($result = odbc_fetch_array($stmt)) {
            $id = $result["id"];
            $c_position = $result["c_position"];
            $status = $result["status"];
        }
    } 
?>
<style>
#btnsave{
    width: 100% !important;
}
</style>
<form id="position-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="form-group">
        <label for="c_position">Position</label>
        <input type="text" class="form-control" id="c_position" name="c_position" value="<?php echo htmlspecialchars($c_position) ?>" oninput="validateAlphaNumericInput(event)" required>
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
<script src="../../../dist/js/manage_position.js"></script>