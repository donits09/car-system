<?php
    session_start();

    require_once('../../inc/check_session.php');
    check_user_group(3);
    $c_encoded_by = '';
    include('../../config.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $tenantId = $_GET['id'];
        $get_tenant_query = "SELECT * FROM t_tenant_accounts WHERE id = ?";
        $stmt = odbc_prepare($conn, $get_tenant_query);
        odbc_execute($stmt, array($tenantId));
        $result = odbc_fetch_array($stmt);
        if($result){
        $row = $result;
?>
<link rel="stylesheet" href="../../dist/css/view_car.css">
<div class="container-fluid">
    <div class="callout callout-primary">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th style="width: 30%;">Account No.:</th>
                    <?php if ($row['c_account_no'] != "" || $row['c_account_no'] != null) { ?>
                        <td><?php echo $row['c_account_no']; ?></td>
                    <?php }else{ ?>
                        <td>----------</td>
                    <?php } ?>
                </tr>
                <tr>
                    <th style="width: 30%;">Location:</th>
                    <td>
                    <?php
                        $c_account_no = $row['c_account_no'];
                        try {
                            if (!empty($c_account_no)) {
                                $c_phase = substr($c_account_no, 0, 3);
                                $c_block = ltrim(substr($c_account_no, 3, 3), '0'); 
                                $c_lot = substr($c_account_no, 6, 2);

                                $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                                $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                                if (odbc_execute($phase_stmt, array($c_phase))) {
                                    $phase_details = odbc_fetch_array($phase_stmt);

                                    if ($phase_details) {
                                        echo htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                                    } else {
                                        echo "-----";
                                    }
                                } else {
                                    echo "-----";
                                }
                            } else {
                                $c_phase = $row['c_phase'];
                                $c_block = $row['c_block'];
                                $c_lot = $row['c_lot'];     

                                if (empty($c_phase) && empty($c_block) && empty($c_lot)) {
                                    echo "-------------";
                                } else {
                                    if (!empty($c_phase) && is_numeric($c_phase)) {
                                        $get_phase_details_qry = "SELECT c_acronym FROM t_projects WHERE c_code = ?";
                                        $phase_stmt = odbc_prepare($conn, $get_phase_details_qry);

                                        if (odbc_execute($phase_stmt, array($c_phase))) {
                                            $phase_details = odbc_fetch_array($phase_stmt);

                                            if ($phase_details) {
                                                echo htmlspecialchars($phase_details["c_acronym"] . ' B' . $c_block . ' L' . $c_lot);
                                            } else {
                                                echo "-----";
                                            }
                                        } else {
                                            echo "-----";
                                        }
                                    } else {
                                        echo htmlspecialchars("-----" . ' B' . $c_block . ' L' . $c_lot);
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            echo "-----";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Name:</th>
                    <td><?php echo $row['c_first_name'] . ' ' . $row['c_middle_name'] . ' ' . $row['c_last_name']; ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td>
                        <?php 
                        $addressParts = [];
                        
                        if (!empty($row['c_address'])) {
                            $addressParts[] = $row['c_address'];
                        }
                        if (!empty($row['c_city_prov'])) {
                            $addressParts[] = $row['c_city_prov'];
                        }
                        if (!empty($row['c_zip_code'])) {
                            $addressParts[] = $row['c_zip_code'];
                        }
                        
                        echo implode(', ', $addressParts);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Tel No:</th>
                    <td><?php echo $row['c_tel_no']; ?></td>
                </tr>
                <tr>
                    <th>Mobile No.:</th>
                    <td><?php echo $row['c_mobile_no']; ?></td>
                </tr>
                <tr>
                    <th>Email Address:</th>
                    <td><?php echo $row['c_email']; ?></td>
                </tr>
                <tr>
                    <th>Civil Status:</th>
                    <td>
                        <?php
                        if ($row['c_civil_status'] == 1) {
                            echo 'Married';
                        } elseif ($row['c_civil_status'] == 2) {
                            echo 'Separated';
                        } elseif ($row['c_civil_status'] == 3) {
                            echo 'Single';
                        } elseif ($row['c_civil_status'] == 4) {
                            echo 'Widowed';
                        }else{
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Birthday:</th>
                    <td><?php echo $row['c_birthday']; ?></td>
                </tr>
                <tr>
                    <th>Gender:</th>
                    <td>
                        <?php
                        if ($row['c_sex'] == 1) {
                            echo 'Female';
                        } elseif ($row['c_sex'] == 2) {
                            echo 'Male';
                        }else{
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Employment Status:</th>
                    <td>
                        <?php
                        switch ($row['c_employment_status']) {
                            case 1:
                                echo 'Unemployed';
                                break;
                            case 2:
                                echo 'Employed';
                                break;
                            case 3:
                                echo 'Self-employed';
                                break;
                            case 4:
                                echo 'OCW';
                                break;
                            case 5:
                                echo 'Retired';
                                break;
                            case 6:
                                echo 'Others';
                                break;
                            default:
                                echo 'Not specified'; 
                                break;
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Lot Area:</th>
                    <td><?php echo $row['c_lot_area']; ?> sqm</td>
                </tr>
                <tr>
                    <th>Price per sqm:</th>
                    <td><?php echo number_format($row['c_price_sqm'], 2); ?></td>
                </tr>
                <tr>
                    <th>Remarks:</th>
                    <td style="max-width: 200px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($row['c_remarks']); ?>
                    </td>
                </tr>
                <tr>
                    <th>Encoded by:</th>
                    <?php
                        $c_encoded_by =  $row['c_encoded_by']; 
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
