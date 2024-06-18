<?php
include('../config.php');
function fetchBuyerDetails($conn, $accountNo) {
    if (empty($accountNo)) {
        return false;
    }
    $query = "SELECT * FROM t_buyers_account WHERE c_account_no = ?";
    $stmt = odbc_prepare($conn, $query);

    if (odbc_execute($stmt, array($accountNo))) {
        return odbc_fetch_array($stmt);
    }
    return false;
}

function fetchCarDetails($conn, $carNo) {
    $query = "SELECT * FROM t_other_car_payment WHERE c_car_no = ?";
    $stmt = odbc_prepare($conn, $query);
    if (odbc_execute($stmt, array($carNo))) {
        return odbc_fetch_array($stmt);
    }
    return false;
}

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $accountId = $_GET['id'];
    $get_car_query = "SELECT a.id, a.c_account_no, a.c_car_no, a.c_car_type,
                      a.c_car_paydate,a.c_car_amount,a.c_encoded_by,a.c_tran_date,a.c_tran_updated,a.c_mop, b.c_name, b.c_phase,
                      b.c_block, b.c_lot
                      FROM t_car_payment a
                      LEFT JOIN t_other_car_payment b ON a.c_car_no = b.c_car_no
                      WHERE a.c_car_no = ?";
    $stmt = odbc_prepare($conn, $get_car_query);
    odbc_execute($stmt, array($accountId));
    $result = odbc_fetch_array($stmt);

    if ($result) {
        $row = $result;
        $c_account_no = $row['c_account_no'];
        $buyerDetails = fetchBuyerDetails($conn, $c_account_no);
        $carDetails = fetchCarDetails($conn, $row['c_car_no']);
        ?>
</html>
        <link rel="stylesheet" href="../dist/css/car_print.css">
        <body onload="convertCarAmountToWords()">
        <img src="<?php echo base_url ?>images/car.jpg" class="background-image" alt="Car Scanned Copy">
        <div class="container">
            <div class="box_middle">
                <input type="text" name="c_current_date" id="c_current_date" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <input type="text" name="c_acc_no" id="c_acc_no" value="<?php echo $c_account_no; ?>">
            <input type="text" name="c_car_type" id="c_car_type" value="<?php echo htmlspecialchars($row['c_car_type']); ?>">
            <input type="text" name="c_car_amount" id="c_car_amount" value="<?php echo number_format($row['c_car_amount'], 2); ?>">
            <textarea name="c_car_amount_words" id="c_car_amount_words"></textarea>
            <input type="text" name="c_car_no" id="c_car_no" value="<?php echo htmlspecialchars($row['c_car_no']); ?>">
            

               <?php $c_mop = isset($row['c_mop']) ? $row['c_mop'] : 0; ?>
                <div class="dynamic-margin" id="dynamicMarginDiv">
                    <input type="text" id="c_mop" value="<?php echo number_format($row['c_car_amount'], 2); ?>">
                    <input type="hidden" id="c_mop_value" value="<?php echo ($row['c_mop']); ?>">
                </div>

                <script>
                    var cMopValue = document.getElementById('c_mop_value').value;
                    var dynamicMarginDiv = document.getElementById('dynamicMarginDiv');
                    if (cMopValue == '1') {
                        dynamicMarginDiv.style.marginTop = '190px';
                    } else {
                        dynamicMarginDiv.style.marginTop = '205px';
                    }
                </script>
            <?php if ($buyerDetails) {
                $lname = $buyerDetails["c_b1_last_name"];
                $fname = $buyerDetails["c_b1_first_name"];
                $mname = $buyerDetails["c_b1_middle_name"];
                $address = $buyerDetails["c_address"];
                $prov = $buyerDetails["c_city_prov"];
                $zip = $buyerDetails["c_zip_code"];
                $c_account_no = $buyerDetails["c_account_no"];

                $c_phase = substr($c_account_no, 0, 3);
                $c_block = ltrim(substr($c_account_no, 3, 3), '0'); 
                $c_lot = substr($c_account_no, 6, 2);

                $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                if (odbc_execute($phase_stmt, array($c_phase))) {
                    $phase_details = odbc_fetch_array($phase_stmt);

                    if ($phase_details) {
                        $loc = $phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot;
                    } else {
                    }
                } else {
                }

                $full_address = trim($address);
                if ($prov || $zip) {
                    $full_address .= ($prov ? ', ' . trim($prov) : '') . ($zip ? ', ' . trim($zip) : '');
                }

                $fullName = htmlspecialchars(trim($fname) . ' ' . trim($mname) . ' ' . trim($lname));
            ?>
                <input type="text" name="c_received" id="c_received" value="<?php echo $fullName; ?>">
                <textarea name="c_address" id="c_address"><?php echo $full_address; ?></textarea>
                <textarea name="c_loc" id="c_loc"><?php echo $loc; ?></textarea>
            <?php } else if ($carDetails) {
                $car_no = $carDetails["c_car_no"];
                $c_name = $carDetails["c_name"];
                $c_phase = $row['c_phase'];
                $c_block = $row['c_block'];
                $c_lot = $row['c_lot'];
                $loc ="";

                if (empty($c_phase) && empty($c_block) && empty($c_lot)) {
                } else {
                    $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                    $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                    if (odbc_execute($phase_stmt, array($c_phase))) {
                        $phase_details = odbc_fetch_array($phase_stmt);
    
                        if ($phase_details) {
                            $loc = $phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot;
                        } else {
                        }
                    } else {
                    }
                }
        
            
            ?>
                <input type="text" name="c_received" id="c_received" value="<?php echo htmlspecialchars($c_name); ?>">
                <textarea name="c_address" id="c_address">-----------------</textarea>
                <textarea name="c_loc" id="c_loc"><?php echo $loc; ?></textarea>
            <?php } ?>

            <?php
                $c_encoded_by = $row['c_encoded_by'];
                $get_encoder_details_qry = "SELECT * FROM t_car_users WHERE c_employee_code = ?";
                $encoder_stmt = odbc_prepare($conn, $get_encoder_details_qry);
                odbc_execute($encoder_stmt, array($c_encoded_by));
                if ($encoder = odbc_fetch_array($encoder_stmt)) {
                    $realname = htmlspecialchars($encoder["c_realname"]);
                }
            ?>
           
            <input type="text" name="c_encoded_by" id="c_encoded_by" value="<?php echo $realname; ?>">
        </div>
        </body>
        <?php
    } else {
        echo "No data found for the given ID.";
    }
} else {
    echo "Invalid request.";
}
?>
<script src="../dist/js/amountToWords.js"></script>
