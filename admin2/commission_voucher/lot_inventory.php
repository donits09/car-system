<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.main_menu{
		float:left;
		width:227px;
		height:40px;
		line-height:40px;
		text-align:center;
		color:black!important;
		border-right:solid 3px white;
	}
	.main_menu:hover{
		border-bottom: solid 2px blue;
		background-color:#E8E8E8;
	}
    #container {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color:transparent;
    }
	#lot-link{
		border-bottom: solid 2px blue;
        background-color:#E8E8E8;
	}
    .nav-inventory{
        background-color:#007bff;
        color:white!important;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 1px 1px 0 rgba(0, 0, 0, 0.1);
    }
    .nav-inventory:hover{
        background-color:#007bff!important;
        color:white!important;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2), 0 1px 1px 0 rgba(0, 0, 0, 0.1)!important;
    }
    .table-container {
  		overflow-x: auto;
  		max-width: 100%;
	}
	table {
		table-layout: fixed;
		width: 100%;
	}
</style>

<div class="card" id="container">
    <div class="navbar-menu">
		<a href="" class="main_menu" id="lot-link" style="border-left:solid 3px white;"><i class="nav-icon fas fa-square"></i>&nbsp;&nbsp;&nbsp;Lot Inventory</a>
		<a href="" class="main_menu" id="project-link"><i class="nav-icon fas fa-map"></i>&nbsp;&nbsp;&nbsp;Project List</a>
	</div>
</div>

<div class="card card-outline rounded-0 card-maroon">
		<div class="card-header">
			<h5 class="card-title"><b><i>Lot Inventory</b></i></h5>
			<div class="card-tools">
				<a class="btn btn-primary btn-flat border-primary new_lot" href="javascript:void(0)" style="font-size:14px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
			</div>
		</div>
		<div class="card-body">
            <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped" id="data-table" style="text-align:center;width:100%;">
                    <thead>
                        <tr>
                        <th>Lot ID</th>
                        <th>Phase</th>
                        <th>Block</th>
                        <th>Lot</th>
                        <th>Lot Area</th>
                        <th>Price SQM</th>
                        <th>Status</th>
				        <th>Actions</th>
                       
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $i = 1;
                        $qry = "SELECT c_lid, c_acronym, c_block, c_lot, c_lot_area, c_price_sqm, i.c_status
                        FROM t_lots i 
                        JOIN t_projects c 
                        ON i.c_site = c.c_code
                        WHERE i.c_site = c.c_code  
                        ORDER BY c.c_acronym, i.c_block, i.c_lot";
                        $result = odbc_exec($conn, $qry);
                        while($row = odbc_fetch_array($result)):
                                
                    ?>
                        <tr>
                        <td><?php echo $row["c_lid"] ?></td>
                        <td><?php echo $row["c_acronym"] ?></td>
                        <td><?php echo $row["c_block"] ?></td>
                        <td><?php echo $row["c_lot"] ?></td>
                        <td><?php echo $row["c_lot_area"] ?></td>
                        <td><?php echo number_format($row["c_price_sqm"],2) ?></td>
                        <?php if($row['c_status'] == "Available"): ?>
                           <td class="text-center"><span class=" badge badge-primary">Available</span></td>
                        <?php elseif($row['c_status'] == "Reserved"): ?>
                           <td class="text-center"><span class=" badge badge-success">Reserved</span></td>
                        <?php elseif($row['c_status'] == "Pre-Reserved"): ?>
                           <td class="text-center"><span class=" badge badge-info">Pre-Reserved</span></td>
                        <?php elseif($row['c_status'] == "On Hold"): ?>
                          <td class="text-center"><span class="badge badge-secondary">On Hold</span></td>
                        <?php elseif($row['c_status'] == "Packaged"): ?>
                            <td class="text-center"><span class=" badge badge-warning">Packaged</span></td>
                        <?php elseif($row['c_status'] == "Sold"): ?>
                           <td class="text-center"><span class=" badge badge-danger">Sold</span></td>
                        <?php endif; ?>
                        <?php if ($usertype == 'IT Admin'): ?>
                        <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item edit-lot" data-lot-id="<?php echo $row['c_lid'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item delete-lot" data-lot-id="<?php echo $row['c_lid'] ?>"><span class="fa fa-ban text-danger"></span> Cancel</a>
                                </div>
                        </td>
                        <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                    </tbody></table>
	            </div>                
            </div>
	</div>
<script>
    $(document).ready(function(){
		$('.table').dataTable();
	})
  
    $('.new_lot').click(function(){
        uni_modal("<i class='fa fa-plus'></i>&nbsp;&nbsp;New Lot",'inventory/manage_lot.php',"mid-large")
    })

    $('.edit-lot').click(function(){
        uni_modal("<i class='fa fa-paint-brush'></i>&nbsp;&nbsp;Edit Lot",'inventory/manage_lot.php?id='+$(this).attr('data-lot-id'),"mid-large")
    })


    $('.delete-lot').click(function(){
        _conf("Are you sure you want to delete this lot information?","delete_lot",[$(this).attr('data-lot-id')])
    }) 

    function delete_lot($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_lot",
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
                }else{
                    alert_toast("An error occured.",'error');
                    end_loader();
                }
            }
        })
    }
	


</script>