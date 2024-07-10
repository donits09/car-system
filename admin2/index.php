<?php
session_start();
require_once('../config.php');
include('../inc/navbar.php');    
include('../inc/header.php');   
include('../inc/common.php');     
// ?>
<style>
/* Ensure CSS specificity and loading order */
#uni_modal_right .modal-dialog {
    max-width: 1200px !important; /* Increase specificity and use !important if needed */
}

#uni_modal_right .modal-content {
    border-radius: 10px;
    border: 2px solid #ccc;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

#uni_modal_right .modal-header {
    background-color: #0038a5;
    color: #fff;
    border-bottom: none;
    border-radius: 5px 5px 0 0;
}

#uni_modal_right .modal-title {
    font-size: 1.5rem;
    font-weight: bold;
}

#uni_modal_right .modal-body {
    padding: 10px;
}

#uni_modal_right .modal-footer {
    border-top: none;
    border-radius: 0 0 10px 10px;
}

#uni_modal_right #submit {
    background-color: #0038a5;
    color: #fff;
    border: 1px solid #0038a5;
    border-radius: 5px;
}

#uni_modal_right .btn-secondary {
    background-color: #6c757d;
    color: #fff;
    border: 1px solid #6c757d;
    border-radius: 5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #uni_modal_right .modal-dialog {
        max-width: 90%;
    }

}

</style>

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
			<button type="button" class="btn btn-secondary btn-flat" data-bs-dismiss="modal">Close</button>
		</div>
		</div>
		</div>
	</div>
	
	<div class="modal fade" id="uni_modal_right" role='dialog'>
		<div class="modal-dialog modal-full-screen  modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title"></h5>
			<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
			<span class="fa fa-times"></span>
			</button>
		</div>
		<div class="modal-body">
		</div>
		</div>
		</div>
	</div>
	


	<div class="modal fade rounded-0" id="uni_modal" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog rounded-0" role="document">
		<div class="modal-content rounded-0">
			<div class="modal-header rounded-0">
			<h5 class="modal-title"></h5>
			</div>
			<div class="modal-body rounded-0">
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-primary btn-flat" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
			<button type="button" class="btn btn-secondary btn-flat" data-bs-dismiss="modal">Cancel</button>
			</div>
		</div>
		</div>
	</div>
</div>
<?php include('includes/footer.php'); ?>
<!-- js -->
</body>
</html>

