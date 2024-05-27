<?php 
include('../config.php');
?>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    #data-table{
        text-align: center;
    }
    .cont_wrapper{
        margin:25px;
    }
</style>
</head>
<body class="cont_wrapper">
    <div class="card-header">
        <table>
            <tr>
                <td style="float:right;">
                    <button id="export-csv-btn" class="btn btn-flat btn-success btn-sm"><i class="fas fa-file-export"></i> Export</button>
                </td>
                <td style="float:right;">
                    <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary" style="font-size:14px;"><span class="fas fa-plus"></span>&nbsp;&nbsp;Create New</a>
                </td>
            </tr>
        </table>
    </div>
    <table class="table table-bordered table-striped" id="data-table">
        <thead>
            <tr>
                <th>#</th>
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
                        <a class="dropdown-item view_data" href="#"><span class="fa fa-eye text-primary"></span> View</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item delete-lot" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- MODAL :) -->
    <div class="modal fade" id="createNewModal" tabindex="-1" role="dialog" aria-labelledby="createNewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNewModalLabel">Create New CAR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="car-form" method="POST">
                        <div class="form-group">
                            <label for="account_no">Account No.</label>
                            <input type="text" class="form-control" id="c_account_no" name="c_account_no" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_type">Payment Type</label>
                            <?php 
                            $car_type_query = "SELECT DISTINCT c_payment_type,id FROM t_car_type ORDER BY id ASC";
                            $type_result = odbc_exec($conn, $car_type_query);
                            ?>
                            <select class="form-control" id="c_car_type" name="c_car_type" required>
                                <option value=""> </option> 
                                <?php
                                while ($row = odbc_fetch_array($type_result)): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($row['c_payment_type']); ?>">
                                        <?php echo htmlspecialchars($row['c_payment_type']); ?>
                                    </option>
                                <?php 
                                endwhile;
                                ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="car_no">CAR No.</label>
                            <input type="text" class="form-control" id="c_car_no" name="c_car_no" required>
                        </div>
                        <div class="form-group">
                            <label for="pay_date">Pay Date</label>
                            <input type="date" class="form-control" id="c_car_paydate" name="c_car_paydate" required>
                        </div>
                        <div class="form-group">
                            <label for="encoder">Encoder</label>
                            <input type="text" class="form-control" id="c_encoded_by" name="c_encoded_by" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(function(){
    $('#car-form').submit(function(e){
        e.preventDefault();
        var _this = $(this);

        start_loader();

        $.ajax({
            url: "../classes/Master.php?f=save_car_payment",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                console.log('Error - ' + errorMessage);
                alert_toast("An error occurred: " + errorMessage, 'error'); 
                end_loader();
            },
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success'); 
                    setTimeout(function() {
                        location.reload(); 
                    }, 2000); 
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error'); 
                }
                end_loader();
            }
        });
    });
});
</script>
<script>
    $('#create_new').click(function(){
        $('#createNewModal').modal('show');
    });
</script>
<script>
function start_loader(){
	$('body').append('<div id="preloader"><div class="loader-holder"><div></div><div></div><div></div><div></div>')
}
function end_loader(){
	 $('#preloader').fadeOut('fast', function() {
		$('#preloader').remove();
      })
}

window.alert_toast= function($msg = 'TEST',$bg = 'success' ,$pos=''){
	   	 var Toast = Swal.mixin({
	      toast: true,
	      position: $pos || 'top-end',
	      showConfirmButton: false,
	      timer: 5000
	    });
	      Toast.fire({
	        icon: $bg,
	        title: $msg
	      })
	  }

</script>
