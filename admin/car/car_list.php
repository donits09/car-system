<?php
include('../../config.php');

$account_no = $_GET['account_no'];
$car_list = "SELECT * FROM t_car_payment WHERE c_account_no = ?";

$stmt = odbc_prepare($conn, $car_list);

if ($stmt && odbc_execute($stmt, array($account_no))) {
    $i = 1;
    while ($row = odbc_fetch_array($stmt)): 
?>
<tr>
    <td class="text-center"><?php echo $i++; ?></td>
    <td class="text-center"><?php echo $row['c_account_no']; ?></td>
    <td class="text-center"><?php echo $row['c_car_type']; ?></td>
    <td class="text-center"><?php echo $row['c_car_amount']; ?></td>
    <td class="text-center"><?php echo $row['c_car_no']; ?></td>
    <td class="text-center"><?php echo $row['c_car_paydate']; ?></td>
    <td class="text-center"><?php echo $row['c_encoded_by']; ?></td>
    <td align="center">
        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
            Action
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu">
            <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                <span class="fa fa-eye text-primary"></span> View
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item edit_data" href="javascript:void(0)" 
            data-id="<?php echo $row['id']; ?>" 
            data-account-no="<?php echo $row['c_account_no']; ?>" 
            data-payment-type="<?php echo $row['c_car_type']; ?>" 
            data-amount="<?php echo $row['c_car_amount']; ?>" 
            data-car-no="<?php echo $row['c_car_no']; ?>" 
            data-pay-date="<?php echo $row['c_car_paydate']; ?>" 
            data-encoder="<?php echo $row['c_encoded_by']; ?>">
                <span class="fa fa-edit text-primary"></span> Edit
            </a>
            <div class="dropdown-divider"></div>
            <div class="card-tools">
                <a class="dropdown-item" href="<?php echo base_url ?>print/print_car.php?id=<?php echo $row['c_car_no']; ?>" target="_blank">
                    <span class="fas fa-print"></span> Print
                </a>
            </div>


            <div class="dropdown-divider"></div>
            <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-car-no="<?php echo $row['c_car_no']; ?>">
                <span class="fa fa-trash text-danger"></span> Delete
            </a>
        </div>
    </td>
</tr>
<?php 
    endwhile; 
} else {
    echo "Error executing query.";
}
?>
