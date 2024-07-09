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

<?php include('nav.php'); ?>

    <div class="container mt-5" style="margin-bottom:50px;">
    <div class="card mt-3">
        <h2 class="text-blue h4">New Commission Voucher</h2>
    <hr>
    <div class="card-body">
        <div class="container-fluid">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="data-table" style="text-align:center;width:100%;">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Agent Code</th>
                            <th>Agent Name</th>
                            <th>Network</th>
                            <th>Division</th>
                            <th>Print Date</th>
                            <th>Total Commission</th>
                            <th>Avg Rate</th>
                            <th>Commission Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                       
                       

                        // Check connection
                        if (!$cnx) {
                            die("Connection failed: " . pg_last_error());
                        }

                        // SQL query
                        $sql = "SELECT t_agents.c_code,
                                    t_new_commission_log.c_print_date,
                                    t_agents.c_first_name || ' ' || t_agents.c_last_name AS agent_name,
                                    t_agents.c_network AS network,
                                    t_agents.c_division AS division,
                                    SUM(t_new_commission_log.c_commission_amount) AS total_amount,
                                    AVG(t_new_commission_log.c_rate) AS avg_rate,
                                    COUNT(*) AS commission_count
                                FROM t_agents
                                RIGHT JOIN t_new_commission_log ON t_agents.c_code = t_new_commission_log.c_code
                                LEFT JOIN t_buyers_account ON t_new_commission_log.c_account_no = t_buyers_account.c_account_no
                                WHERE t_new_commission_log.c_print_date = '2021-05-26'
                                GROUP BY t_agents.c_code, 
                                        t_new_commission_log.c_print_date, 
                                        t_agents.c_first_name, 
                                        t_agents.c_last_name, 
                                        t_agents.c_network, 
                                        t_agents.c_division
                                ORDER BY t_agents.c_code, 
                                        t_new_commission_log.c_print_date;
                                ";

                        $result = pg_query($cnx, $sql);

                        if ($result && pg_num_rows($result) > 0) {
                            $i = 1;
                            while ($row = pg_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><?php echo $row['c_code']; ?></td>
                                    <td><?php echo $row['agent_name']; ?></td>
                                    <td><?php echo $row['network']; ?></td>
                                    <td><?php echo $row['division']; ?></td>
                                    <td><?php echo $row['c_print_date']; ?></td>
                                    <td><?php echo ftom($row['total_amount']); ?></td>
                                    <td><?php echo ftom($row['avg_rate']); ?></td>
                                    <td><?php echo $row['commission_count']; ?></td>
                                    <td align="center">
										<button><a class="view_data" href="javascript:void(0)" data-id ="<?php echo $row['c_code'] ?>" data-print-date ="<?php echo $row['c_print_date'] ?>" data-agent-name="<?php echo $row['agent_name'] ?>">View</a>
                                        </button>
										<button><a class="print_data" href="javascript:void(0)" data-id="<?php echo $row['c_code'] ?>">Print</a>
                                        </button>
								</td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='8'>No results found</td></tr>";
                        }

                        // Close connection
                        pg_close($cnx);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    #uni_modal_right .modal-content {
        font-size: 14px; /* Adjust the font size as needed */
    }

    #uni_modal_right .table td,
    #uni_modal_right .table th {
        vertical-align: middle; /* Ensure content is vertically centered */
    }
</style>
<script>
  $(document).ready(function() {
	$('#data-table').DataTable();
	});
	
    $('.view_data').click(function(){
		uni_modal_right("Commission Details","commission_voucher/agent_commission.php?id="+$(this).attr('data-id')+"&print_date="+$(this).attr('data-print-date')+"&agent_name="+$(this).attr('data-agent-name'))
	})
	$('.print_data').click(function(){
    _conf("Are you sure you want to delete this permanently?", "delete_agent", [$(this).attr('data-id')])
	})

	function delete_agent($id){
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Commission_Master.php?f=delete_agent",
			method: "POST",
			data: {id: $id},
			dataType: "json",
			error: function(err) {
				console.log(err);
				alert_toast("An error occurred.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp === 'object' && resp.status === 'success') {
					alert_toast(resp.msg, 'success');
					setTimeout(function() {
						location.reload();
					}, 2000);
				} else {
					alert_toast(resp.msg || "An error occurred.", 'error'); // Display default error message if msg is undefined
					end_loader();
				}
			}
		});
	}

</script>
