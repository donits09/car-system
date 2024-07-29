<?php 
    include('../../../config.php');

    $c_department = '';
    $status = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_department = "SELECT * FROM t_emp_department WHERE id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_department);
        odbc_execute($stmt, array($accountId));

        if ($result = odbc_fetch_array($stmt)) {
            $id = $result["id"];
            $c_department = $result["c_department"];
            $status = $result["status"];
        }
    } 
?>
<form id="department-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="form-group">
        <label for="c_department">Department</label>
        <input type="text" class="form-control" id="c_department" name="c_department" value="<?php echo htmlspecialchars($c_department) ?>" oninput="validateAlphaNumericInput(event)" required>
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
<script src="../../../dist/js/manage_department.js"></script>