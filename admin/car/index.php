<?php
session_start();

require_once('../../inc/check_session.php');
check_user_group(1);

require_once('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     

?>
<?php
    $l_site = isset($_GET["phase"]) ? $_GET["phase"] : '';
    $l_block = isset($_GET["block"]) ? $_GET["block"] : '';
    $l_lot = isset($_GET["lot"]) ? $_GET["lot"] : '' ;

    $l_acc_no = isset($_GET["acc_no"]) ? $_GET["acc_no"] : '' ;
    $last_name = isset($_GET["last_name"]) ? $_GET["last_name"] : '' ;

    if ($l_acc_no != ''){
        $l_find = $l_acc_no;
        
    }elseif($last_name != ''){
        $l_find = $last_name;
    }else{
    if ($l_block == ''):
        $l_find = sprintf("%03d", (int)$l_site);
    else:
        if ($l_lot == ''):
            $l_find = sprintf("%03d%03d", (int)$l_site, (int)$l_block);	
        else:
            $l_find = sprintf("%03d%03d%02d", (int)$l_site, (int)$l_block, (int)$l_lot);
            
        endif;
    endif;
    }
?>
<link rel="stylesheet" href="../../dist/css/table.css">
<link rel="stylesheet" href="../../dist/css/index.css">
<style>
    .table-container {
        margin-bottom: 20px;
    }

    .table-container label {
        margin-right: 10px; 
    }

    .table-container input[type="text"] {
        width: 150px;
        padding: 5px; 
    }
    label{
        color:black;
    }

    .container {
    width: 100%;
    height:auto;
    }
    
    body{
        width:100%;
    }
    body.modal-open {
        overflow: hidden;
        padding-right: 0 !important;
    }
    #buyer_loc{
        border:none;
        background-color: transparent;
        font-size: 14px;
        font-style: italic;
        font-weight: bold;
        color:black;
    }
    #b_details{
        text-align: left;
        border: none;
    }
</style>
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <div class="pd-20">
        <!-- Dropdown 'to Par -->
        <table class="table">
            <form id="search-type-form">
                <div class="row align-items-end">
                    <div class="col-md-3 form-group">
                        <label for="search_type" class="control-label">Search By:</label>
                        <select id="search_type" class="custom-select form-control" onchange="toggleForm()">
                            <option value="" selected>--SELECT--</option>
                            <option value="account">Account #</option>
                            <option value="location">Location</option>
                            <option value="last-name">Name</option>
                        </select>
                    </div>
                </div>
            </form>
        </table>

        <!-- By Account # -->
        <form id="account-form" class="filter-form" style="display: none;" onsubmit="return searchBuyer('account')">
            <hr>
            <div class="row align-items-end">
                <div class="col-md-3 form-group">
                    <label for="acc_no" class="control-label">Account #</label>
                    <input type="number" id="acc_no" name="acc_no" class="form-control" maxlength="11" oninput="validateNumberInput(event)">
                </div>
                <div class="col-md-3 form-group">
                    <button type="submit" id="searchAcc" class="btn btn-primary" onclick="calculateTotalAmount()">
                        <span class="fa fa-search"></span> Search Account
                    </button>
                </div>
            </div>
        </form>

        <!-- By Location -->
        <form id="location-form" class="filter-form" style="display: none;" onsubmit="return searchBuyer('location')">
            <hr>
            <div class="row align-items-end">
                <div class="col-md-3 form-group">
                    <label for="phase" class="control-label">Phase</label>
                    <select name="phase" id="phase" class="custom-select form-control" autocomplete="off">
                        <option value="" selected>--SELECT--</option>
                        <?php
                        $sql = "SELECT * FROM t_projects ORDER BY c_acronym";
                        $results = odbc_exec($conn, $sql);
                        while ($row = odbc_fetch_array($results)) {
                            echo '<option value="' . $row['c_code'] . '">' . $row['c_acronym'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label for="block" class="control-label">Block</label>
                    <input type="number" id="block" name="block" class="form-control" min="0" step="1" oninput="validateNumberInput(event)">
                </div>
                <div class="col-md-2 form-group">
                    <label for="lot" class="control-label">Lot</label>
                    <input type="number" id="lot" name="lot" class="form-control" min="0" step="1" oninput="validateNumberInput(event)">
                </div>
                <div class="col-md-2 form-group">
                    <button type="submit" id="searchLoc" class="btn btn-primary" onclick="calculateTotalAmount()"><span class="fa fa-search"></span> Search Location</button>
                </div>
            </div>
        </form>

        <!-- By Last Name -->
        <form id="last-name-form" class="filter-form" style="display: none;" onsubmit="return searchBuyer('last-name')">
            <hr>
            <div class="row align-items-end">
                <div class="col-md-3 form-group">
                    <label for="last_name" class="control-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control">
                </div>
                <div class="col-md-3 form-group">
                    <label for="first_name" class="control-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control">
                </div>
                <div class="col-md-3 form-group">
                    <button type="submit" id="searchName" class="btn btn-primary" onclick="calculateTotalAmount()"><span class="fa fa-search"></span> Search Name</button>
                </div>
            </div>
        </form>
        </div>
        <div class="container mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="buyer-details-tab" data-toggle="tab" href="#buyer-details" role="tab" aria-controls="buyer-details" aria-selected="true">Buyer's Details</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="car-list-tab" data-toggle="tab" href="#car-list" role="tab" aria-controls="car-list" aria-selected="false">Car List</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="buyer-details" role="tabpanel" aria-labelledby="buyer-details-tab">
                    <div class="card mt-3">
                        <table id="b_details">
                            <tr>
                                <td style="width: 15%;border-top:none;border-bottom:none;border-left:none;">
                                    <h2 class="text-blue h4">Buyer's Details</h2>
                                </td>
                                <td style="border-top:none;border-bottom:none;border-right:none;">
                                    <input type="text" class="form-control" id="buyer_loc" name="buyer_loc" readonly>
                                </td>
                            </tr>
                        </table>
                        <hr>
                        <div class="container">
                            <form class="row g-3">
                                <div class="col-md-4">
                                    <label for="acc_no" class="form-label">Account No.</label>
                                    <input type="text" class="form-control" id="buyer_acc_no" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_of_sale" class="form-label">Date of Sale</label>
                                    <input type="text" class="form-control" id="buyer_date_of_sale" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="acc_status" class="form-label">Account Status</label>
                                    <input type="text" class="form-control" id="buyer_acc_status" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="buyer_lname" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="buyer_fname" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="mname" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="buyer_mname" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="buyer_address" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control" rows="10" cols="50" id="buyer_remarks" readonly></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="car-list" role="tabpanel" aria-labelledby="car-list-tab">
                    <div class="card mt-3">
                        <div class="container">
                            <h2 class="text-blue h4">Car List</h2>
                            <hr>
                            <button type="button" id="create_new" data-account-no="" class="btn btn-primary" data-toggle="modal" href="javascript:void(0)" data-target="#createCarModal" onclick="updateAccountNo()">
                                <span class="fa fa-edit"></span> Create New CAR
                            </button>
                            <a id="export_csv" class="btn btn-flat btn-success" href="javascript:void(0)">
                                <span class="fa fa-download"></span> Export as CSV
                            </a>
                            <a id="export_pdf" class="btn btn-flat btn-danger" href="javascript:void(0)">
                                <span class="fa fa-download"></span> Export as PDF
                            </a>
                            <hr>
                                <div class="container">
                                    <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label for="accno" class="form-label">Acc #</label>
                                        <input type="text" class="form-control" id="accno" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="fullname" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="fullname" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="car_buyer_loc" class="form-label">Location</label>
                                        <input type="text" class="form-control" id="car_buyer_loc" name="car_buyer_loc" readonly>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <table>
                                    <tr>
                                        <td style="width:80%;border:none;">
                                            <label for="remarks" class="form-label" style="float:right;">Search:</label>
                                        </td>
                                        <td style="width:20%;border:none;">
                                            <input type="text" id="searchInput" onkeyup="filterTable()" class="form-control">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="table-container">
                                <table class="table table-bordered table-striped" id="car-list-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Account No.</th>
                                            <th>CAR No.</th>
                                            <th>Payment Type</th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Amount</th>
                                            <th>MoP</th>
                                            <th>Transaction Date</th>
                                            <th>Pay Date</th>
                                            <th>Encoder</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="car-list-body">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-right">Total Amount:</th>
                                            <th id="totalAmount" class="text-center"></th>
                                            <th colspan="5"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php include ('../modals/main_modals.php'); ?>
        </div>
    </div>
</div>
</body>
<script>
    function updateAccountNo() {
        var accountNo = $('#buyer_acc_no').val();
        console.log(accountNo);
        $('#create_new').data('account-no', accountNo); 
    }
</script>
<script>
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        updateAccountNo();
    });

    $('#buyer_acc_no').on('change', function() {
        updateAccountNo();
    });

    document.getElementById("searchInput").addEventListener("input", function() {
        filterTable();
    });

    $('#createCarModal').on('hidden.bs.modal', function () {
        $('body').css('padding-right', '0');
    });

    document.getElementById('search_type').addEventListener('change', function() {
    var forms = document.querySelectorAll('.filter-form');
    forms.forEach(function(form) {
        form.style.display = 'none';
    });

    var selectedType = this.value;
    if (selectedType) {
        document.getElementById(selectedType + '-form').style.display = 'block';
    }
    
    document.getElementById("searchAcc").addEventListener("click", function(event) {
        searchAndCalculateTotal(event, 'account');
    });

    document.getElementById("searchLoc").addEventListener("click", function(event) {
        searchAndCalculateTotal(event, 'location');
    });

    document.getElementById("searchName").addEventListener("click", function(event) {
        searchAndCalculateTotal(event, 'last-name');
    });

});

</script>
<script>
    $(document).ready(function() {
    function loadModal(title, url, modalId) {
        start_loader();
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $(modalId + ' .modal-body').html(response);
                $(modalId + ' .modal-title').text(title);
                $(modalId).modal('show');
                end_loader();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred while loading data.");
                end_loader();
            }
        });
    }

    $('#create_new').click(function() {
        var accountNo = $(this).data('account-no');
        loadModal('Create New Car', 'manage_car.php?c_account_no=' + accountNo, '#createCarModal');
    });

    $(document).on('click', '.edit_data', function() {
        var accountId = $(this).data('id');
        var accountNo = $(this).data('account-no');
    
        if (!accountNo) {
            loadModal('Edit Car Details', 'manage_other_car.php?id=' + accountId, '#createCarModal');
        } else {
            loadModal('Edit Car Details', 'manage_car.php?id=' + accountId, '#createCarModal');
        }
    });

    $(document).on('click', '.view_data', function() {
        var accountId = $(this).data('id');
        loadModal('Car Details', 'view_car.php?id=' + accountId, '#viewModal');
    });

    $('#create_other_new').click(function() {
        loadModal('Create New Car', 'manage_other_car.php', '#createCarModal');
    });

    $(document).on('click', '.delete_data', function() {
        var carId = $(this).data('id');
        var carNo = $(this).data('car-no');
        _conf("Are you sure you want to delete this car permanently?", delete_car, [carId, carNo]);
    });

    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };
});

$(document).ready(function() {
    calculateTotalAmount();
});

</script>
<script>
function delete_car(carId, carNo) {
    start_loader();
    $.ajax({
        url: "../../classes/Master.php?f=delete_car",
        method: "POST",
        data: { carId: carId, carNo: carNo },
        dataType: "json",
        error: function(err) {
            console.log(err);
            alert_toast("An error occurred.", 'error');
            end_loader();
        },
        success: function(resp) {
            if (resp && resp.status === 'success') {
                alert_toast(resp.msg, 'success');
                setTimeout(function() {
                    //location.reload();
                    $('#confirm_modal').modal('hide'); 
                    $('body').removeClass('modal-open'); 
                    $('.modal-backdrop').remove(); 
                    updateCarList(); 
                    $('.delete_data[data-id="' + carId + '"]').closest('tr').remove();
                }, 1000);
            } else if (resp && resp.status === 'failed' && resp.err) {
                alert_toast("An error occurred: " + resp.err, 'error');
            } else {
                alert_toast("An unexpected error occurred", 'error');
            }
            end_loader();
        }
    });
}

</script>
<script src="../../dist/js/table.js"></script>
<script src="../../dist/js/index.js"></script>
<!-- <script src="../../dist/js/car_list.js"></script> -->
<script src="../../dist/js/export_scripts.js"></script>
<script src="../../dist/js/manage_car.js"></script>
<?php include('../../inc/footer.php'); ?>