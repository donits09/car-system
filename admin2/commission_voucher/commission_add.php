<?php
require_once('../../config.php');

if (isset($_GET['id']) && isset($_GET['acc'])) {
    $sql = "SELECT * FROM t_commission WHERE c_code = ? and c_account_no = ?";
    $acc = $_GET['acc'];
    $id  = $_GET['id'];

    $qry = odbc_prepare($conn, $sql);
    if (!$qry) {
        die("Preparation of the statement failed: " . odbc_errormsg($conn));
    }

    if (!odbc_execute($qry, array($id, $acc))) { // Pass both parameters in an array
        die("Execution of the statement failed: " . odbc_errormsg($conn));
    }

    // Process the query result here


   

    while ($res = odbc_fetch_array($qry)) {
        $c_code = $res['c_code'];
        $l_position = $res['c_position'];
        if($l_position == 1) {
            $l_pos = 'AVP';
        }elseif($l_position == 2){
            $l_pos = 'JAV';
        }
        elseif($l_position == 3){
            $l_pos = 'AM';
        }
        elseif($l_position == 4){
            $l_pos = 'FM';
        }
        elseif($l_position == 5){
            $l_pos = 'SM';
        }
        elseif($l_position == 6){
            $l_pos = 'MA';
        }
        elseif($l_position == 7){
            $l_pos = 'EMP';
        }
        elseif($l_position == 8){
            $l_pos = 'SPC';
        }
        elseif($l_position == 9){
            $l_pos = 'VPS';
        }
        elseif($l_position == 10){
            $l_pos = 'DS';
        }
        elseif($l_position == 11){
            $l_pos = 'SMG';
        }
        elseif($l_position == 12){
            $l_pos = 'PC';
        }
        elseif($l_position == 13){
            $l_pos = 'PD';
        }
        else{
            $l_pos = 'N/A';
        }
        $c_date_of_sale = $res['c_date_of_sale'];
        $c_amount = $res['c_amount'];
        $c_account_no = $res['c_account_no'];
        $c_sale = $res['c_sale'];
        $c_rate = $res['c_rate'];
        $c_net_tcp = $res['c_net_tcp'];
        $c_network = $res['c_network'];
        $c_division = $res['c_division'];
        $c_account_mode = $res['c_account_mode'];
        $c_last_name = $res['c_last_name'];
        $c_first_name = $res['c_first_name'];
        $c_middle_initial = $res['c_middle_initial'];
      
    }
}
?>
<style>
    /* Custom CSS for modal form */

/* Reduce spacing between form sections */
.form-section {
    margin-bottom: 1rem; /* Adjust margin bottom as needed */
}

/* Reduce spacing between form rows */
.form-section .row {
    margin-bottom: 0.5rem; /* Adjust margin bottom as needed */
}

/* Reduce padding inside form fields */
.form-control {
    padding: 0.375rem 0.75rem; /* Adjust padding as needed */
    font-size: 0.775rem; /* Adjust font size to make text smaller */
}

/* Adjust label font size and weight */
.form-section label {
    font-size: 0.775rem; /* Adjust label font size */
    font-weight: normal; /* Adjust label font weight if needed */
}

/* Adjust column width for smaller screens */
@media (max-width: 768px) {
    .col-md-6 {
        width: 100%; /* Make columns full-width on smaller screens */
        margin-bottom: 0.5rem; /* Adjust margin bottom for responsiveness */
    }
}
</style>
<!-- 
<h5>Agent Information Form</h5> -->

<form action="" id="agent-commission" class="container">
    <div class="form-section mb-4">
        <div class="row">  
            <div class="container">
            <label for="c_agent_name">Search Agent:</label>
            <div class="dropdown">
                <input type="text" class="form-control" id="c_agent_name" name="c_agent_name" placeholder="Type or select an option" autocomplete="off" value="<?php echo isset($c_agent_name) ? htmlspecialchars($c_agent_name, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                <div class="dropdown-menu w-100" id="comboBoxMenu">
                    <?php
                $car_type_query = "SELECT DISTINCT c_code FROM t_agents WHERE status = 'Active' ORDER BY c_code ASC";
                $type_result = odbc_exec($conn, $car_type_query);
                while ($row = odbc_fetch_array($type_result)) {
                    $selected = (isset($c_agent_name) && $c_agent_name == $row['c_code']) ? 'active' : '';
                    echo "<a class='dropdown-item $selected' href='#' data-value='".htmlspecialchars($row['c_code'], ENT_QUOTES, 'UTF-8')."'>".htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8')."</a>";
                }
                ?>
                  
                </div>
            </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="c_code">Code:</label>
                        <input type="text" class="form-control" id="c_code" name="c_code" value="<?php echo isset($c_code) ? $c_code : ''; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="c_first_name">First Name:</label>
                        <input type="text" class="form-control" id="c_first_name" name="c_first_name" value="<?php echo isset($c_first_name) ? $c_first_name : ''; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_last_name">Last Name:</label>
                        <input type="text" class="form-control" id="c_last_name" name="c_last_name" value="<?php echo isset($c_last_name) ? $c_last_name : ''; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_middle_initial">Middle Initial:</label>
                        <input type="text" class="form-control" id="c_middle_initial" name="c_middle_initial" value="<?php echo isset($c_middle_initial) ? $c_middle_initial : ''; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="c_position">Position:</label>
                        <input type="text" class="form-control" id="c_position" name="c_position" value="<?php echo isset($l_pos) ? $l_pos : ''; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_network">Network:</label>
                        <input type="text" class="form-control" id="c_network" name="c_network" value="<?php echo isset($c_network) ? $c_network : ''; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_division">Division:</label>
                        <input type="text" class="form-control" id="c_division" name="c_division" value="<?php echo isset($c_division) ? $c_division : ''; ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="net_tcp_amount">Net TCP Amount:</label>
                        <input type="text" class="form-control" id="net_tcp_amount" name="net_tcp_amount" value="<?php echo isset($net_tcp_amount) ? $net_tcp_amount : ''; ?>" >
                    </div>
                    <div class="col-md-4">
                        <label for="rate">Rate:</label>
                        <input type="text" class="form-control" id="rate" name="rate" value="<?php echo isset($rate) ? $rate : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="net_tcp_amount">Commission Amount:</label>
                        <input type="text" class="form-control" id="net_tcp_amount" name="net_tcp_amount" value="<?php echo isset($net_tcp_amount) ? $net_tcp_amount : ''; ?>" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
// Wait for the DOM to be ready
document.addEventListener("DOMContentLoaded", function() {
    // Get the dropdown items
    var dropdownItems = document.querySelectorAll("#comboBoxMenu .dropdown-item");

    // Add click event listeners to each dropdown item
    dropdownItems.forEach(function(item) {
        item.addEventListener("click", function(event) {
            event.preventDefault();
            var selectedValue = this.getAttribute("data-value");
            document.getElementById("c_agent_name").value = selectedValue;
        });
    });
});
</script>
<script>
    $(function(){
        $('#agent-commission').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $('.pop-msg').remove();
            var el = $('<div>');
                el.addClass("pop-msg alert");
                el.hide();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Commission_Master.php?f=save_agent_commission",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err);
                    alert("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp){
                    if (resp.status == 'success'){
                        alert_toast(resp.msg, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else if (!!resp.msg){
                        el.addClass("alert-danger");
                        el.text(resp.msg);
                        _this.prepend(el);
                    } else {
                        el.addClass("alert-danger");
                        el.text("An error occurred due to unknown reason.");
                        _this.prepend(el);
                    }
                    el.show('slow');
                    $('html, body, .modal').animate({scrollTop:0}, 'fast');
                    end_loader();
                }
            });
        });
    });

$(document).ready(function () {
    $('#c_agent_name').on('input', function () {
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
</script>
