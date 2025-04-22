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
            <h2 class="text-blue h4">Client's Contact Info</h2>
            <hr>
        </div>
        <div class="m-3">
            <label for="search-account" class="form-label">Search Account No</label>
            <div class="row mt-2">
                <div class="col-sm-3">
                    <input type="text" class="form-control txt" id="search-account" placeholder="Account No">
                </div>
                <div class="col-auto">
                    <button id="search-btn" class="btn btn-primary">Search</button>
                </div>
            </div>
            <label for="search-account" class="form-label">Search Location</label>
            <div class="row mt-2">
                <div class="col-sm-2">
                    <select class="form-control" id="search-phase" name="search-phase">
                        <option value="">Select Phase</option>
                        <?php
                        $project_query = "SELECT DISTINCT c_acronym, c_code FROM t_projects ORDER BY c_acronym ASC";
                        $result = odbc_exec($conn, $project_query);

                        while ($row = odbc_fetch_array($result)) {
                            $selected = (isset($selected_code) && $selected_code == $row['c_code']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row['c_code'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($row['c_acronym'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control txt" id="search-block" placeholder="Block" maxlength="3">
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control txt" id="search-lot" placeholder="Lot" maxlength="2">
                </div>
                <div class="col-auto">
                    <button id="search-loc" class="btn btn-primary">Search</button>
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
                        <label for="c_accno" class="form-label">Account No.</label>
                        <input type="text" class="form-control txt" id="c_accno" name="c_accno" value="<?php echo htmlspecialchars("") ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_type" class="form-label">Type</label>
                        <input type="text" class="form-control txt" id="c_type" name="c_type"  value="<?php echo htmlspecialchars("") ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_last_updated" class="form-label">Last Updated</label>
                        <input type="text" class="form-control txt" id="c_last_updated" name="c_last_updated"  value="<?php echo htmlspecialchars("") ?>" readonly>
                    </div>
                </div>
                <hr>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_lno" class="form-label">Landline No</label>
                        <input type="text" class="form-control txt" id="c_lno" name="c_lno" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="c_mno" class="form-label">Mobile No</label>
                        <input type="text" class="form-control txt" id="c_mno" name="c_mno">
                        <small id="mno_error" class="text-danger"></small>
                    </div>
                    <div class="col-md-4">
                        <label for="c_email" class="form-label">Email Address</label>
                        <input type="text" class="form-control txt" id="c_email" name="c_email">
                        <small id="email_error" class="text-danger"></small>
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
                        <input type="number" class="form-control txt" id="c_rep_mobile" name="c_rep_mobile" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="c_rep_email" class="form-label">Email Address</label>
                        <input type="text" class="form-control txt" id="c_rep_email" name="c_rep_email" value="<?php echo htmlspecialchars("") ?>">
                    </div>
                </div>
                <p class="text-center fw-bold mt-3" style="font-size: 19px;">Select Last Updated</p>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <table id="c_last_updated_table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Account No.</th>
                                    <th>Type</th>
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

        // para sa old versions ng browsers ayaw kase gumana nung search-loc hayup yan
        if (!String.prototype.padStart) {
            String.prototype.padStart = function(targetLength, padString) {
                targetLength = targetLength >> 0;
                padString = String(padString || ' ');
                if (this.length >= targetLength) {
                    return String(this);
                } else {
                    targetLength = targetLength - this.length;
                    if (targetLength > padString.length) {
                        padString += padString.repeat(targetLength / padString.length); 
                    }
                    return padString.slice(0, targetLength) + String(this);
                }
            };
        }

        $("#search-btn, #search-loc").click(function () {
            var accountNo = $("#search-account").val().trim();
            var phase = $("#search-phase").val().trim();
            var block = $("#search-block").val().trim();
            var lot = $("#search-lot").val().trim();

            if (accountNo !== "") {
                $("#search-phase").val('');
                $("#search-block").val('');
                $("#search-lot").val('');
            } else if (phase !== "" && block !== "" && lot !== "") {
                $("#search-account").val('');
            } else {
                alert("Mag Enter ka muna ng account o location");
                return;
            }

            if (block !== "") {
                block = ('000' + block).slice(-3);
            }

            if (lot !== "") {
                lot = ('00' + lot).slice(-2);
            }

            var location = phase + block + lot;
            console.log("Laman ni Location:", location);
            console.log("Laman ni Account:", accountNo);

            /* get_buyers_account.php */
            $.ajax({
                type: "POST",
                url: '<?php echo base_url ?>bci/get_buyers_account.php',
                data: { account_no: accountNo,
                        location: location },
                dataType: "json",
                success: function (response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        displayData(response.records[0]);
                    }
                },
                error: function () {
                    alert("An error occurred while fetching account data.");
                }
            });

            /* get_buyers.php */
            $.ajax({
                type: "POST",
                url: '<?php echo base_url ?>bci/get_buyers.php',
                data: { account_no: accountNo,
                        location: location },
                dataType: "json",
                success: function (response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        allRecords = response.records;
                        populateTable(allRecords);
                    }
                },
                error: function () {
                    alert("An error occurred while fetching buyer data.");
                }
            });
        });

        function populateTable(records) {
            var table = $("#c_last_updated_table").DataTable();
            table.clear().destroy();

            var tableBody = $("#c_last_updated_table tbody");
            tableBody.empty();

            if (records.length === 0) {
                tableBody.append('<tr><td colspan="6" class="text-center">No records found</td></tr>');
            } else {
                records.forEach(function (record, index) {
                    var formattedDate = record.c_last_updated ? record.c_last_updated.split(" ")[0] : "N/A";
                    var accno = record.c_accno || "-";
                    var type = record.c_type || "-";
                    var address = record.c_address || "-";
                    var mobileNo = record.c_mno || "-";
                    var email = record.c_email || "-";

                    var row = $('<tr class="selectable-row"></tr>')
                        .append('<td>' + formattedDate + '</td>')
                        .append('<td>' + accno + '</td>')
                        .append('<td>' + type + '</td>')
                        .append('<td>' + address + '</td>')
                        .append('<td>' + mobileNo + '</td>')
                        .append('<td>' + email + '</td>');

                    row.on("click", function () {
                        $(".selectable-row").removeClass("table-secondary");
                        $(this).addClass("table-secondary");

                        $("#c_last_updated").val(formattedDate);
                        $("#c_mno").val(mobileNo);
                        $("#c_email").val(email);

                        displayData(records[index]);
                    });

                    tableBody.append(row);
                });
            }

            $('#c_last_updated_table').DataTable({
                "pageLength": 5,
                "lengthChange": false,
                "order": [[0, "desc"]]
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
            $("#c_type").val(data.c_type);
        }
    });


    $(document).ready(function() {
        function isValidMobile(mobile) {
            const mobilePattern = /^09[0-9]{9}$/;
            return mobilePattern.test(mobile);
        }

        function isValidEmail(email) {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return emailPattern.test(email);
        }

        $('#bci-form').on('submit', function(e) {
            e.preventDefault();

            const mobileVal = $('#c_mno').val().trim();
            const emailVal = $('#c_email').val().trim();
            let valid = true;

            if (mobileVal !== "" && !isValidMobile(mobileVal)) {
                $('#mno_error').text("Mali naman mobile jusku");
                $('#c_mno').addClass("is-invalid");
                valid = false;
            } else {
                $('#mno_error').text("");
                $('#c_mno').removeClass("is-invalid");
            }

            if (emailVal !== "" && !isValidEmail(emailVal)) {
                $('#email_error').text("Mali rin email ayusin");
                $('#c_email').addClass("is-invalid");
                valid = false;
            } else {
                $('#email_error').text("");
                $('#c_email').removeClass("is-invalid");
            }

            if (!valid) {
                alert("Invalid mobile number or email address");
                return;
            }

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
                    alert_toast("Need to put account first", 'error');
                    end_loader();
                },
                success: function(resp) {                 
                    console.log(resp);
                    if (resp && resp.status === 'success') {
                        alert_toast(resp.msg, 'success');
                        setTimeout(function () {
                            $("#search-loc").click();
                            $("#search-btn").click();
                        }, 1000);
                    } else if (resp && resp.status === 'failed' && resp.err) {
                        alert_toast("An error occurred: " + resp.err, 'error');
                    } else if (resp && resp.status === 'not_found') {
                        alert_toast("An error occurred.", 'error');
                    } else {
                        alert_toast("An error occurred.", 'error');
                    }
                    end_loader();
                }
            });
        });
    });

    /* function updateBuyerInfo() {
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
 */
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchAccount = document.getElementById("search-account");
        const searchPhase = document.getElementById("search-phase");
        const searchBlock = document.getElementById("search-block");
        const searchLot = document.getElementById("search-lot");

        function toggleSearchFields() {
            if (searchAccount.value.trim() !== "") {
                searchPhase.disabled = true;
                searchBlock.disabled = true;
                searchLot.disabled = true;
            } else if (searchPhase.value !== "" || searchBlock.value.trim() !== "" || searchLot.value.trim() !== "") {
                searchAccount.disabled = true;
            } else {
                searchAccount.disabled = false;
                searchPhase.disabled = false;
                searchBlock.disabled = false;
                searchLot.disabled = false;
            }
        }

        searchAccount.addEventListener("input", toggleSearchFields);
        searchPhase.addEventListener("change", toggleSearchFields);
        searchBlock.addEventListener("input", toggleSearchFields);
        searchLot.addEventListener("input", toggleSearchFields);
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const mobileInput = document.getElementById("c_mno");
    const emailInput = document.getElementById("c_email");
    const mnoError = document.getElementById("mno_error");
    const emailError = document.getElementById("email_error");

    function validateMobile() {
        const value = mobileInput.value.trim();
        const mobilePattern = /^09[0-9]{9}$/;

        if (value === "") {
            mnoError.textContent = "";
            mobileInput.classList.remove("is-invalid");
        } else if (!mobilePattern.test(value)) {
            mnoError.textContent = "Mobile number must start with '09' and be 11 digits long";
            mobileInput.classList.add("is-invalid");
        } else {
            mnoError.textContent = "";
            mobileInput.classList.remove("is-invalid");
        }
    }

    function validateEmail() {
        const value = emailInput.value.trim();
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (value === "") {
            emailError.textContent = "";
            emailInput.classList.remove("is-invalid");
        } else if (!emailPattern.test(value)) {
            emailError.textContent = "Enter a valid email address with '@'";
            emailInput.classList.add("is-invalid");
        } else {
            emailError.textContent = "";
            emailInput.classList.remove("is-invalid");
        }
    }

    mobileInput.addEventListener("input", validateMobile);
    emailInput.addEventListener("input", validateEmail);
});
</script>
<script>
    document.getElementById("search-account").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });
    document.getElementById("search-block").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 3);
    });
    document.getElementById("search-lot").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 2);
    });
    document.getElementById("c_mno").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });
    document.getElementById("c_rep_mobile").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });
</script>
</body>
<?php include('../inc/footer.php'); ?>
