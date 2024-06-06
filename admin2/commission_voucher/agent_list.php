
<?php 
session_start();
require_once('../../config.php');
include('../../inc/navbar.php');    
include('../../inc/header.php');   
if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != 1) {
    require_once('../logout.php');
    exit();
}

if (isset($_SESSION['username'])) {
    echo "Username: " . $_SESSION['username'];
}
?>
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
<div class="card card-outline rounded-0 card-maroon">
		<div class="card-header">
			<h5 class="card-title"><b><i>List of Agents</b></i></h5>
			<div class="card-tools">
				<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary" style="font-size:14px;"><span class="fas fa-plus"></span>&nbsp;&nbsp;Add New</a>
			</div>
		</div>
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
									
										<button><a class="edit_data" href="javascript:void(0)" data-id ="<?php echo $row['c_code'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                        </button>
										<button><a class="delete_data" href="javascript:void(0)" data-id="<?php echo $row['c_code'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
    $(document).ready(function(){
		$('.table').dataTable();
	})
	$('#create_new').click(function(){
		uni_modal("Add New Agent","commission_voucher/new_agent.php",'mid-large')
	})
	$('.edit_data').click(function(){
		uni_modal("Update Agent Details","commission_voucher/new_agent.php?id="+$(this).attr('id'),'mid-large')
	})
	$('.delete_data').click(function(){
		_conf("Are you sure you want to delete this permanently?","delete_agent",[$(this).attr('data-id')])
	})
	$('.table td, .table th').addClass('py-1 px-2 align-middle')
	$('.table').dataTable({
		columnDefs: [
			{ orderable: false, targets: 5 }
		],
	});

    function delete_account($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_account",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
					console.log('dsdsds');
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>