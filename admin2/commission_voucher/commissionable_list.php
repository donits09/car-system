<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

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
							$sql = "SELECT t_commission.c_code, t_commission.c_amount, t_commission.c_account_no, t_commission.c_rate, t_buyers_account.c_net_tcp from t_agents left join t_commission on t_agents.c_code = t_commission.c_code left join t_buyers_account on t_commission.c_account_no = t_buyers_account.c_account_no where t_commission.c_date_of_sale >= '2021-01-22' order by t_commission.c_date_of_sale";
							$comm_result = odbc_exec($conn, $sql);
							while ($row = odbc_fetch_array($comm_result)): 
							?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td class=""><?php echo $row['c_code'] ?></td>
								<td class=""><?php echo $row['c_account_no'] ?></td>
								<td class=""><?php echo $row['c_amount'] ?></td>
								<td align="center">
									<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
										Action
									<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<a class="dropdown-item view_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
									<div class="dropdown-divider"></div>
										<a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
									<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-name="<?php echo $row['name'] ?>"><span class="fa fa-ban text-danger"></span> Cancel</a>
									</div>
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
		uni_modal("Add New Account","accounts/manage_account.php",'mid-large')
	})
	$('.edit_data').click(function(){
		uni_modal("Update Account Details","accounts/manage_account.php?id="+$(this).attr('data-id'),'mid-large')
	})
	$('.delete_data').click(function(){
		_conf("Are you sure you want to delete this from Chart of Accounts permanently?","delete_account",[$(this).attr('data-id')])
	})
	$('.view_data').click(function(){
		uni_modal("Account Details","accounts/view_account.php?id="+$(this).attr('data-id'),'mid-large')
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