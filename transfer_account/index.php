<?php 
session_start();
require_once('../inc/check_session.php');
check_user_group(1);
include('../config.php');
include('../inc/navbar.php');    
include('../inc/header.php');     
?>
<link href="<?php echo base_url; ?>dist/css/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url; ?>dist/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css">
<style>
    .btn.btn-flat.btn-default.btn-sm.dropdown-toggle.dropdown-icon {
        margin: 0; 
        padding: 5px 10px; 
        width: auto; 
    }
    .dropdown-menu {
        top: auto;
        transform: translate3d(0, 0, 0); 
    }
    .search-box {
        margin: 20px 0;
        text-align: right;
    }
    .search-box input {
        width: 200px;
        padding: 5px;
    }
    #createTransferModal .modal-dialog{
        width: 60% !important;
        max-width: 60%;
        height:50%;
        margin-top: 0;
    }
</style>
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <div class="pd-20">
            <h2 class="text-blue h4">List of Accounts</h2>
            <hr>
        </div>
        <div class="search-box">
            <label for="search-account">Search Account No.: </label>
            <input type="text" id="search-account" placeholder="Enter Account No.">
            <button id="search-btn" class="btn btn-primary">Search</button>
        </div>
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Account No.</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="acc-type-body">
                </tbody>
            </table>
        </div>
        <?php include ('modals/main_modals.php'); ?>
    </div>
</div>
<script src="../dist/js/table.js"></script>
<script>
$(document).ready(function() {
    $('#search-btn').on('click', function() {
        var searchAcc = $('#search-account').val(); 

        if (searchAcc.length > 0) {
            $.ajax({
                url: '<?php echo base_url ?>transfer_account/search_account.php',
                type: 'GET',
                data: { searchAcc: searchAcc },
                success: function(response) {
                    $('#acc-type-body').html(response); 
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: ", xhr.responseText);
                }
            });
        } else {
            $('#acc-type-body').html(''); 
        }
    });
});

</script>
</body>
<?php include('../inc/footer.php'); ?>
