<?php
    include('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $accountId = $_GET['id'];
    $get_car_query = "SELECT * FROM t_car_payment WHERE id = ?";
    $stmt = odbc_prepare($conn, $get_car_query);
    odbc_execute($stmt, array($accountId));
    $result = odbc_fetch_array($stmt);
    if($result){
        $row = $result;
?>
<style>
    #uni_modal .modal-footer {
        display: none
    }
    body {
        font-size: 12px;
    }
</style>
<div class="container-fluid">
    <div class="callout callout-primary">
        <dl class="row">
            <dt class="col-sm-4">Account No.:</dt>
            <dd class="col-sm-8"><?php echo $row['c_account_no']; ?></dd>
            <dt class="col-sm-4">Payment Type:</dt>
            <dd class="col-sm-8"><?php echo $row['c_car_type']; ?></dd>
            <dt class="col-sm-4">Amount:</dt>
            <dd class="col-sm-8"><?php echo $row['c_car_amount']; ?></dd>
            <dt class="col-sm-4">CAR No.:</dt>
            <dd class="col-sm-8"><?php echo $row['c_car_no']; ?></dd>
            <dt class="col-sm-4">Pay Date:</dt>
            <dd class="col-sm-8"><?php echo $row['c_car_paydate']; ?></dd>
            <dt class="col-sm-4">Encoder:</dt>
            <dd class="col-sm-8"><?php echo $row['c_encoded_by']; ?></dd>
        </dl>
    </div>
    <table style="width:100%;">
        <tr>
            <td>
                <button class="btn btn-dark btn-flat btn-default" type="button" style="width:100%; margin-left:5px;font-size:14px;" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Close&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
            </td>
        </tr>
    </table>
</div>

<?php
    } else {
        echo "No data found for the given ID.";
    }
} else {
    echo "Invalid request.";
}
?>
