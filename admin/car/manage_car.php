<?php 
    include('../../config.php');

    $c_account_no = null;
    $c_car_type = '';
    $c_car_amount = '';
    $c_car_no = '';
    $c_car_paydate = date('Y-m-d');
    $c_encoded_by = '';

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $get_car_query = "SELECT * FROM t_car_payment WHERE id = ?";
        $accountId = $_GET['id'];
        $stmt = odbc_prepare($conn, $get_car_query);
        odbc_execute($stmt, array($accountId));

        if ($result = odbc_fetch_array($stmt)) {
            $c_account_no = $result["c_account_no"];
            $c_car_type = $result["c_car_type"];
            $c_car_amount = $result["c_car_amount"];
            $c_car_no = $result["c_car_no"];
            $c_car_paydate = $result["c_car_paydate"];
            $c_encoded_by = $result["c_encoded_by"];
        }
    } else if (isset($_GET['c_account_no']) && $_GET['c_account_no'] > 0) {
        $c_account_no = $_GET['c_account_no'];
    }
?>
<form id="car-form">
    <input type="hidden" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="form-group">
        <label for="account_no">Account No.</label>
        <input type="text" class="form-control" id="c_account_no" name="c_account_no" value="<?php echo htmlspecialchars($c_account_no) ?>" required>
    </div>
    <div class="form-group">
        <label for="payment_type">Payment Type</label>
        <div class="input-group">
            <select class="form-control" id="c_car_type" name="c_car_type" style="width: calc(100% - 40px);" required>
                <option value=""></option>
                <?php
                $car_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type ORDER BY id ASC";
                $type_result = odbc_exec($conn, $car_type_query);
                while ($row = odbc_fetch_array($type_result)) {
                    $selected = (isset($c_car_type) && $c_car_type == $row['c_payment_type']) ? 'selected' : '';
                    echo "<option value='".($row['c_payment_type'])."' $selected>".($row['c_payment_type'])."</option>";
                }
                ?>
            </select>
            <!-- <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="addPaymentTypeBtn"><i class="fas fa-plus"></i></button>
            </div> -->
        </div>
        <!-- <input type="text" class="form-control" id="new_payment_type" name="new_payment_type" style="display: none;"> -->
    </div>

    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" class="form-control" id="c_car_amount" name="c_car_amount" value="<?php echo htmlspecialchars($c_car_amount) ?>" required>
    </div>
    <div class="form-group">
        <label for="car_no">CAR No.</label>
        <input type="text" class="form-control" id="c_car_no" name="c_car_no" value="<?php echo htmlspecialchars($c_car_no); ?>" maxlength="6" pattern="\d{1,6}" required>
    </div>

    <div class="form-group">
        <label for="pay_date">Pay Date</label>
        <input type="date" class="form-control" id="c_car_paydate" name="c_car_paydate" value="<?php echo htmlspecialchars($c_car_paydate) ?>" required>
    </div>
    <div class="form-group">
        <label for="encoder">Encoder</label>
        <input type="text" class="form-control" id="c_encoded_by" name="c_encoded_by" value="<?php echo htmlspecialchars($c_encoded_by) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>


<script>
  document.getElementById("addPaymentTypeBtn").addEventListener("click", function() {
    var selectBox = document.getElementById("c_car_type");
    var textBox = document.getElementById("new_payment_type");
    var button = document.getElementById("addPaymentTypeBtn");

    if (textBox.style.display === "none" || textBox.style.display === "") {

        selectBox.style.display = "none";
        textBox.style.display = "block";
        textBox.focus();

        button.innerHTML = "Cancel";
        button.classList.remove("btn-outline-secondary");
        button.classList.add("btn-danger");

        selectBox.removeAttribute("required");
        textBox.setAttribute("required", "required");
    } else {

        selectBox.style.display = "block";
        textBox.style.display = "none";

        button.innerHTML = '<i class="fas fa-plus"></i>';
        button.classList.remove("btn-danger");
        button.classList.add("btn-outline-secondary");

        textBox.removeAttribute("required");
        selectBox.setAttribute("required", "required");

        textBox.value = "";
    }
});


</script>

<script>
   $(document).ready(function() {
    $('#car-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);

        var c_car_type = $('#c_car_type').is(':visible') ? $('#c_car_type').val() : $('#new_payment_type').val();

        start_loader();

        $.ajax({
            url: "../../classes/Master.php?f=save_car_payment",
            data: new FormData(_this[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(err) {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                console.log(resp); // Log the response to see its content
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    setTimeout(function() {
                        //location.reload();
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
    function checkAddNew() {
        var selectBox = document.getElementById("c_car_type");
        var textBox = document.getElementById("new_payment_type");
        if (selectBox.value == "add_new") {
            selectBox.style.display = "none";
            textBox.style.display = "block";
        } else {
            selectBox.style.display = "block";
            textBox.style.display = "none";
        }
    }
    </script>