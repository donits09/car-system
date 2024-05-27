<?php
Class Master{
	private $conn;

    public function __construct() {
        require_once('../config.php');
        $this->conn = odbc_connect($dsn, $user, $pass);
    }


	


	///////////////////////////////CAR PAYMENT///////////////////////////
	function save_car_payment(){
		extract($_POST);
	
		$values = "'$c_account_no', '$c_car_type','$c_car_no','$c_car_paydate','$c_car_amount','$c_encoded_by'";
		$insert = "INSERT INTO t_car_payment (c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount,c_encoded_by) VALUES ($values)";
		$save = odbc_exec($this->conn, $insert);
	
		$resp = array(); //
	
		if($save){
			$resp['status'] = 'success';
			$resp['msg'] = "New car payment successfully saved.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = odbc_errormsg($this->conn);
		}
	

		echo json_encode($resp);
	}
	
	
	
}
	

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);

switch ($action) {
	case 'save_car_payment':
		echo $Master->save_car_payment();
	break;
	default:
	break;
}