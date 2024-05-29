<?php 
    include('../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
       
        $get_car_query = "SELECT * FROM t_car_payment WHERE id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_car_query);
        odbc_execute($stmt, array($accountId));

        while ($result = odbc_fetch_array($stmt)){
            $c_account_no = $result["c_account_no"];
            $c_car_type = $result["c_car_type"];
            $c_car_amount = $result["c_car_amount"];
            $c_car_no= $result["c_car_no"];
            $c_car_paydate = $result["c_car_paydate"];
            $c_encoded_by= $result["c_encoded_by"];
        }
    }else{
        if (isset($_GET['c_account_no']) && $_GET['c_account_no'] > 0) {
            $c_account_no = $_GET['c_account_no'];
        } else {
            $c_account_no = null;
        }
    }
?>
<form id="car-form">
<input type="hidden" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="form-group">
        <label for="account_no">Account No.</label>
        <input type="text" class="form-control" id="c_account_no" name="c_account_no" value="<?php echo isset($c_account_no) ? $c_account_no :"" ?>" required>
    </div>
    <div class="form-group">
        <label for="payment_type">Payment Type</label>
        <select class="form-control" id="c_car_type" name="c_car_type" value="<?php echo isset($c_car_type) ? $c_car_type : "" ?>" required>
    <option value=""></option>
    <?php
    $car_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type ORDER BY id ASC";
    $type_result = odbc_exec($conn, $car_type_query);
    while ($row = odbc_fetch_array($type_result)):
        $selected = (isset($c_car_type) && $c_car_type == $row['c_payment_type']) ? 'selected' : '';
    ?>
        <option value="<?php echo htmlspecialchars($row['c_payment_type']); ?>" <?php echo $selected; ?>>
            <?php echo htmlspecialchars($row['c_payment_type']); ?>
        </option>
    <?php
    endwhile;
    ?>
</select>

    </div>
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo isset($c_car_amount) ? $c_car_amount :"" ?>" required>
    </div>
    <div class="form-group">
        <label for="car_no">CAR No.</label>
        <input type="text" class="form-control" id="c_car_no" name="c_car_no" value="<?php echo isset($c_car_no) ? $c_car_no :"" ?>" required>
    </div>
    <div class="form-group">
        <label for="pay_date">Pay Date</label>
        <input type="date" class="form-control" id="c_car_paydate" name="c_car_paydate" value="<?php echo isset($c_car_paydate) ? $c_car_paydate :"" ?>" required>
    </div>
    <div class="form-group">
        <label for="encoder">Encoder</label>
        <input type="text" class="form-control" id="c_encoded_by" name="c_encoded_by" value="<?php echo isset($c_encoded_by) ? $c_encoded_by :"" ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<script>
    $(function(){
        $('#car-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);

            start_loader();

            $.ajax({
                url: "../../classes/Master.php?f=save_car_payment",
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
