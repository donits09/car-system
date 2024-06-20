<?php
    require_once('../../../config.php');
?>
<link rel="stylesheet" href="../../../dist/css/table.css">
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                <button onclick="closeModal2()" class="btn customized-modal"" data-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST">
                    <div class="mb-3">
                        <label for="employee_code" class="form-label">Employee Code</label>
                        <input type="number" class="form-control" id="c_employee_code" name="c_employee_code" oninput="validateAlphaNumericInput(event)" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="c_password" name="c_password" oninput="validateAlphaNumericInput(event)" required>
                    </div>
                    <div class="mb-3">
                        <label for="realname" class="form-label">Name</label>
                        <input type="text" class="form-control" id="c_realname" name="c_realname" oninput="validateAlphaNumericInput(event)" required>
                    </div>
                    <div class="mb-3">
                        <label for="group" class="form-label">Group</label>
                        <select class="form-control" id="c_group" name="c_group" required>
                            <option value="" disabled <?php echo !isset($row['c_group']) ? 'selected' : ''; ?>></option>
                            <option value="1" <?php echo (isset($row['c_group']) && $row['c_group'] == '1') ? 'selected' : ''; ?>>Admin</option>
                            <option value="2" <?php echo (isset($row['c_group']) && $row['c_group'] == '2') ? 'selected' : ''; ?>>Cashier</option>
                            <option value="3" <?php echo (isset($row['c_group']) && $row['c_group'] == '3') ? 'selected' : ''; ?>>Viewer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-control" id="c_department" name="c_department" required>
                            <option value="" disabled <?php echo !isset($row['c_department']) ? 'selected' : ''; ?>></option>
                            <option value="Information Technology" <?php echo (isset($row['c_department']) && $row['c_department'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                            <option value="Documentation" <?php echo (isset($row['c_department']) && $row['c_department'] == 'Documentation') ? 'selected' : ''; ?>>Documentation</option>
                            <option value="Treasury" <?php echo (isset($row['c_department']) && $row['c_department'] == 'Treasury') ? 'selected' : ''; ?>>Treasury</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button onclick="closeModal2()" class="btn customized-modal"" data-dismiss="modal" aria-label="Close">x</button>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    <input type="hidden" id="edit_user_id" name="id">
                    <div class="mb-3">
                        <label for="edit_employee_code" class="form-label">Employee Code</label>
                        <input type="text" class="form-control" id="edit_employee_code" name="c_employee_code" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="edit_password" name="c_password">
                        <small><i>Leave this blank if you dont want to change the password.</i></small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_realname" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_realname" name="c_realname" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_group" class="form-label">Group</label>
                        <select class="form-control" id="edit_group" name="c_group" required>
                            <option value="1">Admin</option>
                            <option value="2">Cashier</option>
                            <option value="3">Viewer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_department" class="form-label">Department</label>
                        <select class="form-control" id="edit_department" name="c_department" required>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Treasury">Treasury</option>
                            <option value="Documentation">Documentation</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button onclick="closeModal2()" class="btn customized-modal"" data-dismiss="modal" aria-label="Close">x</button>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user permanently?
            </div>  
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
<script>

function closeModal2() {
    $('#confirmDeleteModal').modal('hide');
    $('#editUserModal').modal('hide');
    $('#addUserModal').modal('hide');
  }
</script>
<script src="../../../dist/js/table.js"></script>
<script src="../../../dist/js/user.js"></script>
<!-- <script src="../../../dist/js/modals.js"></script> -->
