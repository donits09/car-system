
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>

<div class="card card-outline rounded-0 card-maroon">
		<div class="card-header">
			<h5 class="card-title"><b><i>User List</b></i></h5>
			<div class="card-tools">
				<a class="btn btn-flat btn-default bg-primary" href="<?php echo base_url.'admin/?page=user/manage_user' ?>" style="font-size:14px;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add New</a>
			</div>
		</div>
		<div class="card-body">
            <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped" id="data-table" style="text-align:center;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID No.</th>
                            <th>Last Name</th>
                            <th>First Name </th>	
                            <th>User type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $i = 1;
                            $qry = $conn->query("SELECT * FROM users;");
                            while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                        <td> <?php echo $i++ ?></td>
                        <td><?php echo $row['user_code'] ?></td>
                        <td><?php echo $row["lastname"] ?></td>
                        <td><?php echo $row["firstname"] ?></td>
                        <td>
                        <?php if($row['type'] == 1): ?>
                           <span class="badge badge-primary">Admin</span>
                        <?php elseif($row['type'] == 2): ?>
                            <span class="badge badge-success">Chief Officer</span>
                        <?php elseif($row['type'] == 3): ?>
                            <span class="badge badge-secondary">Manager</span>
                        <?php elseif($row['type'] == 4): ?>
                            <span class="badge badge-info">Supervisor</span>
                        <?php elseif($row['type'] == 5): ?>
                            <span class="badge badge-danger">Employee</span>
                        <?php endif; ?>
                        </td>
                        <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item " href="./?page=user/manage_user&id=<?php echo $row['user_code'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['user_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        	_this.siblings('.custom-file-label').html(input.files[0].name)
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
    $(document).ready(function(){
		$('.table').dataTable();
	})
	$(document).ready(function(){
		 $('.summernote').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })
	})

    $('.delete_data').click(function(){
            _conf("Are you sure you want to delete this user information?","delete_user",[$(this).attr('data-id')])
    })

    function delete_user($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_user",
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

    $(document).ready(function(){
		$('.table').dataTable();
	})
</script>



