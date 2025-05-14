<?php 
session_start();
// require_once('../../../inc/check_session.php');
// check_user_group(1);

include('../../config.php');
include('../../inc/header.php');  

$account_no = htmlspecialchars($_GET['c_account_no']);
$c_account_no = $account_no;
?>

<link rel="stylesheet" href="<?php echo base_url ?>dist/css/index.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/table.css">

<style>
.form-inline-group {
    display: flex;
    flex-wrap: nowrap;
    gap: 5px;
}
.form-inline-group > div {
    flex: 1;
    min-width: 100px;
}
/* TIN NUMBER HIGHLIGHT */
/* .highlight {
    background-color: #ff6600;
    color: white;
    font-weight: bold;
    padding: 5px 5px;
    border-radius: 50px;
    display: inline-block;
    text-align: center;
} */
/* STATUS FLOW */
.progress-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    padding: 10px 5px;
    background: #f8f9fa;
    border-radius: 8px;
    font-family: 'Segoe UI', sans-serif;
    margin-top: 5px;
    gap: 4px;
}

.progress-step {
    flex: 1;
    text-align: center;
    position: relative;
}

.progress-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 14px;
    right: -50%;
    height: 3px;
    width: 100%;
    background-color: #dee2e6;
    z-index: 0;
}

.progress-step.done:not(:last-child)::after {
    background-color: #28a745;
}

.step-circle {
    height: 22px;
    width: 22px;
    background-color: #dee2e6;
    border-radius: 50%;
    margin: 0 auto;
    line-height: 22px;
    font-size: 11px;
    color: #fff;
    font-weight: bold;
    z-index: 1;
    position: relative;
}

.progress-step.done .step-circle {
    background-color: #28a745;
}

.progress-step.current .step-circle {
    background: linear-gradient(145deg, #007bff, #0056b3);
    box-shadow: 0 0 4px rgba(0, 123, 255, 0.6);
}

.step-label {
    margin-top: 4px;
    font-size: 10px;
    font-weight: 500;
    color: #495057;
}

/* Kapag Conflict and status */
.progress-step.conflict .step-circle {
    background-color: #dc3545 !important;
    color: #fff;
    box-shadow: 0 0 4px rgba(220, 53, 69, 0.6);
}
.progress-step.conflict .step-label {
    color: #dc3545;
    font-weight: bold;
}
</style>

<body>
<div class="container-fluid mt-1">
    <form id="tinForm" enctype="multipart/form-data">
        <div class="form-inline-group">
            <input type="hidden" name="id" id="c_id">
            <input type="hidden" class="form-control txt" id="c_account_no" name="c_account_no" value="<?php echo htmlspecialchars($c_account_no) ?>" readonly required>
            <div class="col-md-2">
                <label for="c_client_last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control txt" id="c_client_last_name" name="c_client_last_name" required>
            </div>
            <div class="col-md-2">
                <label for="c_client_first_name" class="form-label">First Name</label>
                <input type="text" class="form-control txt" id="c_client_first_name" name="c_client_first_name" required>
            </div>
            <div class="col-md-2">
                <label for="c_client_middle_name" class="form-label">Middle Name</label>
                <input type="text" class="form-control txt" id="c_client_middle_name" name="c_client_middle_name" required>
            </div>
            <div class="col-md-2">
                <label for="c_type" class="form-label">Type</label>
                <select class="form-control txt" id="c_type" name="c_type" required>
                    <option value=""></option>
                    <option value="Married To">Married To</option>
                    <option value="Spouses">Spouses</option>
                    <option value="Minor">Minor</option>
                    <option value="And">And</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="c_client_tin" class="form-label">TIN#</label>
                <input type="text" class="form-control txt" id="c_client_tin" name="c_client_tin" required>
            </div>
            <div class="col-md-2">
                <label for="c_status" class="form-label">Status</label>
                <select class="form-control txt" id="c_status" name="c_status" required>
                    <option value="1">For Verification</option>
                    <option value="2">ORUS Verified</option>
                    <option value="3">BIR Verification</option>
                    <option value="4">BIR Verified</option>
                    <option value="5">With Conflict</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary" id="btnsave" style="float:center; width: 100%; margin-top:23px;">Save</button>
            </div>
        </div>
    </form>
    <div class="table-container">
        <table class="table table-bordered table-striped" id="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Account No</th>
                    <th>Clients Name</th>
                    <th>Type</th>
                    <th>TIN</th>
                    <th>Status Flow</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="car-type-body">
            <?php
                if (isset($_GET['c_account_no']) && !empty($_GET['c_account_no'])) {
                    $account_no = htmlspecialchars($_GET['c_account_no']);
                    
                    $get_clients = "SELECT *
                                    FROM t_clients_tin 
                                    WHERE c_account_no = '$account_no' 
                                    ORDER BY id ASC";
                    
                    $stmt = odbc_prepare($conn, $get_clients);
                    if (odbc_execute($stmt)) {
                        $i = 1; 
                        while ($row = odbc_fetch_array($stmt)) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_account_no']); ?></td>
                                <td class="text-center">
                                    <?php 
                                        echo htmlspecialchars(
                                            $row['c_client_last_name'] . ', ' . 
                                            $row['c_client_first_name'] . ' ' . 
                                            $row['c_client_middle_name']
                                        ); 
                                    ?>
                                </td>
                                <td class="text-center"><?php echo htmlspecialchars($row['c_type']); ?></td>
                                <td class="text-center highlight"><?php echo htmlspecialchars($row['c_client_tin']); ?></td>
                                <td>
                                    <div class="progress-container">
                                        <?php
                                        $statuses = [
                                            1 => 'For Verification',
                                            2 => 'ORUS Verified',
                                            3 => 'BIR Verification',
                                            4 => 'BIR Verified',
                                            5 => 'With Conflict'
                                        ];
                                        $current_status = (int)$row['c_status'];
                                        foreach ($statuses as $code => $label) {
                                            $step_class = '';

                                            if ($code == 5 && $current_status == 5) {
                                                $step_class = 'conflict';
                                            } elseif ($code < $current_status) {
                                                $step_class = 'done';
                                            } elseif ($code == $current_status) {
                                                $step_class = 'current';
                                            }

                                            $circle_content = ($code == 5 && $current_status == 5) ? '❌' : (($code < $current_status) ? '✔' : $code);

                                            echo "
                                            <div class='progress-step $step_class'>
                                                <div class='step-circle'>$circle_content</div>
                                                <div class='step-label'>$label</div>
                                            </div>";
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td align="center">
                                    <button 
                                        type="button" 
                                        class="btn btn-flat btn-warning btn-sm edit-btn"
                                        data-id="<?php echo $row['id']; ?>"
                                        data-lastname="<?php echo htmlspecialchars($row['c_client_last_name']); ?>"
                                        data-firstname="<?php echo htmlspecialchars($row['c_client_first_name']); ?>"
                                        data-middlename="<?php echo htmlspecialchars($row['c_client_middle_name']); ?>"
                                        data-tin="<?php echo htmlspecialchars($row['c_client_tin']); ?>"
                                        data-type="<?php echo htmlspecialchars($row['c_type']); ?>"
                                        data-status="<?php echo htmlspecialchars($row['c_status']); ?>"
                                    >
                                        EDIT
                                    </button>

                                    <button 
                                        type="button" 
                                        class="btn btn-flat btn-danger btn-sm delete-btn"
                                        data-id="<?php echo $row['id']; ?>"
                                    >
                                        DELETE
                                    </button>
                                </td>
                            </tr>
                            <?php 
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Error executing query.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No account number provided.</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
    <?php include ('../modals/main_modals.php'); ?>
</div>
</body>

<script>
$(document).ready(function () {
    $(document).on('submit', '#tinForm', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url; ?>classes/Master.php?f=save_tin',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            success: function(resp) {
                console.log(resp);
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    $('#createTinModal').modal('hide');
                } else {
                    alert_toast(resp.err || "An error occurred.", 'error');
                }
            },
            error: function(err) {
                console.log(err);
                alert_toast("An error occurred.", 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const btn = $(this);
        $('#c_id').val(btn.data('id'));
        $('#c_client_last_name').val(btn.data('lastname'));
        $('#c_client_first_name').val(btn.data('firstname'));
        $('#c_client_middle_name').val(btn.data('middlename'));
        $('#c_client_tin').val(btn.data('tin'));
        $('#c_type').val(btn.data('type'));
        $('#c_status').val(btn.data('status'));

        $('html, body').animate({
            scrollTop: $('#tinForm').offset().top
        }, 500);
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: '<?php echo base_url; ?>classes/Master.php?f=delete_tin',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(resp) {
                    if (resp && resp.status === 'success') {
                        alert_toast(resp.msg, 'success');
                        $('#createTinModal').modal('hide');
                    } else {
                        alert_toast(resp.err || "Failed to delete TIN record.", 'error');
                    }
                },
                error: function(err) {
                    console.log(err);
                    alert_toast("An error occurred.", 'error');
                }
            });
        }
    });
});
</script>

<script src="../../dist/js/table.js"></script>
