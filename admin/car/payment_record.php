<?php
$id = 1;
if (isset($_GET['accno']) && $_GET['accno'] !== '') {
    $id = $_GET['accno'];
}

?>



<div class="tab-pane fade" id="payment-record" role="tabpanel" aria-labelledby="payment-record-tab">
    <div class="card mt-3">
        <div class="container">
            <h2 class="text-blue h4">Payment Record </h2>
            <hr>
            <a href="<?php echo base_url ?>admin/car/print_payment_record.php?id=<?php echo $id; ?>", target="_blank" id="print_pr" class="btn btn-flat btn-success" href="javascript:void(0)">
                <span class="fa fa-download"></span> Print
               
            </a>
          
            <hr>
                <div class="container">
                    <div class="row">
                    <div class="col-12 col-md-4">
                        <label for="acct_no" class="form-label">Account No.</label>
                        <input type="text" class="form-control" id="acct_no" values = "<?php echo $id ?> " readonly>
                    </div>
               
                </div>
                <br>
                <hr>
             
            </div>

         
                        <?php
                        // Connection to PostgreSQL database
                
                        //echo $id;
                        $qry4 = pg_query($cnx, "SELECT * FROM t_payment WHERE c_account_no = '$id' ORDER BY c_payment_count,c_due_date ASC");

                        ?>
                        <table class="table table-bordered table-striped" id="car-list-table">
                            <thead>
                                <tr>
                                    <!-- <th style="text-align:center;font-size:13px;">PROPERTY ID</th> -->
                                    <th style="text-align:center;font-size:13px;">DUE DATE</th>
                                    <th style="text-align:center;font-size:13px;">PAY DATE</th>
                                    <th style="text-align:center;font-size:13px;">OR NO</th>
                                    <th style="text-align:center;font-size:13px;">AMOUNT PAID</th>
                                    <th style="text-align:center;font-size:13px;">SURCHARGE</th>
                                    <th style="text-align:center;font-size:13px;">INTEREST</th>
                                    <th style="text-align:center;font-size:13px;">PRINCIPAL</th>
                                    <th style="text-align:center;font-size:13px;">REBATE</th>
                                    <th style="text-align:center;font-size:13px;">PERIOD</th>
                                    <th style="text-align:center;font-size:13px;">BALANCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (pg_num_rows($qry4) <= 0) {
                                    echo "<tr><td colspan='10' class='text-center' style='font-size:13px;'>No Payment Records</td></tr>";
                                } else {
                                    $total_rebate = 0;
                                    while ($row = pg_fetch_assoc($qry4)): 
                                        $due_dte = $row['c_due_date'];
                                        $pay_dte = $row['c_pay_date'];
                                        $or_no = $row['c_or_no'];
                                        $amt_paid = $row['c_amount_paid'];
                                        $interest = $row['c_interest'];
                                        $principal = $row['c_principal'];
                                        $surcharge = $row['c_surcharge'];
                                        $rebate = $row['c_rebate'];
                                        $period = $row['c_status'];
                                        $balance = $row['c_balance'];

                                        $total_rebate += $rebate;
                                ?>
                                <tr>
                                    <td class="text-center" style="font-size:13px;width:12%;"><?php echo $due_dte ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:12%;"><?php echo $pay_dte ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;">
                                    <?php
                                    if (strpos($or_no, 'RSTR') === 0) {
                                        echo '<a class="basic-link view_restruc" data-id="' .$row['c_account_no'] . '" cid="' . str_replace('RSTR-', '', $or_no) . '">' . $or_no . '</a>';
                                    } elseif (strpos($or_no, 'AV') === 0) {
                                        echo '<a class="basic-link view_av" data-id="' .$row['c_account_no']. '" cid="' . $or_no . '">' . $or_no . '</a>';
                                    } elseif (strpos($or_no, 'CM') === 0 || strpos($or_no, 'DM') === 0) {
                                        $newId = substr($or_no, 2); 
                                        echo '<a class="basic-link view_cm" data-id="' . $or_no . '">' . $or_no . '</a>';
                                    } else {
                                        echo $or_no;
                                    }
                                    ?>
                                    </td> 
                                    <td class="text-center" style="font-size:13px;width:15%;"><?php echo number_format($amt_paid, 2) ?> </td>
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($surcharge, 2) ?> </td>  
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($interest, 2) ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($principal, 2) ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($rebate, 2) ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo $period ?> </td> 
                                    <td class="text-center" style="font-size:13px;width:10%;"><?php echo number_format($balance, 2) ?> </td>  
                                </tr>
                                <?php endwhile; } 
                                 pg_close($cnx);
                                ?>
                            </tbody>
                        </table>

            </div>
        </div>
    </div>
</div>