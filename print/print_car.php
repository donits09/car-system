<?php
    include('../config.php');

    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $accountId = $_GET['id'];
        $get_car_query = "SELECT * FROM t_car_payment WHERE c_car_no = ?";
        $stmt = odbc_prepare($conn, $get_car_query);
        odbc_execute($stmt, array($accountId));
        $result = odbc_fetch_array($stmt);

        if ($result) {
            $row = $result;
?>
<link rel="stylesheet" href="../dist/css/car_print.css">
<body onload="convertCarAmountToWords()">
    <img src="<?php echo base_url ?>images/car.jpg" class="background-image" alt="Car Scanned Copy">
    <div class="container">
        <input type="text" name="c_current_date" id="c_current_date" value="<?php echo date('Y-m-d'); ?>">
        <input type="text" name="c_car_type" id="c_car_type" value="<?php echo htmlspecialchars($row['c_car_type']); ?>">
        <input type="text" name="c_car_amount" id="c_car_amount" value="<?php echo number_format($row['c_car_amount'], 2); ?>">
        <textarea name="c_car_amount_words" id="c_car_amount_words"></textarea>
        <input type="text" name="c_car_no" id="c_car_no" value="<?php echo htmlspecialchars($row['c_car_no']); ?>">

        <?php
            $c_account_no = $row['c_account_no'];
            $get_buyer_details_qry = "SELECT * FROM t_buyers_account WHERE c_account_no = '$c_account_no'";
            $results = odbc_exec($conn, $get_buyer_details_qry);
            $lname = $fname = $mname = $address = $prov = $zip = '';

            if ($buyer = odbc_fetch_array($results)) {
                $lname = $buyer["c_b1_last_name"];
                $fname = $buyer["c_b1_first_name"];
                $mname = $buyer["c_b1_middle_name"];
                $address = $buyer["c_address"];
                $prov = $buyer["c_city_prov"];
                $zip = $buyer["c_zip_code"];
            }

            $full_address = trim($address);
            if (($prov == '') && ($zip == '')) {
                $full_address = trim($address);
            } else if (($prov != '') && ($zip == '')) {
                $full_address .= ', ' . trim($prov); 
            } else if (($prov == '') && ($zip != '')) {
                $full_address .= ', ' . trim($zip); 
            } else {
                $full_address .= ', ' . trim($prov) . ', ' . trim($zip);
            }
        ?>
        <input type="text" name="c_received" id="c_received" value="<?php echo htmlspecialchars(trim($fname) . ' ' . trim($mname) . ' ' . trim($lname)); ?>" style="">
        <textarea name="c_address" id="c_address" ><?php echo $full_address; ?></textarea>
        <?php
            $c_encoded_by = $row['c_encoded_by'];
            $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_encoded_by'";
            $results = odbc_exec($conn, $get_encoder_details_qry);

            if ($encoder = odbc_fetch_array($results)) {
                $realname = $encoder["c_realname"];
            }
        ?>
        <input type="text" name="c_encoded_by" id="c_encoded_by" value="<?php echo htmlspecialchars($realname); ?>">
    </div>
<?php
        } else {
            echo "No data found for the given ID.";
        }
    } else {
        echo "Invalid request.";
    }
?>
<script src="../dist/js/amountToWords.js"></script>