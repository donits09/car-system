<?php 
    include('../config.php');
    //include('../inc/header.php');     
    $accountNo = isset($_GET['acc-no']) ? $_GET['acc-no'] : null;
    $lname = $fname = $mname = '';
    $new_acc = '';
    $old_acc = '';
    $notes_copy = '';
    $carPayments = [];
    if ($accountNo) {
        $get_acc_query = "SELECT * FROM t_buyers_account WHERE c_account_no = ?";
        $stmt = odbc_prepare($conn, $get_acc_query);
        odbc_execute($stmt, array($accountNo));

        if ($result = odbc_fetch_array($stmt)) {
            $lname = $result["c_b1_last_name"];
            $fname = $result["c_b1_first_name"];
            $mname = $result["c_b1_middle_name"];
        }
        $get_car_query = "SELECT * FROM t_car_payment WHERE c_account_no = ?";
        $stmt = odbc_prepare($conn, $get_car_query);
        odbc_execute($stmt, array($accountNo));

        while ($car = odbc_fetch_array($stmt)) {
            $carPayments[] = $car;
        }
    }
$c_date_transferred = date('Y-m-d');
$c_time_transferred = date('H:i:s');
?>
<style>
#btnsave{
    width: 100% !important;
}
.hidden_fields {
    border: none;
    background-color: transparent;
    cursor: default;
    pointer-events: none;
}
.hide_textbox{
    display:none;
}
.lbl{
    font-weight: bolder;
    font-size: 14px;
    font-style: italic;
}
.align-left {
    float: left;
}
.form-group{
    text-align: left;
}
@media (max-width: 768px) {
    #car-list-table th, #car-list-table td {
        padding: 8px;
        font-size: 12px;
    }
    #car-list-table th:nth-child(1),
    #car-list-table td:nth-child(1),
    #car-list-table th:nth-child(7),
    #car-list-table td:nth-child(7) {
        display: none; 
    }
}
</style>
<body>
<form id="car-type-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="table-responsive">
        <table class="align-left">
            <tr>
                <td>
                    <div class="form-group">
                        <label for="c_acc_no" class="lbl">Acc No: </label>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <?php echo htmlspecialchars($accountNo) ?>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="c_lname" class="lbl">Full Name: </label>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <?php echo htmlspecialchars($lname) ?>, <?php echo htmlspecialchars($fname) ?> <?php echo htmlspecialchars($mname) ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <label for="c_old_acc_no" class="lbl">Old Acc No: </label>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" class="form-control hidden_fields" id="old_acc_copy" name="old_acc_copy" value="<?php echo htmlspecialchars($accountNo) ?>" oninput="copyAccs()">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <label for="c_new_acc_no" class="lbl">New Acc No: </label>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" class="form-control" id="new_acc_copy" name="new_acc_copy" oninput="copyAccs()" style="background-color:whitesmoke" required>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="form-group">
        <label for="c_new_acc_no" class="lbl">Notes</label>
        <textarea class="form-control txt" rows="2" cols="50" id="notes_copy" name="notes_copy" oninput="copyAccs()"></textarea>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="car-list-table">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>CAR No.</th>
                    <th>Transaction Type</th>
                    <th>Remarks</th>
                    <th>Amount</th>
                    <th>Pay Date</th>
                    <th>Encoder</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="car-list-body">
                <?php 
                if (!empty($carPayments)) {
                    foreach ($carPayments as $index => $car) { ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><input type="text" id="c_car_type" name="c_car_type" class="hide_textbox" value="<?php echo $car['c_car_type'] ?>" readonly>
                            <input type="text" id="cid" name="cid" class="hide_textbox" value="<?php echo $car['id'] ?>" readonly>
                            <input type="text" id="c_car_no" name="c_car_no" class="hide_textbox" value="<?php echo $car['c_car_no'] ?>" readonly>
                            <input type="text" id="c_car_paydate" name="c_car_paydate" class="hide_textbox" value="<?php echo $car['c_car_paydate'] ?>" readonly>
                            <input type="text" id="c_car_amount" name="c_car_amount" class="hide_textbox" value="<?php echo $car['c_car_amount'] ?>" readonly>
                            <input type="text" id="c_encoder" name="c_encoder" class="hide_textbox" value="<?php echo $car['c_encoded_by'] ?>" readonly>
                            <input type="text" id="c_tran_date" name="c_tran_date" class="hide_textbox" value="<?php echo $car['c_tran_date'] ?>" readonly>
                            <input type="text" id="c_mop" name="c_mop" class="hide_textbox" value="<?php echo $car['c_mop'] ?>" readonly>
                            <input type="text" id="c_bank" name="c_bank" class="hide_textbox" value="<?php echo $car['c_bank'] ?>" readonly>
                            <input type="text" id="c_check_no" name="c_check_no" class="hide_textbox" value="<?php echo $car['c_check_no'] ?>" readonly>
                            <input type="text" id="c_remarks" name="c_remarks" class="hide_textbox" value="<?php echo $car['c_remarks'] ?>" readonly>
                            <input type="text" id="c_atap_no" name="c_atap_no" class="hide_textbox" value="<?php echo $car['c_atap_no'] ?>" readonly>
                            <input type="text" id="date_transferred" name="date_transferred" class="hide_textbox" value="<?php echo $c_date_transferred; ?>" readonly>
                            <input type="text" id="c_time_transferred" name="c_time_transferred" class="hide_textbox" value="<?php echo $c_time_transferred; ?>" readonly>
                            <input type="text" class="hide_textbox old_acc" name="old_acc" value="<?php echo $old_acc; ?>" id="old_acc">
                            <input type="text" class="hide_textbox new_acc" name="new_acc" value="<?php  echo $new_acc; ?>" id="new_acc">
                            <input type="text" class="hide_textbox notes" name="notes" value="<?php  echo $notes_copy; ?>" id="notes">
                            <input type="text" id="transferred_by" name="transferred_by" class="hide_textbox" value="<?php echo $car['c_encoded_by'] ?>" readonly>
                            <input type="text" id="id" name="id" class="hide_textbox" value="<?php echo $car['id'] ?>" readonly>
                            <?php echo htmlspecialchars($car['c_car_no']); ?></td>
                            <td><?php echo htmlspecialchars($car['c_car_type']); ?></td>
                            <td><?php echo htmlspecialchars($car['c_remarks']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($car['c_car_amount'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($car['c_car_paydate']); ?></td>
                            <?php
                                $c_encoded_by = $car['c_encoded_by'];
                                $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                                $results = odbc_exec($conn, $get_encoder_details_qry);

                                if ($encoder = odbc_fetch_array($results)) {
                                    $realname = $encoder["c_realname"];
                                }
                            ?>
                            <td class="text-center"><input type="text" class="hidden_fields" value="<?php echo $realname; ?>" readonly></td>
                            <td><input type="checkbox"></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr><td colspan="11" class="text-center">No car payments found</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <button type="button" class="btn btn-primary" id="save-button" style="width:100%;">Save</button>
</form>
</body>
<script>
function copyAccs() {
    var oldAcc = document.getElementById('old_acc_copy').value;
    var newAcc = document.getElementById('new_acc_copy').value;
    var notesCopy = document.getElementById('notes_copy').value;
    document.querySelectorAll('.old_acc').forEach(function(element) {
        element.value = oldAcc;
    });
    document.querySelectorAll('.new_acc').forEach(function(element) {
        element.value = newAcc;
    });
    document.querySelectorAll('.notes').forEach(function(element) {
        element.value = notesCopy;
    });
}
$(document).ready(function() {
    $('#save-button').off('click').on('click', function() {
        var selectedRows = [];
        var saveButton = $(this);  
        // saveButton.prop('disabled', true);  

        const oldAccCopy = document.getElementById('old_acc_copy').value;
        const newAccCopy = document.getElementById('new_acc_copy').value;
        const notesCopy = document.getElementById('notes_copy').value;

        if (oldAccCopy === newAccCopy) {
            alert_toast("Old and new account numbers are the same.", 'error');
            return;  
        }

        if (!newAccCopy || newAccCopy.trim() === "") {
            alert_toast("Please enter a new account number.", 'error');
            return;  
        }

        if (!notesCopy || notesCopy.trim() === "") {
            alert_toast("Please enter a note.", 'error');
            return;  
        }
        
        $('#car-list-body input[type="checkbox"]:checked').each(function() {
            var row = $(this).closest('tr');
            var rowData = {
                id: row.find('input[name="id"]').val(),
                old_acc: row.find('input[name="old_acc"]').val(),
                new_acc: row.find('input[name="new_acc"]').val(),
                c_car_type: row.find('input[name="c_car_type"]').val(),
                c_car_no: row.find('input[name="c_car_no"]').val(),
                c_car_paydate: row.find('input[name="c_car_paydate"]').val(),
                c_car_amount: row.find('input[name="c_car_amount"]').val(),
                c_encoder: row.find('input[name="c_encoder"]').val(),
                c_tran_date: row.find('input[name="c_tran_date"]').val(),
                c_mop: row.find('input[name="c_mop"]').val(),
                c_bank: row.find('input[name="c_bank"]').val(),
                c_check_no: row.find('input[name="c_check_no"]').val(),
                c_remarks: row.find('input[name="c_remarks"]').val(),
                c_atap_no: row.find('input[name="c_atap_no"]').val(),
                date_transferred: row.find('input[name="date_transferred"]').val(),
                transferred_by: row.find('input[name="transferred_by"]').val(),
                notes: row.find('input[name="notes"]').val(),
                c_time_transferred: row.find('input[name="c_time_transferred"]').val(),
            };
            selectedRows.push(rowData);
        });

        console.log("Selected Rows: ", selectedRows);

        if (selectedRows.length === 0) {
            alert_toast("No rows selected", 'error');
            // saveButton.prop('disabled', false); 
            return;
        }

        var formData = new FormData();
        formData.append('selectedRows', JSON.stringify(selectedRows));

        $.ajax({
            url: '<?php echo base_url; ?>classes/Master.php?f=save_transfer',  
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            error: function(xhr, status, error) {
                console.log("AJAX Error: ", status, error);
                console.log(xhr.responseText);  
                alert_toast("An error occurred: " + error, 'error');
                // saveButton.prop('disabled', false); 
            },
            success: function(resp) {
                console.log(resp);
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    setTimeout(function() {
                        $('#createTransferModal').modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        location.reload();
                    }, 1000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else if (resp && resp.status === 'not_found') {
                    alert_toast("An error occurred.", 'error');
                } else {
                    alert_toast("An error occurred.", 'error');
                }
                end_loader();
            },
            complete: function() {
                $('#atap-form').data('formSubmitting', false);
            }
        });

    });
});
</script>
