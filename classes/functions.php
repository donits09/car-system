<?php

function getProject() {
 
   // Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
		die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
	}

    // Get all the project
    $sql = "SELECT * FROM t_projects order by c_acronym";
	$results = $mysqli->query($sql);
    //$all_categories = mysqli_query($con,$sql);
	if($results) {
		echo '<select name="prod_code" id= "prod_code" class="form-control">';
		while($row = $results->fetch_assoc()) {

	/* 		print '<option value="'.$row['c_code'].'">'.$row['c_acronym'].'</option>'; */
			print '<option value="'.$row['c_code'].'" >'.$row['c_acronym'].'</option>';
			
		}
		echo '</select>';

	} else {
		echo '<select name = "prod_code" id= "prod_code" class="form-control">';
		echo '<option value="">No Project</option>';
		echo '</select>';
		
	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
	

}
function getHouseModel() {
 
	// Connect to the database
	 $mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
 
	 // output any connection error
	 if ($mysqli->connect_error) {
		 die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
	 }
 
	 // Get all the project
	 $sql = "SELECT * FROM t_model_house order by c_code";
	 $results = $mysqli->query($sql);
	 //$all_categories = mysqli_query($con,$sql);
	 if($results) {
		 echo '<select name="house_model" id= "house_model" class="form-control">';
		 print  '<option  value="None" selected="selected">No House</option>';
		 while($row = $results->fetch_assoc()) {
			
			 print '<option value="'.$row['c_model'].'">'.$row['c_model'].'</option>';
			 
		 }
	
 
	 } else {
		 echo '<select name = "house_model" id= "house_model" class="form-control">';
		 echo '<option value="">No House Model</option>';
	
		 
	 }
 
	 // Frees the memory associated with a result
	 $results->free();
 
	 // close connection 
	 $mysqli->close();
	 
 
 }
 
function getProjectSite() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM t_projects ORDER BY c_code ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

					<th>Code</th>
					<th>Name</th>
					<th>Acronym</th>
					<th>Address</th>
					<th>Province</th>
					<th>Zip</th>
					<th>Rate</th>
					<th>Reservation</th>
					<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["c_code"].'</td>
				    <td>'.$row["c_name"].'</td>
				    <td>'.$row["c_acronym"].'</td>
					<td>'.$row["c_address"].'</td>
				    <td>'.$row["c_province"].'</td>
				    <td>'.$row["c_zip"].'</td>
					<td>'.$row["c_rate"].'</td>
				    <td>'.number_format($row["c_reservation"],2).'</td>
				    <td class="actions"><a data-project-id="'.$row['c_code'].'" class="btn btn-primary btn-xs edit-project"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a data-project-id="'.$row['c_code'].'" class="btn btn-danger btn-xs delete-project"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no project sites to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}

function getHouse() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM t_model_house ORDER BY c_code ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>Code</th>
				<th>Model</th>
				<th>Acronym</th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["c_code"].'</td>
				    <td>'.$row["c_model"].'</td>
				    <td>'.$row["c_acronym"].'</td>
				    <td class="actions"><a data-house-id="'.$row['c_code'].'" class="btn btn-primary btn-xs edit-house"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a data-house-id="'.$row['c_code'].'" class="btn btn-danger btn-xs delete-house"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no project sites to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}

// get csr list
function getCSRs() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
		die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	

 	if(isset($_POST["filtercsr"])){
		$filter = $_POST["filtercsr"];
		$query = "SELECT * FROM t_csr_view  where c_csr_status = '$filter' order by c_csr_no desc";
	}else{
		//$query = "SELECT * FROM t_csr_view  where (coo_approval != 1 and coo_approval != 2) ";
		$query = "SELECT * FROM t_csr_view order by c_csr_no desc";
	} 

	$results = $mysqli->query($query);
	$no = 1;
	if($results) {


		print '<table class="display table table-striped table-hover table-bordered" id="" cellspacing="0"><thead><tr>

				<th> No.</th>	
				<th> Prepared by </th>	
				<th> Location </th>		
				<th>Buyers Name</th>
				<th>Net TCP</th>
				<th>Prepared Date</th>
				<th>Status</th>
				<th>Approval Status</th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {
			$timeStamp = date( "m/d/Y", strtotime($row['c_date_updated']));
			print '
				<tr>
					<td>'.$no++.'</td>
					<td class="text-center">'.$row["c_created_by"].'</td>
					<td>'.$row["c_acronym"].' Block '.$row["c_block"].' Lot '.$row["c_lot"].' </td>
					<td>'.$row["last_name"].', '.$row["first_name"].' '.$row["middle_name"].' </td>
					<td class="text-right">'.number_format($row["c_net_tcp"], 2).'</td>
					<td class="text-center">'.$timeStamp.'</td>
					
				';
			
				if($row['c_verify'] == 0){
					print '<td class="text-center"><span class="label label-warning">Pending</span></td>';
				}elseif($row['c_verify'] == 1){
					print '<td class="text-center"><span class="label label-info">SOS Verified</span></td>';
				}elseif($row['c_verify'] == 2){
					print '<td class="text-center"><span class="label label-danger">SOS Void</span></td>';
				}

				if($row['coo_approval'] == 0){
					print '<td class="text-center"><span class="label label-warning">Pending</span></td>';
				}elseif($row['coo_approval'] == 3){
					print '<td class="text-center"><span class="label label-danger">Disapproved</span></td>';
				
				}elseif($row['coo_approval'] == 1){
					print '<td class="text-center"><span class="label label-success">Approved</span></td>';
				}
				elseif($row['coo_approval'] == 2){
					print '<td class="text-center"><span class="label label-default">Cancelled</span></td>';
				}
				print '
				<td class="actions"><a href="?page=csr-view&id='.$row["c_csr_no"].'" class="btn btn-info btn-xs">
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span></a> 

				<a data-csr-id="'.$row['c_csr_no'].'" class="btn btn-danger btn-xs delete-csr">
				<span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
				
			    </tr>
			'; 
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no invoices to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}

function get_acronym($phase) {
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}
	


	// the query
	$query = "SELECT * from t_projects where c_code = $phase ";
	
	$results = $mysqli->query($query);

	if($results) {

		while($row = $results->fetch_assoc()) {

			print ' '.$row["c_acronym"].'';

		}
	}else{
			print "None";

		}


	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}

function popLotsList() {

// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}
	
	// the query
	$query = "SELECT c_lid, c.c_acronym, h.c_house_lid, h.c_house_model, h.c_floor_area, 
	h.c_h_price_sqm , i.c_block, i.c_lot, i.c_status, i.c_lot_area, i.c_price_sqm 
	FROM t_lots i 
	LEFT JOIN t_projects c 
	ON i.c_site = c.c_code
    LEFT JOIN t_house h  
	ON i.c_house_lid = h.c_house_lid
	WHERE i.c_site = c.c_code  and  (i.c_status = 'Available' )
	ORDER BY c.c_acronym, i.c_block, i.c_lot";

	//echo $query;
	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table-lot"><thead><tr>
				
				<th>Lot ID</th>
				<th>Project</th>
				<th>Block</th>
				<th>Lot</th>
				<th>Status </th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {
		
		    print '
			    <tr>
					<td>'.$row["c_lid"].'</td>
					<td>'.$row["c_acronym"].'</td>
				    <td>'.$row["c_block"].'</td>
				    <td>'.$row["c_lot"].'</td>
					<td>'.$row["c_status"].'</td>
				    <td class="actions"><a href="#" class="btn btn-primary btn-xs lot-select" data-lot-lid="'.$row['c_lid'].'" data-house-lid="'.$row['c_house_lid'].'" data-floor-area="'.$row['c_floor_area'].'" data-house-price="'.$row['c_h_price_sqm'].'" data-house-model="'.$row['c_house_model'].'" data-lot-site="'.$row['c_acronym'].'" data-lot-block="'.$row['c_block'].'" data-lot-lot="'.$row['c_lot'].'" data-lot-lot-area="'.$row['c_lot_area'].'" data-lot-per-sqm="'.$row['c_price_sqm'].'">Select</a></td>
			   
				</tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no lots to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}


	function popCustomersList() {

	
		// Connect to the database
		$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
	
		// output any connection error
		if ($mysqli->connect_error) {
			die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
		}
	
		// the query
		$query = "SELECT * FROM t_buyer_info ORDER BY last_name ASC";
	
		//echo $query;
		// mysqli select query
		$results = $mysqli->query($query);
	
		if($results) {
	
			print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>
	
					<th>Last Name</th>
					<th>First Name</th>
					<th>Middle Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th class="actions">Actions</th>
	
				  </tr></thead><tbody>';
	
			while($row = $results->fetch_assoc()) {
	
				print '
					<tr>
						<td>'.$row["last_name"].'</td>
						<td>'.$row["first_name"].'</td>
						<td>'.$row["middle_name"].'</td>
						<td>'.$row["email"].'</td>
						<td>'.$row["contact_no"].'</td>
						<td class="actions"><a href="#" class="btn btn-primary btn-xs customer-select"  data-customer-civil="'.$row['civil_status'].'" data-customer-gender="'.$row['gender'].'" data-customer-age="'.$row['age'].'" data-customer-birthday="'.$row['birthdate'].'" data-customer-viber="'.$row['viber'].'" data-customer-address-1="'.$row['address'].'" data-customer-zip-code="'.$row['zip_code'].'"  data-customer-address-abroad="'.$row['address_abroad'].'" data-customer-lname="'.$row['last_name'].'" data-customer-fname="'.$row['first_name'].'" data-customer-mname="'.$row['middle_name'].'" data-customer-sname="'.$row['suffix_name'].'" data-customer-email="'.$row['email'].'" data-customer-phone="'.$row['contact_no'].'" data-customer-ctzn="'.$row['citizenship'].'">Select</a></td>
					</tr>
				';
			}
	
			print '</tr></tbody></table>';
	
		} else {
	
			echo "<p>There are no clients to display.</p>";
	
		}
	
		// Frees the memory associated with a result
		$results->free();
	
		// close connection 
		$mysqli->close();
	
	
	
	}

function getLots() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT c_lid, c_acronym, c_block, c_lot, c_lot_area, c_price_sqm, i.c_status
			FROM t_lots i 
			JOIN t_projects c 
			ON i.c_site = c.c_code
			WHERE i.c_site = c.c_code  
			ORDER BY c.c_acronym, i.c_block, i.c_lot";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table" ><thead><tr>

				<th>Lot ID#</th>
				<th>Phase</th>
				<th>Block</th>
				<th>Lot</th>
				<th>Lot Area</th>
				<th>Price SQM</th>
				<th>Status</th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["c_lid"].'</td>
				    <td>'.$row["c_acronym"].'</td>
				    <td>'.$row["c_block"].'</td>
					<td>'.$row["c_lot"].'</td>
					<td>'.$row["c_lot_area"].'</td>
					<td>'.number_format($row["c_price_sqm"],2).'</td>';
					if($row['c_status'] == "Available"){
						print '<td class="text-center"><span class="label label-primary">Available</span></td>';
					}elseif($row['c_status'] == "Reserved"){
						print '<td class="text-center"><span class="label label-success">Reserved</span></td>';
					}elseif($row['c_status'] == "Pre-Reserved"){
						print '<td class="text-center"><span class="label label-info">Pre-Reserved</span></td>';
					}elseif($row['c_status'] == "On Hold"){
						print '<td class="text-center"><span class="label label-default">On Hold</span></td>';
					}elseif($row['c_status'] == "Packaged"){
						print '<td class="text-center"><span class="label label-warning">Packaged</span></td>';
					}elseif($row['c_status'] == "Sold"){
						print '<td class="text-center"><span class="label label-danger">Sold</span></td>';
					}
					print '
				    <td class="actions"><a data-lot-id="'.$row['c_lid'].'" class="btn btn-primary btn-xs edit-lot"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a data-lot-id="'.$row['c_lid'].'" class="btn btn-danger btn-xs delete-lot"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no lots to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}

// get user list
function getUsers() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM users ORDER BY username ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>User ID</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Middle Name</th>
				<th>Username</th>
				<th>User Type</th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
			    	<td>'.$row['user_id'].'</td>
					<td>'.$row["last_name"].'</td>
				    <td>'.$row["first_name"].'</td>
				    <td>'.$row["middle_name"].'</td>
					<td>'.$row["username"].'</td>
					<td>'.$row["user_type"].'</td>
				   	<td class="actions"><a data-id="'.$row['user_id'].'" class="btn btn-primary btn-xs edit-user"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> <a data-id="'.$row['user_id'].'" class="btn btn-danger btn-xs delete-user"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			   
				</tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no users to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}
function popHouseModel() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM t_model_house ORDER BY c_code ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {
		echo '<select class="form-control model-select">';
		while($row = $results->fetch_assoc()) {

		    print '<option value="'.$row["c_model"].'">'.$row["c_model"].' </option>';
		}
		echo '</select>';

	} else {

		echo "<p>There are no agents, please add an agents.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}

function popAgentsList() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM t_agents ORDER BY c_last_name ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {
		echo '<select class="form-control item-select">';
		while($row = $results->fetch_assoc()) {

		    print '<option value="'.$row['c_code'].' - '.$row["c_position"].' ">'.$row["c_last_name"].' , '.$row["c_first_name"].' </option>';
		}
		echo '</select>';

	} else {

		echo "<p>There are no agents, please add an agents.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}


function getAgents() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM t_agents ORDER BY c_last_name ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>Code</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Position</th>
				<th>Status</th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
			    	<td>'.$row['c_code'].'</td>
					<td>'.$row["c_last_name"].'</td>
				    <td>'.$row["c_first_name"].'</td>
				    <td>'.$row["c_position"].'</td>
					<td>'.$row["c_status"].'</td>
				    <td class="actions">
					<a href="?page=agent-edit&id='.$row["c_code"].'" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
					<a data-agent-id="'.$row['c_code'].'" class="btn btn-danger btn-xs delete-agent"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no users to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}


// get user list
function getCustomers() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
	    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
	$query = "SELECT * FROM t_buyer_info ORDER BY last_name ASC";

	// mysqli select query
	$results = $mysqli->query($query);

	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table"><thead><tr>

				<th>Name</th>
				<th>Email</th>
				<th>Phone</th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {

		    print '
			    <tr>
					<td>'.$row["last_name"].' '.$row["suffix_name"].', '.$row["first_name"].'</td>
				    <td>'.$row["email"].'</td>
				    <td>'.$row["contact_no"].'</td>
					<td class="actions"><a data-customer-id="'.$row['id'].'" class="btn btn-primary btn-xs edit-customer"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
				   <a data-customer-id="'.$row['id'].'" class="btn btn-danger btn-xs delete-customer">
					<span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
			    </tr>
		    ';
		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no customers to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();
}

// get csr list
function getRAs() {
	
	include('header.php');
	
	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
		die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
    $query = "SELECT *
		FROM t_approval_csr i inner join t_csr_view x on i.c_csr_no = x.c_csr_no
		ORDER BY c_date_approved";

	// mysqli select query
	$results = $mysqli->query($query);
	$no = 1;
	// mysqli select query
	/* 		<th>Location </th> */
	/* <td>'.$row["c_acronym"].' Block '.$row["c_block"].' Lot '.$row["c_lot"].' </td> */
	if($results) {
		print '<table class="table table-striped table-hover table-bordered" id="data-table" cellspacing="0"><thead><tr>
				<th>RA No.</th>
				<th>Location </th>
				<th>Buyer Name </th>
				<th>Approval Status</th>
				<th>Reserve Status</th>
				<th>CA Status</th>
				<th class="actions">Actions</th>
			  </tr></thead><tbody>';
		while($row = $results->fetch_assoc()) {
			$status=$row["c_csr_status"];
			$date_created=$row["c_date_created"];
			$id=$row["c_csr_no"];
			$lid = $row["c_lot_lid"];
			$exp_date=new DateTime($row["c_duration"]);
			$exp_date_str=$row["c_duration"];
			$exp_date_only=date("Y-m-d",strtotime($exp_date_str));
			//echo $exp_date_only;

			$today_date=date('Y/m/d H:i:s');
			$today_date_only=date("Y-m-d",strtotime($today_date));
			//echo $today_date_only;

			$exp=strtotime($exp_date_str);
			$td=strtotime($today_date);
			print '
				<tr>
					<td>'.$row["ra_id"].'</td>
					<td>'.$row["c_acronym"].' Block '.$row["c_block"].' Lot '.$row["c_lot"].' </td>
					<td>'.$row["last_name"].', '.$row["first_name"].' '.$row["middle_name"].' </td>

				';

				
				if(($td>$exp) && ($row['c_reserve_status'] == 0)){ 
					//$diff=$td-$exp;
					$x=$exp_date->diff(new DateTime());

					print '<td class="counter"><span class="label label-default">Expired</span></td>';
					$query = "UPDATE t_csr SET coo_approval = 2, c_active = 0 WHERE c_csr_no = '".$id."'";
					$result = mysqli_query($mysqli,$query);
					
					$query2 = "UPDATE t_approval_csr SET c_csr_status = 2 WHERE c_csr_no = '".$id."'";
					$result2 = mysqli_query($mysqli,$query2);

					$query3 = "UPDATE t_lots SET c_status = 'Available' WHERE c_lid = '".$lid."'";
					$result3 = mysqli_query($mysqli,$query3);
					
				} else if (($td>$exp) && ($row['c_reserve_status'] == 1)){
					print '<td><span class="label label-success"> COO Approved<br></span>';
					
				}else if (($td<$exp) && ($row['c_reserve_status'] == 1)){
						print '<td><span class="label label-success"> COO Approved<br></span>';
						
				}else{
					$x=$exp_date->diff(new DateTime());
					print '<td><span class="label label-success"> COO Approved <br></span>
					<span class="label label-info">'.$x->format('%h hr/s %i min/s %s sec/s remaining').'</td></span>';
				}


				if($row['c_reserve_status'] == 1){
					print '<td><span class="label label-success">Paid </span></td>';
				}
				if($row['c_reserve_status'] == 0){
					print '<td><span class="label label-warning">Unpaid</span></td>';
				}


				if($row['c_ca_status'] == 1){
					print '<td><span class="label label-success">CA Approved</span></td>';
				} elseif ($row['c_ca_status'] == 0){
					print '<td><span class="label label-warning">Pending</span></td>';
				} elseif ($row['c_ca_status'] == 2){
					print '<td><span class="label label-danger">Disapproved</span></td>';}

				else{
					print '<td><span class="label label-danger"> --- </span></td>';
				}

			
				print '
				<td class="actions"><a href="?page=ra-view&id='.$row["c_csr_no"].'" data-ra-id="'.$row['ra_id'].'" class="btn btn-primary btn-xs">View
				<span class="glyphicon glyphicon-search" aria-hidden="true"></span></a> 
				
			    </tr>
			'; 

		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no invoices to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}

// get reservations list
function getReservations() {

	// Connect to the database
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);

	// output any connection error
	if ($mysqli->connect_error) {
		die('Error : ('.$mysqli->connect_errno .') '. $mysqli->connect_error);
	}

	// the query
    $query = "SELECT *
		FROM t_reservation
		ORDER BY id";

	// mysqli select query
	$results = $mysqli->query($query);
	$no = 1;
	// mysqli select query
	if($results) {

		print '<table class="table table-striped table-hover table-bordered" id="data-table" cellspacing="0"><thead><tr>

				<th> No. </th>
				<th>RA No.</th>
				<th> Reserved Date </th>
				<th> OR No. </th>
				<th> Reservation Fee</th>
				<th class="actions">Actions</th>

			  </tr></thead><tbody>';

		while($row = $results->fetch_assoc()) {
				
			
			print '
				<tr>
					<td>'.$no++.'</th>
					<td>'.$row["ra_no"].'</td>
					<td>'.$row["c_reserve_date"].'</td>
					<td>'.$row["c_or_no"].'</td>
					<td>'.$row["c_amount_paid"].'</td>
					<td class="actions"><a href="?page=reservation-edit&id='.$row["id"].'" class="btn btn-primary btn-xs">
					<span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
				  	<a data-ra-id="'.$row['id'].'" data-csr-no="'.$row['c_csr_no'].'" class="btn btn-danger btn-xs delete-reservation">
					<span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>

			    </tr>
			'; 

		}

		print '</tr></tbody></table>';

	} else {

		echo "<p>There are no invoices to display.</p>";

	}

	// Frees the memory associated with a result
	$results->free();

	// close connection 
	$mysqli->close();

}



function popRAsList() {

	// Connect to the database
		$mysqli = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME);
	
		// output any connection error
		if ($mysqli->connect_error) {
			die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
		}


		$ras = $mysqli->query("SELECT * FROM t_approval_csr i inner join t_csr_view x on i.c_csr_no = x.c_csr_no ORDER BY c_date_approved");
		while($row=$ras->fetch_assoc()):
			$ra_id = $row["ra_id"];
			$status=$row["c_csr_status"];
			$date_created=$row["c_date_created"];
			$id=$row["c_csr_no"];
			$lid = $row["c_lot_lid"];
			$exp_date=new DateTime($row["c_duration"]);
			$exp_date_str=$row["c_duration"];
			$exp_date_only=date("Y-m-d",strtotime($exp_date_str));
			//echo $exp_date_only;

			$today_date=date('Y/m/d H:i:s');
			$today_date_only=date("Y-m-d",strtotime($today_date));
			//echo $today_date_only;

			$exp=strtotime($exp_date_str);
			$td=strtotime($today_date);		

			if(($td>$exp) && ($row['c_reserve_status'] == 0)  && ($row['c_csr_status'] == 1)){
				$update_csr = $mysqli->query("UPDATE t_csr SET coo_approval = 2, c_active = 0 WHERE c_csr_no = '".$id."'");	
				$update_app = $mysqli->query("UPDATE t_approval_csr SET c_csr_status = 2 WHERE c_csr_no = '".$id."'");
				$update_lot = $mysqli->query("UPDATE t_lots SET c_status = 'Available' WHERE c_lid = '".$lid."'");
			}

			endwhile;


		$query ="SELECT * FROM t_approval_csr i inner join t_csr_view x on i.c_csr_no = x.c_csr_no where (i.c_csr_status = 1 and (i.c_reserve_status = 0 or i.c_reserve_status = 3)) 
				ORDER BY c_date_approved";

		
		//echo $query;
		// mysqli select query
		$results = $mysqli->query($query);
	
		if($results) {
			
			print '<table class="table table-striped table-hover table-bordered" id= "data-table" cellspacing="0"><thead><tr>
			
			
			
				<th>RA No.</th>
				<th>Phase</th>
				<th>Block</th>
				<th>Lot</th>
				<th>Buyer Name</th>
				<th>Action</th>


			  </tr></thead><tbody>';
	
			while($row = $results->fetch_assoc()) {
				$res = $row['c_reservation_amt'];
				$amt_paid = $row['c_amount_paid'];
				$remaining_res = $res - $amt_paid;
				
	
				print '
					<tr>
						<td>'.$row["ra_id"].'</td>
						<td>'.$row["c_acronym"].'</td>
						<td>'.$row["c_block"].'</td>
						<td>'.$row["c_lot"].'</td>
						<td>'.$row["last_name"].', '.$row["first_name"].' '.$row["middle_name"].' </td>
					
						
						<td><a href="#" class="btn btn-primary btn-xs ra-select" data-ra-no="'.$row['ra_id'].'" data-ra-lot-lid="'.$row['c_lot_lid'].'" data-csr-no="'.$row['c_csr_no'].'" data-ra-site="'.$row['c_acronym'].'" data-ra-block="'.$row['c_block'].'" data-ra-lot="'.$row['c_lot'].'" data-res-remaining="'.$remaining_res.'" data-ra-res="'.$row['c_reservation_amt'].'" data-ra-fname="'.$row["last_name"].', '.$row["first_name"].' '.$row["middle_name"].'">Select</a></td>
				   
						</tr>
				';
			}
	
			print '</tr></tbody></table>';
	
		} else {
	
			echo "<p>There are no RAs to display.</p>";
	
		}
	
		// Frees the memory associated with a result
		$results->free();
	
		// close connection 
		$mysqli->close();
	
	}
?>
