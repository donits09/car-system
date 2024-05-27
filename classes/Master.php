<?php
Class Master{
	private $conn;

    public function __construct() {
        require_once('../config.php');
        $this->conn = odbc_connect($dsn, $user, $pass);
    }

	function delete_car(){
		$resp = array();
	
		if(isset($_POST['carId'])) {
			$carId = $_POST['carId'];
			$sql = "DELETE FROM t_car_payment WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if($stmt) {
				$result = @odbc_execute($stmt, array($carId)); 
	
				if ($result) {
					$resp['status'] = 'success';
					$resp['msg'] = "Car payment successfully deleted.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'Car ID not provided.';
		}
	
		echo json_encode($resp);
	}
	
	function save_car_payment(){
		extract($_POST);
		$data = "c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount,c_encoded_by";
		$values = "'$c_account_no', '$c_car_type','$c_car_no','$c_car_paydate','$c_car_amount','$c_encoded_by'";
		if (empty($id)) {
		$insert = "INSERT INTO t_car_payment ($data) VALUES ($values)";
		$save = odbc_exec($this->conn, $insert);
	
		$resp = array(); 
		}else {
			$sql = "UPDATE t_car_payment SET ($data) = ($values) WHERE id = '$id'";
			$save = odbc_exec($this->conn, $sql);
		}
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
	case 'delete_car':
		echo $Master->delete_car();
	break;

	default:
	break;
}