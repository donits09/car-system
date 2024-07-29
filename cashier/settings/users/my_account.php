<?php
session_start();
require_once('../../../inc/check_session.php');
check_user_group(3);

require_once('../../../config.php');
include('../../../inc/navbar.php');  
include('../../../inc/header.php');
?>

<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url; ?>dist/css/table.css">

<style>
    .card-header{
        font-size: 15px;
        font-weight: bold;
        background-color: white !important;
        color: black !important;
    }
    #btnsave{
        width: 100% !important;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    My Account Details
                </div>
                <div class="card-body">
                    <form id="user_details">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="c_employee_code" class="form-label">Employee ID</label>
                            <input type="text" class="form-control txt" style="background-color: white;" id="c_employee_code" name="c_employee_code" readonly>
                        </div>
                        <div class="form-group">
                            <label for="c_realname" class="form-label">Name</label>
                            <input type="text" class="form-control txt" style="background-color: white;" id="c_realname" name="c_realname" readonly>
                        </div>
                        <div class="form-group">
                            <label for="c_department" class="form-label">Department</label>
                            <input type="text" class="form-control txt" style="background-color: white;" id="c_department" name="c_department" readonly>
                        </div>
                        <div class="form-group">
                            <label for="c_password" class="form-label">Password</label>
                            <input type="password" class="form-control txt" id="c_password" name="c_password">
                        </div>
                        <button type="submit" class="btn btn-primary" id="btnsave">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../../dist/js/my_account.js"></script>
<?php include('../../../inc/footer.php'); ?>
