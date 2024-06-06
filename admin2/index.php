<?php
session_start();
require_once('../config.php');
include('../inc/navbar.php');    
include('../inc/header.php');     


?>

<body>
	<?php $page = isset($_GET['page']) ? $_GET['page'] : 'admin_dash';  ?>
		<section class="content">
				<?php 
				if(!file_exists($page.".php") && !is_dir($page)){
					include '404.html';
				}else{
					if(is_dir($page))
					include $page.'/index.php';
					else
					include $page.'.php';

				}
				?>
			
		</section>
		<div class="modal fade" id="confirm_modal" role='dialog'>
			<div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
			<div class="modal-content">
				<div class="modal-header">
				<h5 class="modal-title">Confirmation</h5>
			</div>
			<div class="modal-body">
				<div id="delete_content"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-flat" id='confirm' onclick="">Continue</button>
				<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
			</div>
			</div>
			</div>
		</div>
	

		<div class="modal fade rounded-0" id="uni_modal" role="dialog">
			<div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
			<div class="modal-content rounded-0">
				<div class="modal-header rounded-0">
				<h5 class="modal-title"></h5>
				</div>
				<div class="modal-body rounded-0">
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-flat" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
				<button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cancel</button>
				</div>
			</div>
			</div>
		</div>

		
	</div>
	<?php include('includes/footer.php'); ?>
	<!-- js -->
</body>
</html>