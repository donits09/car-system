<?php
    session_start();
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
<div class="container-fluid">
    <div class="callout callout-primary">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th style="width: 30%;">Account No.:</th>
                    <td><?php echo $row['c_account_no']; ?></td>
                </tr>
                <tr>
                    <th>Payment Type:</th>
                    <td><?php echo $row['c_car_type']; ?></td>
                </tr>
                <tr>
                    <th>Amount:</th>
                    <td><?php echo number_format($row['c_car_amount'],2); ?></td>
                </tr>
                <tr>
                    <th>CAR No.:</th>
                    <td><?php echo $row['c_car_no']; ?></td>
                </tr>
                <tr>
                    <th>Pay Date:</th>
                    <td><?php echo $row['c_car_paydate']; ?></td>
                </tr>
                <tr>
                    <th>Encoded by:</th>
                    <?php
                        $c_encoded_by = $_SESSION['username'];
                        $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
                        $results = odbc_exec($conn, $get_encoder_details_qry);

                        if ($encoder = odbc_fetch_array($results)) {
                            $realname = $encoder["c_realname"];
                        }
                    ?>
                    <td><?php echo $realname; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
    } else {
        echo "No data found for the given ID.";
    }
} else {
    echo "Invalid request.";
}
?>
