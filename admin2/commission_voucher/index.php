
<style>
    
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .hidden-button {
        display: none;
    }
</style>

<?php
// Sample data for the table

$l_acct_no = isset($_GET["acct_no"]) ? $_GET["acct_no"] : '' ;

$l_agent_data = [];
if ($l_acct_no != ''){
    $l_find = $l_acct_no;

	$sql = "SELECT * FROM t_buyers_account where c_account_no = '%s' order by c_account_no ";
	$query = sprintf($sql, $l_find);
	$l_qry = odbc_exec($conn, $query);
	$l_rst = odbc_fetch_array($l_qry);
    if ($l_rst === false) {
        echo "Error: No account found with the provided account number.";
    } else {
        // Process the result as needed
        
    
	$sql2 = "SELECT * FROM t_payment WHERE c_account_no = '%s' AND c_status = 'ERN' ORDER BY c_payment_count ";
	$query2 = sprintf($sql2, $l_find);
	$l_qry1 = odbc_exec($conn, $query2);
	$l_rst1 = odbc_fetch_array($l_qry1);
	$row_count = 0;
	while (odbc_fetch_row($l_qry1)) {
		$row_count++;
	}

	$l_reservation = $l_rst['c_reservation'];
	if ($row_count > 0) {
		$l_earnest = $l_rst['c_reservation'];
		$total_paid = 0; // Initialize a variable to store the total paid amount
		for ($i = 0; $i < $row_count; $i++) {
			$l_rst1 = odbc_fetch_array($l_qry1); // Fetch the next row as an associative array
			if ($l_rst1) {
				$total_paid += intval($l_rst1['c_amount_paid']);
			}
		}
		$l_reservation = $l_earnest + $total_paid;
	}

    $l_account_no = $l_rst['c_account_no'];
    $lot_area = $l_rst['c_lot_area'];
    $flr_area = $l_rst['c_floor_area'];
    $lot_price_sqm = $l_rst['c_price_sqm'];
    $hse_price_sqm = $l_rst['c_house_price_sqm'];
    $lot_discount = $l_rst['c_lot_discount'];
    $lot_disc_amt = $l_rst['c_lot_discount_amount'];
    $hse_discount = $l_rst['c_h_discount'];
    $hse_disc_amt = $l_rst['c_h_discount_amount'];
    $tcp_disc_amt = $l_rst['c_tcp_discount_amount'];
    $tcp_discount = $l_rst['c_tcp_discount'];
    $l_location = substr($l_account_no, 0, 3) . ' ' . substr($l_account_no, 3, 3) . ' ' . substr($l_account_no, 6, 2);
	$l_name = $l_rst['c_b1_last_name'] . ', ' . $l_rst['c_b1_first_name'] . ' ' . $l_rst['c_b1_middle_name'];
	if($l_rst['c_b2_last_name'] != ''){
		$l_name2 = $l_rst['c_b2_last_name'] . ', ' . $l_rst['c_b2_first_name'] . ' ' . $l_rst['c_b2_middle_name'];
	}
	$l_net_tcp = $l_rst['c_net_tcp'];
	$l_down_per = $l_rst['c_down_percent'];
	$l_net_dp =  $l_rst['c_net_dp'];
	$l_status =  $l_rst['c_account_status'];
	$l_no_payment =  $l_rst['c_no_payments'];
	$l_date_of_sale = $l_rst['c_date_of_sale'];
	$l_network =  $l_rst['c_network'];
	$l_division =  $l_rst['c_division'];
	$l_remarks =  $l_rst['c_remarks'];
	

	$sql3 = "SELECT * FROM t_commission WHERE c_account_no = '%s' ORDER BY c_position";
    $query3 = sprintf($sql3, $l_find);
    $l_qry3 = odbc_exec($conn, $query3);

    $data = [];  // Initialize an array to store the final data

    while ($row = odbc_fetch_array($l_qry3)) {
        $l_code = strval($row['c_code']);
        $l_amount = strval($row['c_amount']);
        $l_rate = strval($row['c_rate']);

        $sql4 = "SELECT * FROM t_agents WHERE c_code = '%s'";
        $query4 = sprintf($sql4, $l_code);
        $l_qry4 = odbc_exec($conn, $query4);

        while ($result = odbc_fetch_array($l_qry4)) {
            $l_agent_name = $result['c_last_name'] . ', ' . $result['c_first_name'] . ' '  . $result['c_middle_initial'];
            $l_tax_rate = isset($result["c_withholding_tax"]) ? $result["c_withholding_tax"] : 10;
            $l_position = $row['c_position'];
            //echo $l_position;
            if($l_position == 1) {
                $l_pos = 'AVP';
            }elseif($l_position == 2){
                $l_pos = 'JAV';
            }
            elseif($l_position == 3){
                $l_pos = 'AM';
            }
            elseif($l_position == 4){
                $l_pos = 'FM';
            }
            elseif($l_position == 5){
                $l_pos = 'SM';
            }
            elseif($l_position == 6){
                $l_pos = 'MA';
            }
            elseif($l_position == 7){
                $l_pos = 'EMP';
            }
            elseif($l_position == 8){
                $l_pos = 'SPC';
            }
            elseif($l_position == 9){
                $l_pos = 'VPS';
            }
            elseif($l_position == 10){
                $l_pos = 'DS';
            }
            elseif($l_position == 11){
                $l_pos = 'SMG';
            }
            elseif($l_position == 12){
                $l_pos = 'PC';
            }
            elseif($l_position == 13){
                $l_pos = 'PD';
            }
            else{
                $l_pos = 'N/A';
            }
               
            }

            $l_data = [$l_pos, $l_code, $l_agent_name, $l_amount, $l_rate, $l_tax_rate];
            $data[] = $l_data; 
            
            $l_agent_data = [];

            foreach ($data as $row) {
                $l_agent_data[] = [
                    'pos' => $row[0],
                    'code' => $row[1],
                    'name' => $row[2],
                    'amount' => $row[3],
                    'rate' => $row[4],
                    'whtax' => $row[5]
                ];
            }
        }
        $commission_percentage = due_commission($conn, $l_account_no, $l_net_tcp, $l_down_per);
    }

    
}
    
    function due_commission($conn, $c_account_no, $net_tcp, $down_per){
        // Prepare SQL query
        $l_sql = "SELECT sum(c_principal) AS total_principal FROM t_payment WHERE c_account_no = '%s'";
        $query = sprintf($l_sql, $c_account_no);
        
        // Execute the query
        $l_qry = odbc_exec($conn, $query);
        
        if ($l_qry === false) {
            die("Query execution failed: " . odbc_errormsg($conn));
        }
    
        // Fetch the result
        $row = odbc_fetch_array($l_qry);
        if ($row === false) {
            die("Fetching result failed: " . odbc_errormsg($conn));
        }
    
        // Get the total principal
        $total_principal = $row['total_principal'];
        $total_dp = $net_tcp * ($down_per/100);
        if ($total_dp == 0) {
            $val1 = 0;
        } else {
            $val1 = (($total_principal / $total_dp) * 100);
        }
       
        //check_comm
        if ($val1 >= 10 and $val1 <= 19.99):
            $commission_for = 10;
        elseif($val1 >= 20 and $val1 <= 29.99):
            $commission_for = 20;
        elseif($val1 >= 30 and $val1 <= 39.99):
            $commission_for = 30;
        elseif($val1 >= 40 and $val1 <= 49.99):
            $commission_for = 40;
        elseif($val1 >= 50 and $val1 <= 59.99):
            $commission_for = 50;
        elseif($val1 >= 60 and $val1 <= 69.99):
            $commission_for = 60;
        elseif($val1 >= 70 and $val1 <= 79.99):
            $commission_for = 70;
        elseif($val1 >= 80 and $val1 <= 89.99):
            $commission_for = 80;
        elseif($val1 >= 90 and $val1 <= 99.99):
            $commission_for = 90;
        elseif($val1 >= 99.99):
            $commission_for = 100;
        else:
            $commission_for = 0;
        endif;

        // Process the result or return it
        return $commission_for;
    }

    


?>

    <style>
        .long-textbox {
            width: 60%;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
        }
        .highlight {
            background-color: yellow;
        }
    </style>
    <script>
        let currentRow;
        
        function displayData(pos, code, name, amount, rate, whtax, row) {
            document.getElementById('pos').value = pos;
            document.getElementById('code').value = code;
            document.getElementById('name').value = name;
            document.getElementById('amount').value = amount;
            document.getElementById('rate').value = rate;
            document.getElementById('whtax').value = whtax;

            if (currentRow) {
                currentRow.classList.remove('highlight');
            }
            row.classList.add('highlight');
            currentRow = row;
            showButton();
        }

        function showButton() {
            document.getElementById('generateButton').style.display = 'block';
        }
        
    </script>
</head>
<body>
<div class="card card-outline rounded-0 card-maroon">
	<div class="card-header">
		<h5 class="card-title"><b><i>Commissions</b></i></h5>
	</div>
    <div class="card-body">
            <!-- Tab navigation -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab1">Commission Voucher</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab2">Agent List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab3">Summary Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab4">New Commission Voucher</a>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content">
                <div id="tab1" class="container tab-pane active"><br>
                <form action="" id="filter">
                    <div class="row align-items-end">
                        <input type="hidden" id="page" name="page" value="commission_voucher" class="form-control form-control-sm rounded-0">
                    
                        <div class="col-md-3 form-group">
                            
                            <input type="text" id="acct_no" name="acct_no" value="<?= $l_acct_no ?>" class="form-control" placeholder="Enter Account No" maxlength="11">
                        </div>

                        <div class="col-md-3 form-group">
                            <button class="btn btn-primary"><i class="dw dw-search"></i> Find Account</button>
                        </div>
                    </div>
                </form>


                <form action="<?php base_url?>commission_voucher/generate_pdf.php" method="post"  target="_blank" id="print-commission">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Account No: </label>
                                    <input type="text" class="required" name="account_no" id="account_no" value="<?php echo isset($l_acct_no) ? $l_acct_no : ''  ?>" readonly>
                                </div>
                                
                            </div>
                            <div class="col-md-8">        
                                <div class="form-group">
                                    <label class="control-label">Location: </label>
                                    <input type="text" class="required long-textbox" name="location" id="location" value="<?php echo isset($l_location) ? $l_location : '' ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label class="control-label">Account Status: </label>
                                    <input type="text" class="" name="account_status" id="account_status" value="<?php echo  isset($l_status) ? $l_status : '' ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label class="control-label">Date of Sale: </label>
                                    <input type="text" class="" name="date_of_sale" id="date_of_sale" value="<?php echo isset($l_date_of_sale) ? $l_date_of_sale : '' ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label class="control-label">Buyer 1: </label>
                                    <input type="text" class="" name="buyer1" id="buyer1" value="<?php echo isset($l_name) ? $l_name :'' ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label class="control-label">Network: </label>
                                    <input type="text" class="" name="network" id="network" value="<?php echo isset($l_network) ? $l_network : '' ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label class="control-label">Buyer 2: </label>
                                    <input type="text" class="" name="buyer2" id="buyer2" value="<?php echo isset($l_name2) ? $l_name2 : '' ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label class="control-label">Division: </label>
                                    <input type="text" class="" name="division" id="division" value="<?php echo isset($l_division) ? $l_division : '' ?>" readonly>    
                                </div>
                                
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label for="pos">Position:</label>
                                    <input type="text" id="pos" name="pos" readonly><br><br>
                                </div>
                            </div>
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label for="code">Code:</label>
                                    <input type="text" id="code" name="code" readonly><br><br>
                                </div>
                            </div>
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" id="name" name="name" readonly><br><br>
                                </div>
                            </div>    
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label for="amount">Amount:</label>
                                    <input type="text" id="amount" name="amount" readonly><br><br>
                                </div>
                            </div>    
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label for="rate">Rate:</label>
                                    <input type="text" id="rate" name="rate" readonly>
                                </div>
                            </div>    
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label for="whtax">W.H. Tax:</label>
                                    <input type="text" id="whtax" name="whtax" readonly>
                                </div>
                            </div>    
                        
                        </div>
                        <div class="row">
                            <table class="table table-bordered table-stripped" id="data-table" style="text-align:center;width:100%;">
                                <thead>
                                    <colgroup>
                                    <col width="10%">
                                    <col width="5%">
                                    <col width="25%">
                                    <col width="20%">
                                    <col width="5%">
                                    <col width="5%">
                                    </colgroup>
                                    <tr>
                                        <th>Position</th>
                                        <th>Code</th>
                                        <th>Agent Name</th>
                                        <th>Amount</th>
                                        <th>Commission Rate</th>
                                        <th>W.H. Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($l_agent_data as $row): ?>
                                        <tr onclick="displayData('<?php echo $row['pos']; ?>', '<?php echo $row['code']; ?>', '<?php echo $row['name']; ?>', '<?php echo number_format($row['amount'],2); ?>', '<?php echo $row['rate']; ?>', '<?php echo $row['whtax']; ?>', this)">
                                            <td><?php echo $row['pos']; ?></td>
                                            <td><?php echo $row['code']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo number_format($row['amount'],2); ?></td>
                                            <td><?php echo $row['rate']; ?></td>
                                            <td><?php echo $row['whtax']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>    
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Net TCP: </label>
                                    <input type="text" class="" name="net_tcp" id="net_tcp" value="<?php echo  number_format( isset($l_net_tcp) ? $l_net_tcp : 0,2) ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Down %: </label>
                                    <input type="text" class="" name="down_percent" id="down_percent" value="<?php echo isset($l_down_per) ? $l_down_per : 0 ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label"># Payments: </label>
                                    <input type="text" class="" name="no_payment" id="no_payment" value="<?php echo isset($l_no_payment) ? $l_no_payment : 0 ?>" readonly>    
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Reservation: </label>
                                    <input type="text" class="" name="reservation" id="reservation" value="<?php echo  number_format(isset($l_reservation) ? $l_reservation : 0) ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Net DP: </label>
                                    <input type="text" class="" name="net_dp" id="net_dp" value="<?php echo number_format(isset($l_net_dp)? $l_net_dp : 0,2) ?>" readonly>    
                                </div>
                                
                            </div>
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Due Commission: </label>
                                    <input type="text" class="" name="due_commission" id="due_commission" value="<?php echo isset($commission_percentage) ? $commission_percentage : 0 ?>">    
                                </div>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Cash Advance: </label>
                                    <input type="text" class="" name="cash_advance" id="cash_advance" value="">    
                                </div>
                                
                            </div>
                            <div class="col-md-4">        
                                <div class="form-group">
                                    <label class="control-label">Others: </label>
                                    <input type="text" class="" name="others" id="others" value="" >    
                                </div>
                                
                            </div>

                            <div class="col-md-4">        
                                <button id="generateButton" class="btn btn-primary hidden-button">Print Commission</button>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">        
                                <div class="form-group">
                                    <label for="remarks">Remarks:</label>
                                    <textarea id="remarks" name="remarks" rows="20" cols="150" ><?php echo isset($l_remarks) ? $l_remarks : '' ?></textarea>
                                </div>
                                
                            </div>
                        
                            
                        </div>

                        
                    </div>
                </form>  
                </div>
                <div id="tab2" class="container tab-pane fade"><br>
                    <h5 class="card-title"><b><i>List of Agents</b></i></h5>
                    <div class="card-tools">
                        <a href="javascript:void(0)" id="create_new2"  data-toggle="modal" href="javascript:void(0)" data-target="#createCarModal" class="btn btn-flat btn-primary" style="font-size:14px;">
                            <span class="fas fa-plus"></span>&nbsp;&nbsp;Add New
                        </a>
                    </div>
                    <table class="table table-bordered table-stripped" id="agent-table" style="text-align:center;width:100%;">
                        <colgroup>
                            <col width="5%">
                            <col width="20%">
                            <col width="20%">
                            <col width="25%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Agent Code</th>
                            <th>Agent Name</th>
                            <th>Position</th>
                            <th>Action	</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $i = 1;
                            $sql = "SELECT * FROM t_agents order by c_hire_date";
                            $comm_result = odbc_exec($conn, $sql);
                            while ($row = odbc_fetch_array($comm_result)): 
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class=""><?php echo $row['c_code'] ?></td>
                                <td class=""><?php echo $row['c_last_name'] . ', '. $row['c_first_name'] . ' '. $row['c_middle_initial']  ?></td>
                                <td class=""><?php echo $row['c_position'] ?></td>
                                <td align="center">
                                    
                                        <button><a class="edit_data" href="javascript:void(0)" data-id ="<?php echo $row['c_code'] ?>"><!-- <span class="fa fa-edit text-primary"></span>  -->Edit</a>
                                        </button>
                                        <button><a class="delete_data" href="javascript:void(0)" data-id="<?php echo $row['c_code'] ?>"><!-- <span class="fa fa-ban text-danger"></span>  -->Cancel</a>
                                        </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div id="tab3" class="container tab-pane fade"><br>
                    <h3>Tab 3</h3>
                    <p>Content for Tab 3.</p>
                </div>
                <div id="tab4" class="container tab-pane fade"><br>
                <?php
                // Sample data for the table

                $code_no = isset($_GET["code_no"]) ? $_GET["code_no"] : '' ;

                $l_buyer_data = [];
                if ($code_no != ''){
                    $l_find = $l_acct_no;

                    $sql = "SELECT * FROM t_agents where c_code = '%s' order by c_account_no ";
                    $query = sprintf($sql, $l_find);
                    $l_qry = odbc_exec($conn, $query);
                    $l_rst = odbc_fetch_array($l_qry);
                    if ($l_rst === false) {
                        echo "Error: No account found with the provided account number.";
                    } else {
                        // Process the result as needed
                        
                    
                    $sql2 = "SELECT * FROM t_payment WHERE c_account_no = '%s' AND c_status = 'ERN' ORDER BY c_payment_count ";
                    $query2 = sprintf($sql2, $l_find);
                    $l_qry1 = odbc_exec($conn, $query2);
                    $l_rst1 = odbc_fetch_array($l_qry1);
                    $row_count = 0;
                    while (odbc_fetch_row($l_qry1)) {
                        $row_count++;
                    }

                    $l_reservation = $l_rst['c_reservation'];
                    if ($row_count > 0) {
                        $l_earnest = $l_rst['c_reservation'];
                        $total_paid = 0; // Initialize a variable to store the total paid amount
                        for ($i = 0; $i < $row_count; $i++) {
                            $l_rst1 = odbc_fetch_array($l_qry1); // Fetch the next row as an associative array
                            if ($l_rst1) {
                                $total_paid += intval($l_rst1['c_amount_paid']);
                            }
                        }
                        $l_reservation = $l_earnest + $total_paid;
                    }

                    $l_account_no = $l_rst['c_account_no'];
                    $lot_area = $l_rst['c_lot_area'];
                    $flr_area = $l_rst['c_floor_area'];
                    $lot_price_sqm = $l_rst['c_price_sqm'];
                    $hse_price_sqm = $l_rst['c_house_price_sqm'];
                    $lot_discount = $l_rst['c_lot_discount'];
                    $lot_disc_amt = $l_rst['c_lot_discount_amount'];
                    $hse_discount = $l_rst['c_h_discount'];
                    $hse_disc_amt = $l_rst['c_h_discount_amount'];
                    $tcp_disc_amt = $l_rst['c_tcp_discount_amount'];
                    $tcp_discount = $l_rst['c_tcp_discount'];
                    $l_location = substr($l_account_no, 0, 3) . ' ' . substr($l_account_no, 3, 3) . ' ' . substr($l_account_no, 6, 2);
                    $l_name = $l_rst['c_b1_last_name'] . ', ' . $l_rst['c_b1_first_name'] . ' ' . $l_rst['c_b1_middle_name'];
                    if($l_rst['c_b2_last_name'] != ''){
                        $l_name2 = $l_rst['c_b2_last_name'] . ', ' . $l_rst['c_b2_first_name'] . ' ' . $l_rst['c_b2_middle_name'];
                    }
                    $l_net_tcp = $l_rst['c_net_tcp'];
                    $l_down_per = $l_rst['c_down_percent'];
                    $l_net_dp =  $l_rst['c_net_dp'];
                    $l_status =  $l_rst['c_account_status'];
                    $l_no_payment =  $l_rst['c_no_payments'];
                    $l_date_of_sale = $l_rst['c_date_of_sale'];
                    $l_network =  $l_rst['c_network'];
                    $l_division =  $l_rst['c_division'];
                    $l_remarks =  $l_rst['c_remarks'];
                    

                    $sql3 = "SELECT * FROM t_commission WHERE c_account_no = '%s' ORDER BY c_position";
                    $query3 = sprintf($sql3, $l_find);
                    $l_qry3 = odbc_exec($conn, $query3);

                    $data = [];  // Initialize an array to store the final data

                    while ($row = odbc_fetch_array($l_qry3)) {
                        $l_code = strval($row['c_code']);
                        $l_amount = strval($row['c_amount']);
                        $l_rate = strval($row['c_rate']);

                        $sql4 = "SELECT * FROM t_agents WHERE c_code = '%s'";
                        $query4 = sprintf($sql4, $l_code);
                        $l_qry4 = odbc_exec($conn, $query4);

                        while ($result = odbc_fetch_array($l_qry4)) {
                            $l_agent_name = $result['c_last_name'] . ', ' . $result['c_first_name'] . ' '  . $result['c_middle_initial'];
                            $l_tax_rate = isset($result["c_withholding_tax"]) ? $result["c_withholding_tax"] : 10;
                            $l_position = $row['c_position'];
                            //echo $l_position;
                            if($l_position == 1) {
                                $l_pos = 'AVP';
                            }elseif($l_position == 2){
                                $l_pos = 'JAV';
                            }
                            elseif($l_position == 3){
                                $l_pos = 'AM';
                            }
                            elseif($l_position == 4){
                                $l_pos = 'FM';
                            }
                            elseif($l_position == 5){
                                $l_pos = 'SM';
                            }
                            elseif($l_position == 6){
                                $l_pos = 'MA';
                            }
                            elseif($l_position == 7){
                                $l_pos = 'EMP';
                            }
                            elseif($l_position == 8){
                                $l_pos = 'SPC';
                            }
                            elseif($l_position == 9){
                                $l_pos = 'VPS';
                            }
                            elseif($l_position == 10){
                                $l_pos = 'DS';
                            }
                            elseif($l_position == 11){
                                $l_pos = 'SMG';
                            }
                            elseif($l_position == 12){
                                $l_pos = 'PC';
                            }
                            elseif($l_position == 13){
                                $l_pos = 'PD';
                            }
                            else{
                                $l_pos = 'N/A';
                            }
                            
                            }

                            $l_data = [$l_pos, $l_code, $l_agent_name, $l_amount, $l_rate, $l_tax_rate];
                            $data[] = $l_data; 
                            
                            $l_agent_data = [];

                            foreach ($data as $row) {
                                $l_agent_data[] = [
                                    'pos' => $row[0],
                                    'code' => $row[1],
                                    'name' => $row[2],
                                    'amount' => $row[3],
                                    'rate' => $row[4],
                                    'whtax' => $row[5]
                                ];
                            }
                        }
                        $commission_percentage = due_commission($conn, $l_account_no, $l_net_tcp, $l_down_per);
                    }

                    
                }?>
                    <form action="" id="filter">
                        <div class="row align-items-end">
                            <input type="hidden" id="page" name="page" value="commission_voucher" class="form-control form-control-sm rounded-0">
                        
                            <div class="col-md-3 form-group">
                                
                                <input type="text" id="code_no" name="code_no" value="<?= $code_no ?>" class="form-control" placeholder="Enter Code No" maxlength="6">
                            </div>

                            <div class="col-md-3 form-group">
                                <button class="btn btn-primary"><i class="dw dw-search"></i> Find Agent</button>
                            </div>
                        </div>
                    </form>


                    <form action="<?php base_url?>commission_voucher/generate_pdf.php" method="post"  target="_blank" id="print-commission">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Code No: </label>
                                        <input type="text" class="required" name="code_no" id="code_no" value="" readonly>
                                    </div>
                                    
                                </div>
                                <div class="col-md-8">        
                                    <div class="form-group">
                                        <label class="control-label">Status: </label>
                                        <input type="text" class="required" name="agent_status" id="agent_status" value="" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="control-label">Position: </label>
                                        <input type="text" class="" name="agent_pos" id="agent_pos" value="" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="control-label">Date Hired: </label>
                                        <input type="text" class="" name="date_hired" id="date_hired" value="" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="control-label">Agent Name: </label>
                                        <input type="text" class="" name="agent_name" id="agent_name" value="" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="control-label">Network: </label>
                                        <input type="text" class="" name="network" id="network" value="<?php echo isset($l_network) ? $l_network : '' ?>" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">        
                                    <div class="form-group">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">        
                                    <div class="form-group">
                                        <label class="control-label">Division: </label>
                                        <input type="text" class="" name="division" id="division" value="<?php echo isset($l_division) ? $l_division : '' ?>" readonly>    
                                    </div>
                                    
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label for="pos">Account No:</label>
                                        <input type="text" id="acc" name="acc" readonly><br><br>
                                    </div>
                                </div>
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label for="code">Location:</label>
                                        <input type="text" id="loc" name="loc" readonly><br><br>
                                    </div>
                                </div>
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" readonly><br><br>
                                    </div>
                                </div>    
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label for="amount">Amount:</label>
                                        <input type="text" id="amount" name="amount" readonly><br><br>
                                    </div>
                                </div>    
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label for="rate">Rate:</label>
                                        <input type="text" id="rate" name="rate" readonly>
                                    </div>
                                </div>    
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label for="whtax">W.H. Tax:</label>
                                        <input type="text" id="whtax" name="whtax" readonly>
                                    </div>
                                </div>    
                            
                            </div>
                            <div class="row">
                                <table class="table table-bordered table-stripped" id="data-table" style="text-align:center;width:100%;">
                                    <thead>
                                        <colgroup>
                                        <col width="15%">
                                        <col width="25%">
                                        <col width="25%">
                                        <col width="20%">
                                        <col width="5%">
                                        <col width="5%">
                                        </colgroup>
                                        <tr>
                                            <th>Account No</th>
                                            <th>Location</th>
                                            <th>Buyer Name</th>
                                            <th>Amount</th>
                                            <th>Commission Rate</th>
                                            <th>W.H. Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach ($l_agent_data as $row): ?>
                                            <tr onclick="displayData('<?php echo $row['acc']; ?>', '<?php echo $row['loc']; ?>', '<?php echo $row['name']; ?>', '<?php echo number_format($row['amount'],2); ?>', '<?php echo $row['rate']; ?>', '<?php echo $row['whtax']; ?>', this)">
                                                <td><?php echo $row['acc']; ?></td>
                                                <td><?php echo $row['loc']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo number_format($row['amount'],2); ?></td>
                                                <td><?php echo $row['rate']; ?></td>
                                                <td><?php echo $row['whtax']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Net TCP: </label>
                                        <input type="text" class="" name="net_tcp" id="net_tcp" value="<?php echo  number_format( isset($l_net_tcp) ? $l_net_tcp : 0,2) ?>" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Down %: </label>
                                        <input type="text" class="" name="down_percent" id="down_percent" value="<?php echo isset($l_down_per) ? $l_down_per : 0 ?>" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label"># Payments: </label>
                                        <input type="text" class="" name="no_payment" id="no_payment" value="<?php echo isset($l_no_payment) ? $l_no_payment : 0 ?>" readonly>    
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Reservation: </label>
                                        <input type="text" class="" name="reservation" id="reservation" value="<?php echo  number_format(isset($l_reservation) ? $l_reservation : 0) ?>" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Net DP: </label>
                                        <input type="text" class="" name="net_dp" id="net_dp" value="<?php echo number_format(isset($l_net_dp)? $l_net_dp : 0,2) ?>" readonly>    
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Due Commission: </label>
                                        <input type="text" class="" name="due_commission" id="due_commission" value="<?php echo isset($commission_percentage) ? $commission_percentage : 0 ?>">    
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Cash Advance: </label>
                                        <input type="text" class="" name="cash_advance" id="cash_advance" value="">    
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">        
                                    <div class="form-group">
                                        <label class="control-label">Others: </label>
                                        <input type="text" class="" name="others" id="others" value="" >    
                                    </div>
                                    
                                </div>

                                <div class="col-md-4">        
                                    <button id="generateButton" class="btn btn-primary hidden-button">Print Commission</button>
                                </div>
                                
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">        
                                    <div class="form-group">
                                        <label for="remarks">Remarks:</label>
                                        <textarea id="remarks" name="remarks" rows="20" cols="150" ><?php echo isset($l_remarks) ? $l_remarks : '' ?></textarea>
                                    </div>
                                    
                                </div>
                            
                                
                            </div>

                            
                        </div>
                    </form>  
                </div>
            </div>
        </div>
 
</div>
</body>
<script>
$(document).ready(function(){
   
	$('#agent-table').DataTable({
		"paging": true,
		"searching": true,
		"ordering": true,
		"info": false,
		"responsive": false
	});


	$('#create_new2').click(function(){
		uni_modal("Add New Agent","commission_voucher/agent.php",'')
	});

    $('.edit_data').click(function(){
		uni_modal("Update Agent Details","commission_voucher/agent.php?id="+$(this).attr('data-id'),'')
	})
	$('.delete_data').click(function(){
		_conf("Are you sure you want to delete this permanently?","delete_agent",[$(this).attr('data-id')])
	})

    function delete_account($id){
	
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_agent",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
					console.log('test');
				}else{
					alert_toast("An error occured.",'error');
					
				}
			}
		})
	}

});
</script>