
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
            <h2 class="text-blue h4">Agents</h2>
		
            <hr>
			<div class="pd-20">
            <a id="create_new" class="btn btn-flat btn-primary" href="javascript:void(0)" >
                <span class="fa fa-edit"></span> Create New Agent
            </a>
			
		<div class="card-body">
            <div class="container-fluid">
            <div class="container-fluid">
                	<table class="table table-bordered table-stripped" id="data-table" style="text-align:center;width:100%;">
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
							$sql = "SELECT * FROM t_agents order by c_hire_date DESC limit 10";
							$comm_result = odbc_exec($conn, $sql);
                            while ($row = odbc_fetch_array($comm_result)): 
							?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td class=""><?php echo $row['c_code'] ?></td>
								<td class=""><?php echo $row['c_last_name'] . ', '. $row['c_first_name'] . ' '. $row['c_middle_initial']  ?></td>
								<td class=""><?php echo $row['c_position'] ?></td>
								<td align="center">
									
										<button><a class="edit_data" href="javascript:void(0)" data-id ="<?php echo $row['c_code'] ?>">Edit</a>
                                        </button>
										<button><a class="delete_data" href="javascript:void(0)" data-id="<?php echo $row['c_code'] ?>">Delete</a>
                                        </button>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>                
			</div>
	</div>

<script>
  $(document).ready(function() {
	$('#data-table').DataTable();
	});
	

	$('#create_new').click(function(){
		uni_modal("Add New Agent","commission_voucher/agent.php",'')
	});

    $('.edit_data').click(function(){
		uni_modal("Update Agent Details","commission_voucher/agent.php?id="+$(this).attr('data-id'),'')
	})
	$('.delete_data').click(function(){
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