<?php
require_once('../../config.php'); 
if (isset($_POST['id'])) {
    $atapId = $_POST['id'];
    $query = "SELECT * FROM t_atap WHERE id = ?";
    $stmt = odbc_prepare($conn, $query);
    odbc_execute($stmt, array($atapId));
    if ($row = odbc_fetch_array($stmt)) {
        echo '<div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit ATAP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAtapForm">
                    <input type="hidden" name="id" value="'.$row['id'].'">
                    <div class="form-group">
                        <label for="c_atap_no">ATAP No.</label>
                        <input type="text" class="form-control" id="c_atap_no" name="c_atap_no" value="'.$row['c_atap_no'].'" readonly>
                    </div>
                    <div class="form-group">
                        <label for="c_name">Name</label>
                        <input type="text" class="form-control" id="c_name" name="c_name" value="'.$row['c_name'].'">
                    </div>
                    <div class="form-group">
                        <label for="c_tran_date">Transaction Date</label>
                        <input type="text" class="form-control" id="c_tran_date" name="c_tran_date" value="'.$row['c_tran_date'].'" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>';
    } else {
        echo 'ATAP details not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
