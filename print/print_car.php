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
<style>
    body {
    position: relative;
    font-size: 8px !important;
}
.container {
    position: relative;
    width: 500px;
    padding: 20px;
    box-sizing: border-box;
    z-index: 2; 
}
.background-image {
    position: absolute;
    top: 0;
    left: 0;
    height: 280px;
    width: 670px;
    z-index: 1;
    object-fit: cover;
}
input {
    border: none;
    width: 100px;
    text-align: left;
    background-color: transparent;
}
textarea{
    width: 100px;
    text-align: left;
    font-weight: 300;
    border: none;
    background-color: transparent;
    font-size: 11px !important;
}
#c_current_date {
    float: right;
    margin-top: 85px;
    margin-right: -140px;
}
#c_car_type {
    float: right;
    margin-top: 185px;
    margin-right: -160px;
    width: 300px;
}
#c_car_amount {
    float: right;
    margin-top: 170px;
    margin-right: -310px;
    width: 140px;
}
#c_car_amount_words {
    float: right;
    margin-top: 138px;
    margin-right: -290px;
    width: 340px;
    height: auto;
    line-height: 1.2em;
    overflow: hidden;
    white-space: pre-wrap;
    word-wrap: break-word;
}
#c_car_no {
    float: right;
    margin-top: 60px;
    margin-right: -370px;
    width: 80px;
}
#c_received {
    text-transform: uppercase;
    float: right;
    margin-top: 100px;
    margin-right: -200px;
    width: 230px;
    text-align: center;
}
#c_address {
    text-transform: uppercase;
    float: right;
    margin-top: 115px;
    margin-right: -370px;
    width: 350px;
    text-align: center;
}
#c_encoded_by {
    text-transform: uppercase;
    float: right;
    margin-top: 210px;
    margin-right: -400px;
    width: 150px;
    text-align: center;
    font-size: 10px !important;
}
</style>
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