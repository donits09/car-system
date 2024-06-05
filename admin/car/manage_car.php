<style>
    .dropdown-item.active {
        background-color: #007bff;
        color: #ffffff;
    }
</style>

<?php 
    session_start();
    include('../../config.php');

    $c_account_no = null;
    $c_car_type = '';
    $c_car_amount = '';
    $c_car_no = '';
    $c_car_paydate = date('Y-m-d');
    $c_encoded_by = '';
    $c_tran_date = date('Y-m-d H:i:s');

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
    <input type="text" name="id" value="<?php echo isset($accountId) ? $accountId : '' ?>">
    <div class="form-group">
        <label for="account_no">Account No.</label>
        <input type="text" class="form-control" id="c_account_no" name="c_account_no" value="<?php echo htmlspecialchars($c_account_no) ?>" required>
    </div>
    <div class="form-group">
    <label for="c_car_type">Payment Type</label>
    <div class="dropdown">
        <input type="text" class="form-control" id="c_car_type" name="c_car_type" style="width: calc(100% - 40px);" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>">
        <div class="dropdown-menu w-100" id="comboBoxMenu">
            <?php
            $car_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type WHERE status = 0 ORDER BY id ASC";
            $type_result = odbc_exec($conn, $car_type_query);
            while ($row = odbc_fetch_array($type_result)) {
                $selected = (isset($c_car_type) && $c_car_type == $row['c_payment_type']) ? 'active' : '';
                echo "<a class='dropdown-item $selected' href='#' data-value='".htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8')."'>".htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8')."</a>";
            }
            ?>
        </div>
        <script>
        var idValue = "<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>";
        if (idValue) {
            $('#comboBoxMenu').find('a[data-value="' + idValue + '"]').addClass('active');
        }
        </script>
    </div>
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
        <label for="encoder">Encoded by</label>
        <input type="text" class="form-control" id="c_encoded_by" name="c_encoded_by" value="<?php echo  $_SESSION['username'] ?>" readonly>
    </div>
    <div class="form-group">
        <label for="encoder">Transaction date</label>
        <input type="text" class="form-control" id="c_tran_date" name="c_tran_date" value="<?php echo  htmlspecialchars($c_tran_date) ?>" readonly>
    </div>
   
    <button type="submit" class="btn btn-primary">Save</button>
</form>
<script>
    $(document).ready(function () {
        $('#c_car_type').on('input', function () {
            var input = $(this).val().toLowerCase();
            var hasVisibleOptions = false;
            $('#comboBoxMenu .dropdown-item').each(function () {
                if ($(this).text().toLowerCase().startsWith(input)) {
                    $(this).show();
                    hasVisibleOptions = true;
                } else {
                    $(this).hide();
                }
            });

            if (hasVisibleOptions) {
                $('#comboBoxMenu').show();
            } else {
                $('#comboBoxMenu').hide();
            }
        });

        $('#comboBoxMenu').on('click', '.dropdown-item', function () {
            var selectedText = $(this).data('value');
            $('#c_car_type').val(selectedText);
            $('#comboBoxMenu').hide();
        });

        $('#c_car_type').on('click', function () {
            $('#comboBoxMenu').show();
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('#comboBoxMenu').hide();
            }
        });
    });

    function submitForm() {
        var selectedOption = document.getElementById('c_car_type').value;
        alert('You selected: ' + selectedOption);
    }
</script>
<script>
$(document).ready(function() {
    $('#car-form').submit(function(e) {
        e.preventDefault();
       
        if (confirm("Are you sure you want to save this car payment?")) {
            var _this = $(this);

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
                console.log(resp); 
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
        }
    });
});
</script>