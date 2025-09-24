<?php 
session_start();


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
            <h2 class="text-blue h4">Account Logs List</h2>
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
            <p class="text-center fw-bold mt-3" style="font-size: 20px;">Account's Info</p>
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
                        <label for="c_address" class="form-label">Address</label>
                        <input type="text" class="form-control txt" id="c_address" name="c_address" value="<?php echo htmlspecialchars("") ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="c_prov" class="form-label">City/Provice</label>
                        <input type="text" class="form-control txt" id="c_prov" name="c_prov" value="<?php echo htmlspecialchars("") ?>" readonly>
                    </div>
                </div>

                <!-- {Pang Test kung nakukuha yung laman ng LOG} -->
                <input type="hidden" class="form-control txt" id="c_logname" name="c_logname" readonly>

                <p class="text-center fw-bold mt-3" style="font-size: 19px;">Payment Log's Details</p>
                <hr>

                <div class="d-flex justify-content-end mb-2">
                    <button type="button" id="btn-export" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
                    </button>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="table-container">
                            <table class="table table-bordered table-striped" id="data-table">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">No.</th>
                                        <th style="width:15%;">Log User</th>
                                        <th style="width:15%;">Log Date</th>
                                        <th style="width:15%;">Log Time</th>
                                        <th style="width:15%;">Log Module</th>
                                        <th style="width:50%;">Log Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var dt = $('#data-table').DataTable({
            pageLength: 10,
            lengthChange: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "View All"]],
            ordering: true,
            searching: true,
            responsive: true,
            order: [2, "desc"]
        });

        var errorShown = false;

        if (!String.prototype.padStart) {
            String.prototype.padStart = function(targetLength, padString) {
                targetLength = targetLength >> 0;
                padString = String(padString || ' ');
                if (this.length >= targetLength) {
                    return String(this);
                } else {
                    targetLength = targetLength - this.length;
                    if (targetLength > padString.length) {
                        padString += padString.repeat(Math.ceil(targetLength / padString.length));
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

            errorShown = false;

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

            if (block !== "") block = ('000' + block).slice(-3);
            if (lot !== "") lot = ('00' + lot).slice(-2);

            var location = phase + block + lot;

            $.ajax({
                type: "POST",
                url: '<?php echo base_url ?>account_logs/get_buyers_account.php',
                data: { account_no: accountNo, location: location },
                dataType: "json",
                success: function (response) {
                    if (response.error && !errorShown) {
                        errorShown = true;
                        alert(response.error);
                        dt.clear().draw();
                    } else if (response.records && response.records.length > 0) {
                        displayData(response.records[0]);
                        displayLogs(response.records);
                    } else {
                        dt.clear().draw();
                    }
                },
                error: function () {
                    if (!errorShown) {
                        errorShown = true;
                        alert("An error occurred while fetching account data.");
                    }
                }
            });
        });

        function displayLogs(records) {
            var rows = [];
            records.forEach(function (row, index) {
                if (row.log_name) {
                    rows.push([
                        index + 1,
                        row.log_name || "",
                        row.log_date || "",
                        row.log_time || "",
                        row.log_module || "",
                        row.log_notes || ""
                    ]);
                }
            });

            dt.clear();
            if (rows.length) {
                dt.rows.add(rows);
            }
            dt.draw();
        }

        function displayData(data) {
            $("#c_lname").val(data.c_lname || "");
            $("#c_fname").val(data.c_fname || "");
            $("#c_mname").val(data.c_mname || "");
            $("#c_address").val(data.c_address || "");
            $("#c_prov").val(data.c_prov || "");
            $("#c_accno").val(data.c_accno || "");
            $("#c_logname").val(data.log_name || "");
        }
    });
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
    document.getElementById("search-account").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });
    document.getElementById("search-block").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 3);
    });
    document.getElementById("search-lot").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 2);
    });
</script>
<script>
    $("#btn-export").on("click", function () {
        var accountNo = $("#search-account").val().trim();
        var phase = $("#search-phase").val().trim();
        var block = $("#search-block").val().trim();
        var lot   = $("#search-lot").val().trim();

        if (block !== "") block = ('000' + block).slice(-3);
        if (lot   !== "") lot   = ('00'  + lot).slice(-2);

        var location = (phase || "") + (block || "") + (lot || "");

        const qs = new URLSearchParams({
            account_no: accountNo || "",
            location: accountNo ? "" : location
        });

        window.open("export_logs_pdf.php?" + qs.toString(), "_blank");
    });
</script>

<script src="../dist/js/table.js"></script>

</body>
<?php include('../inc/footer.php'); ?>
