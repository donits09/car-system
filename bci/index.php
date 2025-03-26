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
<style>
    .selectable-row {
        cursor: pointer;
    }

    .selectable-row:hover {
        background-color: #f1f1f1;
    }

    .table-primary {
        background-color:rgb(160, 160, 160) !important;
        color: white;
    }
</style>
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
                        <label for="c_last_updated" class="form-label">Last Updated</label>
                        <input type="text" class="form-control txt" id="c_last_updated" name="c_last_updated" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4 mt-4">
                        <input type="hidden" class="form-control txt" id="c_accno" name="c_accno" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <p class="text-center fw-bold mt-3" style="font-size: 19px;">Select Last Updated</p>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <table id="c_last_updated_table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Address</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
                        populateTable(allRecords);
                        displayData(allRecords[0]);
                    }
                },
                error: function () {
                    alert("An error occurred while fetching data.");
                }
            });
        });

        function populateTable(records) {
            var tableBody = $("#c_last_updated_table tbody");
            tableBody.empty();

            if (records.length === 0) {
                tableBody.append('<tr><td colspan="3" class="text-center">No records found</td></tr>');
                return;
            }

            var latestRow = null; // Store the latest row

            records.forEach(function (record, index) {
                var formattedDate = record.c_last_updated ? record.c_last_updated.split(" ")[0] : "N/A";
                var address = record.c_address || "N/A";
                var mobileNo = record.c_mno || "N/A";
                var email = record.c_email || "N/A";

                var row = $('<tr class="selectable-row"></tr>')
                    .append('<td>' + formattedDate + '</td>')
                    .append('<td>' + address + '</td>')
                    .append('<td>' + mobileNo + '</td>')
                    .append('<td>' + email + '</td>');

                // Click event to select row
                row.on("click", function () {
                    $(".selectable-row").removeClass("table-secondary");
                    $(this).addClass("table-secondary");

                    // Update selected fields
                    $("#c_last_updated").val(formattedDate);
                    $("#c_mno").val(mobileNo);
                    $("#c_email").val(email);

                    // Display the full data in the form
                    displayData(records[index]);
                });

                tableBody.append(row);

                // Mark the first row as the latest and store it
                if (index === 0) {
                    latestRow = row;
                }
            });

            // Auto-select the latest row
            if (latestRow) {
                latestRow.addClass("table-secondary"); // Highlight latest row
                latestRow.trigger("click"); // Simulate a click to set values
            }
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
