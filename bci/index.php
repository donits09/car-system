<?php 
session_start();

require_once('../inc/check_session.php');
check_user_group(1);

include('../config.php');
include('../inc/navbar.php');    
include('../inc/header.php');     
?>

<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css">
<body>
<div class="container mt-5">
    <div class="card mt-3">
        <div class="pd-20">
            <h2 class="text-blue h4">Buyer's Contact Info</h2>
            <hr>
        </div>
        <div class="m-3">
            <div class="row mt-2">
                <div class="col-md-4">
                    <label for="search-account" class="form-label">Search Account No.: </label>
                    <div class="row g-2">
                        <div class="col">
                            <input type="number" class="form-control txt" id="search-account" placeholder="Enter Account No.">
                        </div>
                        <div class="col-auto">
                            <button id="search-btn" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-center fw-bold mt-3" style="font-size: 20px;">Client's Info</p>
            <hr>
            <form id="bci-form" method="post" action="">
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control txt" id="c_lname" name="c_lname" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_fname" class="form-label">First Name</label>
                        <input type="text" class="form-control txt" id="c_fname" name="c_fname" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_mname" class="form-label">Middle Name</label>
                        <input type="text" class="form-control txt" id="c_mname" name="c_mname" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_lno" class="form-label">Landline No</label>
                        <input type="text" class="form-control txt" id="c_lno" name="c_lno" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_mno" class="form-label">Mobile No</label>
                        <input type="text" class="form-control txt" id="c_mno" name="c_mno" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_email" class="form-label">Email Address</label>
                        <input type="text" class="form-control txt" id="c_email" name="c_email" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_address" class="form-label">Address</label>
                        <input type="text" class="form-control txt" id="c_address" name="c_address" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_prov" class="form-label">City/Provice</label>
                        <input type="text" class="form-control txt" id="c_prov" name="c_prov" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_zipcode" class="form-label">Zip Code</label>
                        <input type="text" class="form-control txt" id="c_zipcode" name="c_zipcode" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_tin" class="form-label">TIN #</label>
                        <input type="text" class="form-control txt" id="c_tin" name="c_tin" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <p class="text-center fw-bold mt-3" style="font-size: 20px;">Representative Info</p>
                <hr>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_rep_name" class="form-label">Representative Name</label>
                        <input type="text" class="form-control txt" id="c_rep_name" name="c_rep_name" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_rep_landline" class="form-label">Landline No</label>
                        <input type="text" class="form-control txt" id="c_rep_landline" name="c_rep_landline" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_rep_mobile" class="form-label">Mobile No</label>
                        <input type="text" class="form-control txt" id="c_rep_mobile" name="c_rep_mobile" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_rep_email" class="form-label">Email Address</label>
                        <input type="text" class="form-control txt" id="c_rep_email" name="c_rep_email" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_last_updated_select" class="form-label">Select Last Updated</label>
                        <select id="c_last_updated_select" class="form-control">
                            <option value="">Select Date</option>
                        </select>
                    </div>
                    <div class="col-md-4 mt-4">
                        <input type="hidden" class="form-control txt" id="c_accno" name="c_accno" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" id="btnsave" style="float:right;">Save</button>
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var allRecords = [];

        $("#search-btn").click(function () {
            var accountNo = $("#search-account").val();

            if (accountNo === "") {
                alert("Please enter an account number.");
                return;
            }

            $.ajax({
                type: "POST",
                url: '<?php echo base_url ?>bci/get_buyers.php',
                data: { account_no: accountNo },
                dataType: "json",
                success: function (response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        allRecords = response.records;
                        populateDropdown(allRecords);
                        displayData(allRecords[0]);
                    }
                },
                error: function () {
                    alert("An error occurred while fetching data.");
                }
            });
        });

        function populateDropdown(records) {
            var dropdown = $("#c_last_updated_select");
            dropdown.empty();

            if (records.length === 0) {
                dropdown.append('<option value="">Select Date</option>');
            }

            records.forEach(function (record, index) {
                var formattedDate = record.c_last_updated.split(" ")[0];
                dropdown.append('<option value="' + index + '">' + formattedDate + '</option>');
            });

            dropdown.off("change").on("change", function () {
                var selectedIndex = $(this).val();
                if (selectedIndex !== "") {
                    displayData(records[selectedIndex]);
                }
            });
        }


        function displayData(data) {
            $("#c_lname").val(data.c_lname);
            $("#c_fname").val(data.c_fname);
            $("#c_mname").val(data.c_mname);
            $("#c_lno").val(data.c_lno);
            $("#c_mno").val(data.c_mno);
            $("#c_email").val(data.c_email);
            $("#c_address").val(data.c_address);
            $("#c_prov").val(data.c_prov);
            $("#c_zipcode").val(data.c_zipcode);
            $("#c_rep_name").val(data.c_rep_name);
            $("#c_rep_landline").val(data.c_rep_landline);
            $("#c_rep_mobile").val(data.c_rep_mobile);
            $("#c_rep_email").val(data.c_rep_email);
            $("#c_last_updated").val(data.c_last_updated);
            $("#c_accno").val(data.c_accno);
            $("#c_tin").val(data.c_tin);
        }
    });

    $(document).ready(function() {
            $('#bci-form').on('submit', function(e) {
                e.preventDefault(); 
                var formData = $(this).serialize(); 
                $.ajax({
                url: '<?php echo base_url; ?>classes/Master.php?f=save_bci',
                data: new FormData($(this)[0]),
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
                            updateBuyerInfo();
                        }, 300);
                    } else if (resp && resp.status === 'failed' && resp.err) {
                        alert_toast("An error occurred: " + resp.err, 'error');
                    } else if (resp && resp.status === 'not_found') {
                        alert_toast("An error occurred.", 'error');
                    } else {
                        alert_toast("An error occurred.", 'error');
                    }
                    end_loader();
                },
            });
        });
    });

    function updateBuyerInfo() {
        var accountNo = $("#search-account").val();

        if (!accountNo) {
            console.warn("No account number entered.");
            return;
        }

        fetch(`<?php echo base_url ?>bci/get_buyers.php?account_no=${accountNo}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert_toast(data.error, 'error');
                } else {
                    allRecords = data.records;
                    populateDropdown(allRecords);
                    displayData(allRecords[0]);
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
            });
    }
</script>
</body>
<?php include('../inc/footer.php'); ?>
