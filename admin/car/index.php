<?php 
    include('../../config.php');
    include('../../inc/header.php'); 
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title"><b><i>CAR Encoding</i></b></h3>
        <div class="card-tools">
            <button id="create_new" class="btn btn-flat btn-primary" style="font-size:14px;">
                <span class="fas fa-plus"></span>&nbsp;&nbsp;Create New
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
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
                <tbody>
                    <?php 
                    $i = 1;
                    $car_list = "SELECT * FROM t_car_payment ORDER BY c_car_paydate ASC";
                    $car_result = odbc_exec($conn, $car_list);
                    while ($row = odbc_fetch_array($car_result)): 
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td class="text-center"><?php echo $row['c_account_no']; ?></td>
                        <td class="text-center"><?php echo $row['c_car_type']; ?></td>
                        <td class="text-center"><?php echo $row['c_car_amount']; ?></td>
                        <td class="text-center"><?php echo $row['c_car_no']; ?></td>
                        <td class="text-center"><?php echo $row['c_car_paydate']; ?></td>
                        <td class="text-center"><?php echo $row['c_encoded_by']; ?></td>
                        <td align="center">
                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                    <span class="fa fa-eye text-primary"></span> View
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item edit_data" href="javascript:void(0)" 
                                   data-id="<?php echo $row['id']; ?>" 
                                   data-account-no="<?php echo $row['c_account_no']; ?>" 
                                   data-payment-type="<?php echo $row['c_car_type']; ?>" 
                                   data-amount="<?php echo $row['c_car_amount']; ?>" 
                                   data-car-no="<?php echo $row['c_car_no']; ?>" 
                                   data-pay-date="<?php echo $row['c_car_paydate']; ?>" 
                                   data-encoder="<?php echo $row['c_encoded_by']; ?>">
                                    <span class="fa fa-edit text-primary"></span> Edit
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">
                                    <span class="fa fa-trash text-danger"></span> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">CAR Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically here -->
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCarModal" tabindex="-1" role="dialog" aria-labelledby="createCarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCarModalLabel">Create New Car</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded dynamically here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Add save button or any other buttons here -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Car Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing car details -->
                <form id="edit-car-form">
                    <div class="form-group">
                        <label for="edit-account-no">Account No.</label>
                        <input type="text" class="form-control" id="edit-c-account-no" name="c_account_no" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-payment-type">Payment Type</label>
                        <select class="form-control" id="edit-c-car-type" name="c_car_type" required>
                            <option value=""></option>
                            <!-- Options will be dynamically populated via PHP or JavaScript -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-amount">Amount</label>
                        <input type="text" class="form-control" id="edit-c-car-amount" name="c_car_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-car-no">CAR No.</label>
                        <input type="text" class="form-control" id="edit-c-car-no" name="c_car_no" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-pay-date">Pay Date</label>
                        <input type="date" class="form-control" id="edit-c-car-paydate" name="c_car_paydate" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-encoder">Encoder</label>
                        <input type="text" class="form-control" id="edit-c-encoded-by" name="c_encoded_by" required>
                    </div>
                    <input type="hidden" id="edit-id" name="id">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="confirm_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm_modal_label">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Modal body content goes here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="../../dist/js/index.js"></script>

</html>
