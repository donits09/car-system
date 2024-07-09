<?php
session_start();

require_once('../../inc/check_session.php');
check_user_group(1);

require_once('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');     

?>
<?php
    $c_remarks = '';
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
<div class="container mt-5" style="margin-bottom:50px;">
    <div class="card mt-3">
    <h2 class="text-blue h4">Cash Acknowledgement Receipt of Account</h2>
    <hr>
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
            <input type="hidden" id="username" class="form-control" value="<?php echo $username ?>">
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
                    <a class="nav-link" id="car-list-tab" data-toggle="tab" href="#car-list" role="tab" aria-controls="car-list" aria-selected="false">CAR List</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="atap-list-tab" data-toggle="tab" href="#atap-list" role="tab" aria-controls="atap-list" aria-selected="false">ATAP List</a>
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
                            <form class="row g-3" id="buyerForm">
                                <div class="col-md-4">
                                    <label for="acc_no" class="form-label">Account No.</label>
                                    <input type="text" class="form-control txt" id="buyer_acc_no" name="buyer_acc_no" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_of_sale" class="form-label">Date of Sale</label>
                                    <input type="text" class="form-control txt" id="buyer_date_of_sale" name="buyer_date_of_sale" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="acc_status" class="form-label">Account Status</label>
                                    <input type="text" class="form-control txt" id="buyer_acc_status" name="buyer_acc_status" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control txt" id="buyer_lname" name="buyer_lname" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input type="text" class="form-control txt" id="buyer_fname" name="buyer_fname" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="mname" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control txt" id="buyer_mname" name="buyer_mname" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="bal" class="form-label">Balance</label>
                                    <input type="text" class="form-control txt" id="buyer_bal" name="buyer_bal" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="tcp" class="form-label">Net TCP</label>
                                    <input type="text" class="form-control txt" id="buyer_tcp" name="buyer_tcp" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="ret" class="form-label">Account Option</label>
                                    <input type="text" class="form-control txt" id="buyer_ret" name="buyer_ret" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="text" class="form-control txt" id="buyer_email" name="buyer_email" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="mobile" class="form-label">Mobile #</label>
                                    <input type="text" class="form-control txt" id="buyer_mobile" name="buyer_mobile" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control txt" id="buyer_title" name="buyer_title" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control txt" id="buyer_address" name="buyer_address" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="remarks" class="form-label">
                                        Remarks 
                                        <span class="rem_note">
                                            (<span class="note">NOTE:</span> The Enter key is enabled only on the last line)
                                        </span>
                                    </label>
                                    <textarea class="form-control txt" rows="10" cols="50" id="buyer_remarks" name="buyer_remarks"><?php echo htmlspecialchars($c_remarks) ?></textarea>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="car-list" role="tabpanel" aria-labelledby="car-list-tab">
                    <div class="card mt-3">
                        <div class="container">
                            <h2 class="text-blue h4">CAR List</h2>
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
                                            <th>Bank</th>
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

                <div class="tab-pane fade" id="atap-list" role="tabpanel" aria-labelledby="atap-list-tab">
                    <div class="card mt-3">
                    <div class="container">
                            <h2 class="text-blue h4">ATAP List</h2>
                            <hr>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label for="accno" class="form-label">Acc #</label>
                                        <input type="text" class="form-control" id="atap_accno" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="fullname" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="atap_fullname" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="car_buyer_loc" class="form-label">Location</label>
                                        <input type="text" class="form-control" id="atap_car_buyer_loc" name="atap_car_buyer_loc" readonly>
                                    </div>
                                </div>
                                <br>
                                <hr>
                                <table>
                                    <tr>
                                        <td style="width:80%;border:none;">
                                            <label for="search" class="form-label" style="float:right;">Search:</label>
                                        </td>
                                        <td style="width:20%;border:none;">
                                            <input type="text" id="searchInputAtap" onkeyup="filterTableAtap()" class="form-control">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="table-container">
                                <table class="table table-bordered table-striped" id="atap-list-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Account No.</th>
                                            <th>ATAP No.</th>
                                            <th>Name</th>
                                            <th>Total Amount</th>
                                            <th>Transaction Date</th>
                                            <th>Status</th>
                                            <th>Encoder</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="atap-list-body">
                                        <?php include('../atap/fetch_atap_list.php'); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-right">Total Amount:</th>
                                            <th id="totalAtapAmount" class="text-center"></th>
                                            <th colspan="4"></th>
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

<!-- REMARKS FUNCTIONS -->
<script>
    document.getElementById('buyer_remarks').addEventListener('keydown', function(event) {
        const textarea = event.target;

        if (event.key === 'Enter') {
            const lines = textarea.value.split('\n');
            const caretPosition = textarea.selectionStart;

            let lineNumber = 0;
            let currentPosition = 0;
            for (let i = 0; i < lines.length; i++) {
                currentPosition += lines[i].length + 1; 
                if (caretPosition < currentPosition) {
                    lineNumber = i;
                    break;
                }
            }

            if (lineNumber < lines.length - 1) {
                event.preventDefault();
            }
        }

        if (event.key === 'Backspace') {
            const caretPosition = textarea.selectionStart;
            const textBeforeCaret = textarea.value.substring(0, caretPosition);

          
            const lastNewLineIndex = textBeforeCaret.lastIndexOf('\n');
            
            if (caretPosition === lastNewLineIndex + 1) {
                event.preventDefault();
                
                textarea.classList.add('red-glow');

                setTimeout(() => {
                    textarea.classList.remove('red-glow');
                }, 500);
            }
        }
    });
</script>
<script>
$(document).ready(function() {
    $('#buyerForm').submit(function(e) {
        e.preventDefault(); 

        start_loader();

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: "../../classes/Master.php?f=save_remarks",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
                alert_toast("An error occurred: " + error, 'error');
                end_loader(); 
            },
            success: function(resp) {
                console.log(resp);
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success'); 
                    $('#buyer_remarks').val(resp.remarks);
                } else {
                    alert_toast("An unexpected error occurred", 'error'); 
                }
                end_loader();
            }
        });
    });
});
</script>

<!-- GETTING OF ACCOUNT NO FOR PASSING -->
<script>
    function updateAccountNo() {
        var accountNo = $('#buyer_acc_no').val();
        console.log(accountNo);
        $('#create_new').data('account-no', accountNo); 
    }
</script>

<!-- USING OF ACCOUNT NO TO MAKE OTHER FUNCTIONS WORK DYNAMICALLY :))) -->
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

    
    document.getElementById("searchInputAtap").addEventListener("input", function() {
        filterTableAtap();
    });

    $('#createCarModal').on('hidden.bs.modal', function () {
        $('body').css('padding-right', '0');
    });

    // document.getElementById('search_type').addEventListener('change', function() {
    // var forms = document.querySelectorAll('.filter-form');
    // forms.forEach(function(form) {
    //     form.style.display = 'none';
    // });

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

</script>

<!-- CALLING OF MODAAAAAAALS (MERONG FOR ATAP AND FOR CAR ALSO. PINAGSAMA KO NA) -->
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

    $(document).on('click', '.view_data', function() {
        var accountId = $(this).data('id');
        loadModal('Car Details', 'view_car.php?id=' + accountId, '#viewModal');
    });

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

  
    $('#create_other_new').click(function() {
        loadModal('Create New Car', 'manage_other_car.php', '#createCarModal');
    });

    $(document).on('click', '.delete_data', function() {
        var carId = $(this).data('id');
        var carNo = $(this).data('car-no');
        _conf("Are you sure you want to cancel this car permanently?", delete_car, [carId, carNo]);
    });

    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };

    $(document).on('click', '.view_atap', function() {
        var atapId = $(this).data('id');
        var atapNo = $(this).data('no');
        loadModal('ATAP Details', '../atap/view_atap.php?id=' + atapId + '&no=' + atapNo, '#viewModal');
    });
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
function updateCarList() {
    const accountNo = document.getElementById('buyer_acc_no').value;
    fetch(`car_list.php?account_no=${accountNo}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('car-list-body').innerHTML = data;
            calculateTotalAmount();
        });
        calculateTotalAmount();
}
</script>
<script src="../../dist/js/table.js"></script>
<script src="../../dist/js/index.js"></script>
<script src="../../dist/js/atap_js/index_atap_cshr.js"></script>
<script src="../../dist/js/manage_car.js"></script>
<?php include('../../inc/footer.php'); ?>