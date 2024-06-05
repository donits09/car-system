<?php
Class Master{
	private $conn;

    public function __construct() {
        require_once('../config.php');
        $this->conn = odbc_connect($dsn, $user, $pass);
    }

	/* function save_car_users() {
		extract($_POST);
		$data = "c_employee_code, c_password, c_realname, c_group, c_department";
		$values = "'$c_employee_code', '$c_password', '$c_realname', '$c_group', '$c_department'";
		$resp = array();
	
		if (empty($id)) {
			$insert = "INSERT INTO t_car_users ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$resp['status'] = 'success';
				$resp['msg'] = "Add user successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_car_users SET 
						c_employee_code = '$c_employee_code',
						c_password = '$c_password',
						c_realname = '$c_realname',
						c_group = '$c_group',
						c_department = '$c_department'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$resp['status'] = 'success';
				$resp['msg'] = "User successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	} */

	function save_car_users() {
		extract($_POST);
		$data = "c_employee_code, c_password, c_realname, c_group, c_department";
		$hashed_password = password_hash($c_password, PASSWORD_BCRYPT); // Hash the password
		$values = "'$c_employee_code', '$hashed_password', '$c_realname', '$c_group', '$c_department'";
		$resp = array();
	
		if (empty($id)) {
			$insert = "INSERT INTO t_car_users ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$resp['status'] = 'success';
				$resp['msg'] = "Add user successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_car_users SET 
						c_employee_code = '$c_employee_code',
						c_password = '$hashed_password', // Update hashed password
						c_realname = '$c_realname',
						c_group = '$c_group',
						c_department = '$c_department'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$resp['status'] = 'success';
				$resp['msg'] = "User successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	}	

	function delete_user(){
		$resp = array();
	
		if(isset($_POST['userId'])) {
			$userId = $_POST['userId'];
			$sql = "DELETE FROM t_car_users WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if($stmt) {
				$result = @odbc_execute($stmt, array($userId)); 
	
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

	function delete_car(){

		$resp = array();
	
		if (isset($carId) && isset($carNo)) {
			$sql = "DELETE FROM t_car_payment WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($carId)); 
	
				if ($result) {
					$this->car_logs('Car Management', "DELETED - CAR#$carNo");
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
			$resp['msg'] = 'Car ID or Car No not provided.';
		}
	
		echo json_encode($resp);
	}
	
	
	function save_car_payment() {
		extract($_POST);
		$car_type_check = "SELECT * FROM t_car_type WHERE c_payment_type = '$c_car_type'";
		$check_result = odbc_exec($this->conn, $car_type_check);
	
		if (odbc_num_rows($check_result) == 0) {
			$insert_car_type = "INSERT INTO t_car_type (c_payment_type, status) VALUES ('$c_car_type', 0)";
			$insert_result = odbc_exec($this->conn, $insert_car_type);
			if (!$insert_result) {
				error_log("Failed to insert new car type: " . odbc_errormsg($this->conn));
			}
		}
	
		$data = "c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount, c_encoded_by, c_tran_date, c_tran_updated";
		$values = "'$c_account_no', '$c_car_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date'";
		$resp = array();
	
		if (empty($id)) {
			$this->car_logs('Car Management', "ADDED - CAR#$c_car_no");
			$insert = "INSERT INTO t_car_payment ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$resp['status'] = 'success';
				$resp['msg'] = "New car payment successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_car_payment SET 
						c_car_type = '$c_car_type',
						c_car_no = '$c_car_no',
						c_car_paydate = '$c_car_paydate',
						c_car_amount = '$c_car_amount',
						c_encoded_by = '$c_encoded_by',
						c_tran_updated = '$c_tran_date'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$this->car_logs('Car Management', "UPDATED - CAR#$c_car_no");
				$resp['status'] = 'success';
				$resp['msg'] = "Car payment record successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	}

	public function car_logs($module, $notes){
		require_once('../auth/session_auth.php');
		$username = $_SESSION['username'];
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$values = "'$username','$notes','$date','$time','$module'";
		$insert = "INSERT INTO t_car_logs (c_name, c_log, c_date, c_time, c_module) VALUES ($values)";
		$save = odbc_exec($this->conn, $insert);
		if ($save) {
				$resp['status'] = 'success';
				$resp['msg'] = "Logs has been successfully inserted.";
		} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn) . " [$insert]";
		}
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
	case 'save_car_users':
		echo $Master->save_car_users();
	break;
	case 'delete_user':
		echo $Master->delete_user();
	break;

	default:
	break;
	}