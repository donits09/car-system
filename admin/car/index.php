
<?php
    include('../../config.php');
    include('../../inc/header.php');    

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

<link rel="stylesheet" href="../../dist/css/index.css">
<div class="cont_wrapper">
    <div class="pd-ltr-20">
        <div class="card">
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
                            <option value="last-name">Last Name</option>
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
                    <input type="number" id="acc_no" name="acc_no" class="form-control" maxlength="11">
                </div>
                <div class="col-md-3 form-group">
                    <button type="submit" class="btn btn-primary"><i class="dw dw-search"></i> Find Account</button>
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
                    <input type="number" id="block" name="block" class="form-control">
                </div>
                <div class="col-md-2 form-group">
                    <label for="lot" class="control-label">Lot</label>
                    <input type="number" id="lot" name="lot" class="form-control">
                </div>
                <div class="col-md-2 form-group">
                    <button type="submit" class="btn btn-primary"><i class="dw dw-search"></i> Search Location</button>
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
                    <button type="submit" class="btn btn-primary"><i class="dw dw-search"></i> Find Surname</button>
                </div>
            </div>
        </form>
        </div>
        <br>
        <div class="container mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="buyer-details-tab" data-bs-toggle="tab" href="#buyer-details" role="tab" aria-controls="buyer-details" aria-selected="true">Buyer's Details</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="car-list-tab" data-bs-toggle="tab" href="#car-list" role="tab" aria-controls="car-list" aria-selected="false">Car List</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="buyer-details" role="tabpanel" aria-labelledby="buyer-details-tab">
                    <div class="card mt-3">
                        <div class="pd-20">
                            <h2 class="text-blue h4">Buyer's Details</h2>
                        </div>
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
                        <div class="pd-20">
                            <h2 class="text-blue h4">Car List</h2>
                            <button id="create_new" class="btn btn-flat btn-primary" style="font-size:14px;">
                                <span class="fas fa-plus"></span>&nbsp;&nbsp;Create New
                            </button>
                            <hr>
                        </div>
                        <div class="container">
                        <table class="table table-bordered table-striped" id="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account No.</th>
                                    <th>Payment Type</th>
                                    <th>Amount</th>
                                    <th>CAR No.</th>
                                    <th>Pay Date</th>
                                    <th>Encoder</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="car-list-body">
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include ('main_modals.php'); ?>
    </div>
</div>
<script src="../../dist/js/index.js"></script>
<script src="../../dist/js/car_list.js"></script>
