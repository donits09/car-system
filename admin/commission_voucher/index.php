
<?php
  require_once('inc.php');
?>
<?php
ini_set('date.timezone','Asia/Manila');
date_default_timezone_set('Asia/Manila');


$dsn = "PostgreSQL30"; // Replace with your DSN name
$user = "postgres";    // Replace with your database username
$pass = "admin12345";    // Replace with your database password

$conn = odbc_connect($dsn, $user, $pass);

if (!$conn) {
    die("Connection failed: " . odbc_errormsg());
}

date_default_timezone_set('Asia/Manila');

?>
<?php
// Sample data for the table

$l_acct_no = isset($_GET["acct_no"]) ? $_GET["acct_no"] : '' ;


if ($l_acct_no != ''){
    $l_find = $l_acct_no;

	$sql = "SELECT * FROM t_buyers_account where c_account_no = '%s' order by c_account_no ";
	$query = sprintf($sql, $l_find);
	$l_qry = odbc_exec($conn, $query);
	$l_rst = odbc_fetch_array($l_qry);

	
	$sql2 = "SELECT * FROM t_payment WHERE c_account_no = '%s' AND c_status = 'ERN' ORDER BY c_payment_count ";
	$query2 = sprintf($sql2, $l_find);
	$l_qry1 = odbc_exec($conn, $query2);
	$l_rst1 = odbc_fetch_array($l_qry1);
	$row_count = 0;
	while (odbc_fetch_row($l_qry1)) {
		$row_count++;
	}

	$l_reserved = $l_rst['c_reservation'];
	if ($row_count > 0) {
		$l_earnest = $l_rst['c_reservation'];
		$total_paid = 0; // Initialize a variable to store the total paid amount
		for ($i = 0; $i < $row_count; $i++) {
			$l_rst1 = odbc_fetch_array($l_qry1); // Fetch the next row as an associative array
			if ($l_rst1) {
				$total_paid += intval($l_rst1['c_amount_paid']);
			}
		}
		$l_reserved = $l_earnest + $total_paid;
	}

    $l_account_no = $l_rst['c_account_no'];
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
        $val1 = (($total_principal / $total_dp) * 100);

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
        }
    </script>
</head>
<body>
<div class="card card-outline rounded-0 card-maroon">
	<div class="card-header">
		<h5 class="card-title"><b><i>Commissions Voucher</b></i></h5>
	
	</div>
    <div class="card-body">
		<form action="" id="filter">
			<div class="row align-items-end">
				<input type="hidden" id="page" name="page" value="commission_voucher" class="form-control form-control-sm rounded-0">
			
				<div class="col-md-3 form-group">
					
					<input type="text" id="acct_no" name="acct_no" value="<?= $l_acct_no ?>" class="form-control" placeholder="Enter Account No" maxlength="11">
				</div>

				<div class="col-md-3 form-group">
					<button class="btn btn-primary"><i class="dw dw-search"></i> Find Account</button>
					<!-- <button class="btn btn-default border btn-flat btn-sm" id="print" type="button"><i class="fa fa-print"></i> Print</button> -->
				</div>
			</div>
		</form>


        <form action="<?php base_url ?>commission_voucher/generate_pdf.php" method="post"  target="_blank" id="print-commission">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">        
                        <div class="form-group">
                            <label class="control-label">Account No: </label>
                            <input type="text" class="required" name="account_no" id="account_no" value="<?php echo $l_acct_no ?>" readonly>
                        </div>
                        
                    </div>
                    <div class="col-md-8">        
                        <div class="form-group">
                            <label class="control-label">Location: </label>
                            <input type="text" class="required long-textbox" name="location" id="location" value="<?php echo $l_location ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-6">        
                        <div class="form-group">
                            <label class="control-label">Account Status: </label>
                            <input type="text" class="" name="account_status" id="account_status" value="<?php echo $l_status ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-6">        
                        <div class="form-group">
                            <label class="control-label">Date of Sale: </label>
                            <input type="text" class="" name="date_of_sale" id="date_of_sale" value="<?php echo $l_date_of_sale ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-6">        
                        <div class="form-group">
                            <label class="control-label">Buyer 1: </label>
                            <input type="text" class="" name="buyer1" id="buyer1" value="<?php echo $l_name ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-6">        
                        <div class="form-group">
                            <label class="control-label">Network: </label>
                            <input type="text" class="" name="network" id="network" value="<?php echo $l_network ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-6">        
                        <div class="form-group">
                            <label class="control-label">Buyer 2: </label>
                            <input type="text" class="" name="buyer2" id="buyer2" value="<?php echo $l_name2 ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-6">        
                        <div class="form-group">
                            <label class="control-label">Division: </label>
                            <input type="text" class="" name="division" id="division" value="<?php echo $l_division ?>" readonly>    
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
                </form>
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
                            <?php foreach ($l_agent_data as $row): ?>
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
                            <input type="text" class="" name="net_tcp" id="net_tcp" value="<?php echo  number_format($l_net_tcp) ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-4">        
                        <div class="form-group">
                            <label class="control-label">Down %: </label>
                            <input type="text" class="" name="down_percent" id="down_percent" value="<?php echo $l_down_per ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-4">        
                        <div class="form-group">
                            <label class="control-label"># Payments: </label>
                            <input type="text" class="" name="no_payment" id="no_payment" value="<?php echo $l_no_payment ?>" readonly>    
                        </div>
                        
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-4">        
                        <div class="form-group">
                            <label class="control-label">Reservation: </label>
                            <input type="text" class="" name="reservation" id="reservation" value="<?php echo  number_format($l_reserved) ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-4">        
                        <div class="form-group">
                            <label class="control-label">Net DP: </label>
                            <input type="text" class="" name="net_dp" id="net_dp" value="<?php echo number_format($l_net_dp,2) ?>" readonly>    
                        </div>
                        
                    </div>
                    <div class="col-md-4">        
                        <div class="form-group">
                            <label class="control-label">Due Commission: </label>
                            <input type="text" class="" name="due_commission" id="due_commission" value="<?php echo $commission_percentage ?>">    
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
                       <button> Print </button>
                        
                    </div>
                    
                </div>
				<hr>
				<div class="row">
                    <div class="col-md-12">        
						<div class="form-group">
                            <label for="remarks">Remarks:</label>
                            <textarea id="remarks" name="remarks" rows="20" cols="150" ><?php echo $l_remarks ?></textarea>
                        </div>
                        
                    </div>
                  
                    
                </div>

                
            </div>
        </form>  
        
        

    </div>
</div>
</body>

