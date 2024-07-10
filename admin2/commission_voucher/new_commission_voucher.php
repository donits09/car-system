

<?php
// PostgreSQL database connection parameters
$dbhost = 'localhost';
$dbport = '5432'; // default PostgreSQL port
$dbname = 'CAR_TESTDB';
$dbuser = 'glicelo';
$dbpass = 'admin12345';

// Establish PostgreSQL database connection
$cnx = pg_connect("host=$dbhost port=$dbport dbname=$dbname user=$dbuser password=$dbpass");

// Check connection status
if (!$cnx) {
    die("Error connecting to PostgreSQL database: " . pg_last_error());
}

// Sample data for the table
$code_no = isset($_GET["code_no"]) ? $_GET["code_no"] : '';
$l_date = "2024-05-27";
$l_buyer_data = [];
$l_agent_data2 = [];

if ($code_no != '') {
    $l_find = $code_no;

    // Query to fetch agent details
    $sql4 = "SELECT * FROM t_agents WHERE c_code = '%s'";
    $query4 = sprintf($sql4, $l_find);
    $l_qry4 = pg_query($cnx, $query4);

    if (!$l_qry4) {
        die("Error in SQL query: " . pg_last_error());
    }

    // Fetch agent details
    if (pg_num_rows($l_qry4) > 0) {
        $result = pg_fetch_assoc($l_qry4);
        $l_code = $result['c_code'];
        $l_agent_name = $result['c_last_name'] . ', ' . $result['c_first_name'] . ' ' . $result['c_middle_initial'];
        $l_tax_rate = isset($result["c_withholding_tax"]) ? $result["c_withholding_tax"] : 10;
        $l_position = $result['c_position'];
        $l_status = $result['c_status'];
        $l_date_hired = $result['c_hire_date'];
        $l_network = $result['c_network'];
        $l_division = $result['c_division'];

        // Query commissions
        $sql = "SELECT t_commission.c_code, t_commission.c_amount, t_commission.c_account_no, t_commission.c_rate, 
                    t_buyers_account.c_down_percent, t_buyers_account.c_b1_first_name, t_buyers_account.c_b1_last_name, 
                    t_commission.c_sale , t_commission.c_net_tcp
                FROM t_agents 
                LEFT JOIN t_commission ON t_agents.c_code = t_commission.c_code 
                LEFT JOIN t_buyers_account ON t_commission.c_account_no = t_buyers_account.c_account_no 
                RIGHT JOIN t_new_commission_log ON t_commission.c_code = t_new_commission_log.c_code 
                WHERE t_new_commission_log.c_print_date = '2024-05-27' 
                ORDER BY t_commission.c_account_no";
        $query = sprintf($sql, $l_code, $l_date);
        $l_qry = pg_query($cnx, $query);

        if (!$l_qry) {
            die("Error in SQL query: " . pg_last_error());
        }

        $l_row = pg_num_rows($l_qry);

        if ($l_row == 0) {
            echo "<script>alert('No Commission Found');</script>";
        } else {
            $l_agent_data = [];
            while ($row = pg_fetch_assoc($l_qry)) {
                // Calculate due commission
                $agent_code = $row['c_code'];
                $l_amount = $row['c_amount'];
                $l_acct_no = $row['c_account_no'];
                $l_location = substr($l_acct_no, 0, 3) . ' ' . substr($l_acct_no, 3, 3) . ' ' . substr($l_acct_no, 6, 2);
                $l_rate = $row['c_rate'];
                $l_net_tcp = $row['c_net_tcp'];
                $l_down_percent = $row['c_down_percent'];
                $l_first_name = $row['c_b1_first_name'];
                $l_last_name = $row['c_b1_last_name'];
                $buyer_name = $l_last_name . ', ' . $l_first_name;
                $l_comm_type = $row['c_sale'];
                $get_val = due_commission2($cnx,$l_acct_no, $l_net_tcp, $l_down_percent, $row['c_amount'], $row['c_sale']);
                $l_val = $get_val[0][0];
                $l_get_comm = $get_val[0][1];
                $l_tot_dp = $get_val[0][2];
                $l_prev_comm = $get_val[0][3];

                // Prepare data for display
                $l_data2 = [
                    'acc' => $row['c_account_no'],
                    'loc' => $l_location,
                    'agent_name' => $row['c_b1_last_name'] . ', ' . $row['c_b1_first_name'],
                    'amount' => ftom($row['c_amount']), // Assuming ftom() function is defined somewhere
                    'rate' => ftoa($row['c_rate']), // Assuming ftoa() function is defined somewhere
                    'whtax' => isset($row['c_withholding_tax']) ? $row['c_withholding_tax'] : ''
                ];

                $l_agent_data2[] = $l_data2;
            }
        }
    }
}

// Function to calculate due commission
function due_commission2($conn, $acc_no, $net_tcp, $down_per, $l_amount, $l_comm_type)
{
    // Initialize an empty array to store data
    $l_list = [];
    $l_val = 0;
    $l_get_comm = 0;

    // Query to get total principal
    $l_sql = "SELECT sum(c_principal) AS c_total_principal FROM t_payment WHERE c_account_no = $1";
    $l_stmt = pg_prepare($conn, "", $l_sql);

    if ($l_stmt === false) {
        die("Query preparation failed: " . pg_last_error());
    }

    $l_qry = pg_execute($conn, "", [$acc_no]);

    if ($l_qry === false) {
        die("Query execution failed: " . pg_last_error());
    }

    // Fetch the result
    $row = pg_fetch_assoc($l_qry);

    if ($row === false) {
        die("Fetching result failed");
    }

    // Get the total principal
    $l_prin = $row['c_total_principal'];

    // Calculate values based on conditions
    $total_dp = $net_tcp * ($down_per / 100);
    if ($total_dp == 0) {
        $l_val = 80;
        $l_get_comm = $l_amount * ($l_val / 100);
    } else {
        if ($l_prin >= $total_dp) {
            $l_val = 100.0;
            $l_get_comm = $l_amount * ($l_val / 100);
        }
    }

    // Query to get previous commission data
    // Prepare the query with a parameter placeholder
    $sql666 = "SELECT c_due_comm, c_commission_amount, c_commission_count FROM t_new_commission_log WHERE c_account_no = $1 ORDER BY c_commission_count DESC LIMIT 1";
    $stmt = pg_prepare($conn, "", $sql666);
    $l_qry = pg_execute($conn, "", [$acc_no]);

    if ($l_qry === false) {
        die("Query execution failed: " . pg_last_error());
    }

    $row2 = pg_fetch_assoc($l_qry);
    if ($row2 === false) {
        // No rows found, handle this case gracefully
        $l_prev_comm = 0.0;
        $l_prev_amt = 0.0;
        $l_comm_count = 0;
    } else {
        $l_prev_comm = isset($row2['c_due_comm']) ? $row2['c_due_comm'] : 0.0;
        $l_prev_amt = isset($row2['c_commission_amount']) ? $row2['c_commission_amount'] : 0.0;
        $l_comm_count = isset($row2['c_commission_count']) ? $row2['c_commission_count'] : 0;
    }

    // Store data in an array
    $l_data = [$l_val, $l_get_comm, $total_dp, $l_prev_comm, $l_prev_amt, $l_comm_count];

    // Append data to the result array
    $l_list[] = $l_data;

    // Return the result array
    return $l_list;
}

?>


<?php include('nav.php'); ?>
<div class="card card-outline">
    <div class="card-header">
        <h5 class=""><b><i> New Commissions Voucher</i></b></h5>
    </div>
    <div class="card-body">
    <form action="" id="filter">
        <div class="row align-items-end">
            <input type="hidden" id="page" name="page" value="commission_voucher/new_commission_voucher" class="form-control form-control-sm rounded-0">
            <div class="col-md-3 form-group">
                <input type="text" id="code_no" name="code_no" value="<?= $code_no ?>" class="form-control" placeholder="Enter Code No" maxlength="6">
            </div>
            <div class="col-md-3 form-group">
                <button class="btn btn-primary"><i class="dw dw-search"></i> Find Agent</button>
            </div>
        </div>
    </form>

    <form action="<?php base_url ?>commission_voucher/generate_pdf.php" method="post" target="_blank" id="print-commission">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Code No: </label>
                        <input type="text" class="form-control required" name="code_no" id="code_no" value="<?= isset($agent_code) ? $agent_code : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label">Status: </label>
                        <input type="text" class="form-control required" name="agent_status" id="agent_status" value="<?= isset($l_status) ? $l_status : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Position: </label>
                        <input type="text" class="form-control" name="agent_pos" id="agent_pos" value="<?= isset($l_position) ? $l_position : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Date Hired: </label>
                        <input type="text" class="form-control" name="date_hired" id="date_hired" value="<?= isset($l_date_hired) ? $l_date_hired : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Agent Name: </label>
                        <input type="text" class="form-control" name="agent_name" id="agent_name" value="<?= isset($l_agent_name) ? $l_agent_name : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Network: </label>
                        <input type="text" class="form-control" name="network" id="network" value="<?= isset($l_network) ? $l_network : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <!-- Add your fields here -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Division: </label>
                        <input type="text" class="form-control" name="division" id="division" value="<?= isset($l_division) ? $l_division : '' ?>" readonly>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="pos">Account No:</label>
                        <input type="text" class="form-control" id="acc" name="acc" value="<?= isset($acc) ? $acc : '' ?>" readonly><br><br>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">Location:</label>
                        <input type="text" class="form-control" id="loc" name="loc" value="<?= isset($loc) ? $loc : '' ?>" readonly><br><br>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="agent_name">Name:</label>
                        <input type="text" class="form-control" id="buyer_name" name="buyer__name" value="<?= isset($buyer_name) ? $buyer_name : '' ?>" readonly><br><br>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="text" class="form-control" id="amount2" name="amount2" value="<?= isset($amount) ? $amount : '' ?>" readonly><br><br>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="rate">Rate:</label>
                        <input type="text" class="form-control" id="rate2" name="rate2" value="<?= isset($rate) ? $rate : '' ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="whtax">W.H. Tax:</label>
                        <input type="text" class="form-control" id="whtax2" name="whtax2" value="<?= isset($whtax) ? $whtax : '' ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="data-table2">
                <thead>
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
                    <?php foreach ($l_agent_data2 as $row3): ?>
                        <tr onclick="displayData2('<?php echo $row3['acc']; ?>', '<?php echo $row3['loc']; ?>', '<?php echo $row3['agent_name']; ?>', '<?php echo ftom(floatval($row3['amount'])); ?>', '<?php echo $row3['rate']; ?>', '<?php echo $row3['whtax']; ?>', this)">
                            <td><?php echo $row3['acc']; ?></td>
                            <td><?php echo $row3['loc']; ?></td>
                            <td><?php echo $row3['agent_name']; ?></td>
                            <td><?php echo ftom((float)$row3['amount']); ?></td>
                            <td><?php echo $row3['rate']; ?></td>
                            <td><?php echo $row3['whtax']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>



        <script>
        $(document).ready(function() {
            $('#data-table2').DataTable();
        });
        </script>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Net TCP: </label>
                    <input type="text" class="form-control" name="net_tcp" id="net_tcp" value="<?php echo number_format(isset($l_net_tcp) ? $l_net_tcp : 0,2) ?>" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Down %: </label>
                    <input type="text" class="form-control" name="down_percent" id="down_percent" value="<?php echo isset($l_down_per) ? $l_down_per : 0 ?>" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label"># Payments: </label>
                    <input type="text" class="form-control" name="no_payment" id="no_payment" value="<?php echo isset($l_no_payment) ? $l_no_payment : 0 ?>" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Reservation: </label>
                    <input type="text" class="form-control" name="reservation" id="reservation" value="<?php echo number_format(isset($l_reservation) ? $l_reservation : 0) ?>" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Net DP: </label>
                    <input type="text" class="form-control" name="net_dp" id="net_dp" value="<?php echo number_format(isset($l_net_dp) ? $l_net_dp : 0,2) ?>" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Due Commission: </label>
                    <input type="text" class="form-control" name="due_commission" id="due_commission" value="<?php echo isset($commission_percentage) ? $commission_percentage : 0 ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Cash Advance: </label>
                    <input type="text" class="form-control" name="cash_advance" id="cash_advance" value="">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Others: </label>
                    <input type="text" class="form-control" name="others" id="others" value="">
                </div>
            </div>
            <div class="col-md-4">
                <button id="generateButton" class="btn btn-primary">Print Commission</button>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="remarks">Remarks:</label>
                    <textarea id="remarks" class="form-control" name="remarks" rows="20" cols="150"><?php echo isset($l_remarks) ? $l_remarks : '' ?></textarea>
                </div>
            </div>
        </div>
    </form>

    </div>
</div>

<script> 
    let currentRow2 = null;

    function displayData2(acc, loc, buyer_name, amount, rate, whtax, row) {
        document.getElementById('acc').value = acc;
        document.getElementById('loc').value = loc;
        document.getElementById('buyer_name').value = buyer_name;
        document.getElementById('amount2').value = amount;
        document.getElementById('rate2').value = rate;
        document.getElementById('whtax2').value = whtax;

        if (currentRow2) {
            currentRow2.classList.remove('highlight');
        }
        row.classList.add('highlight');
        currentRow2 = row;
    }

    // Use event delegation to handle row clicks within the DataTable
    document.addEventListener('DOMContentLoaded', function() {
        const table = $('#data-table2').DataTable();

        $('#data-table2 tbody').on('click', 'tr', function() {
            const data = table.row(this).data();
            // Assuming data has the format [acc, loc, buyer_name, amount, rate, whtax]
            displayData2(data[0], data[1], data[2], data[3], data[4], data[5], this);
        });
    });
</script>