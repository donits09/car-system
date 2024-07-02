<?php
Class Master{
	private $conn;

    public function __construct() {
        require_once('../config.php');
		global $dsn, $user, $pass;
        $this->conn = odbc_connect($dsn, $user, $pass);
    }

	function save_car_users() {
		extract($_POST);
		$resp = array();
	
		if (empty($id)) {
			$check_query = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_employee_code'";
			$check_result = odbc_exec($this->conn, $check_query);
	
			if (odbc_num_rows($check_result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Employee code already exists.";
				echo json_encode($resp);
				return;
			}
	
			$hashed_password = password_hash($c_password, PASSWORD_BCRYPT);
			$data = "c_employee_code, c_password, c_realname, c_group, c_department";
			$values = "'$c_employee_code', '$hashed_password', '$c_realname', '$c_group', '$c_department'";
			$insert = "INSERT INTO t_car_users ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$this->car_logs('Car Users', "ADDED - $c_employee_code - $c_realname");
				$resp['status'] = 'success';
				$resp['msg'] = "User successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$check_query = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_employee_code' AND id != '$id'";
			$check_result = odbc_exec($this->conn, $check_query);
	
			if (odbc_num_rows($check_result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Employee code already exists.";
				echo json_encode($resp);
				return;
			}
	
			$update_fields = array(
				"c_employee_code = '$c_employee_code'",
				"c_realname = '$c_realname'",
				"c_group = '$c_group'",
				"c_department = '$c_department'"
			);
	
			if (!empty($c_password)) {
				$hashed_password = password_hash($c_password, PASSWORD_BCRYPT);
				$update_fields[] = "c_password = '$hashed_password'";
			}
	
			$update_query = "UPDATE t_car_users SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update_query);
	
			if ($save) {
				$this->car_logs('Car Users', "UPDATE - $c_employee_code - $c_realname");
				$resp['status'] = 'success';
				$resp['msg'] = "User successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	}				

	function delete_user() {
		$resp = array();
	
		if (isset($_POST['userId'])) {
			$userId = $_POST['userId'];
			$fetchSql = "SELECT * FROM t_car_users WHERE id = ?";
			$fetchStmt = odbc_prepare($this->conn, $fetchSql);
	
			if ($fetchStmt) {
				$fetchResult = odbc_execute($fetchStmt, array($userId));
				$c_employee_code = null;
				$c_realname = null;
	
				if ($fetchResult) {
					$row = odbc_fetch_array($fetchStmt);
					$c_employee_code = $row['c_employee_code'];
					$c_realname = $row['c_realname'];
				}
	
				$sql = "DELETE FROM t_car_users WHERE id = ?";
				$stmt = odbc_prepare($this->conn, $sql);
	
				if ($stmt) {
					$result = @odbc_execute($stmt, array($userId));
	
					if ($result) {
						$this->car_logs('Car Users', "DELETED - $c_employee_code - $c_realname");
						$resp['status'] = 'success';
						$resp['msg'] = "User successfully deleted.";
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
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'Car ID not provided.';
		}
	
		echo json_encode($resp);
	}	

	function delete_car($carId, $carNo) {
		$resp = array();
	
		if (isset($carId) && isset($carNo)) {
			$sql = "UPDATE t_car_payment SET status = 1 WHERE id = ?";
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
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	
	function delete_car_type($carTypeId, $carType) {
		$resp = array();
	
		if (isset($carTypeId) && isset($carType)) {
			$sql = "DELETE FROM t_car_type WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($carTypeId)); 
	
				if ($result) {
					$this->car_logs('Car Type Management', "DELETED - $carType");
					$resp['status'] = 'success';
					$resp['msg'] = "Car payment type successfully deleted.";
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
			$resp['msg'] = 'Car type not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	
	function save_car_payment() {
		extract($_POST);
		$c_car_amount = str_replace(',', '', $c_car_amount);
	
		$car_type_check = "SELECT * FROM t_car_type WHERE c_payment_type = '$c_car_type'";
		$check_result = odbc_exec($this->conn, $car_type_check);
	
		if (odbc_num_rows($check_result) == 0) {
			$insert_car_type = "INSERT INTO t_car_type (c_payment_type, status) VALUES ('$c_car_type', 0)";
			$insert_result = odbc_exec($this->conn, $insert_car_type);
			if (!$insert_result) {
				error_log("Failed to insert new car type: " . odbc_errormsg($this->conn));
			}
		}
		$maxIdQuery = "SELECT MAX(id) AS max_id FROM t_car_payment";
		$maxIdResult = odbc_exec($this->conn, $maxIdQuery);
	
		if ($maxIdResult) {
			$row = odbc_fetch_array($maxIdResult);
			$maxId = $row['max_id'] + 1; 
		} else {
			$maxId = 1; 
			error_log("Failed to retrieve max ID: " . odbc_errormsg($this->conn));
		}
		$data = "id, c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount, c_encoded_by, c_tran_date, c_tran_updated,c_mop";
		$values = "'$maxId','$c_account_no', '$c_car_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date','$c_mop'";
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
						c_tran_updated = '$c_tran_date',
						c_mop = '$c_mop'
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
	
	function save_other_car_payment() {
		extract($_POST);
	
		$c_car_amount = str_replace(',', '', $c_car_amount);

		$car_type_check = "SELECT * FROM t_car_type WHERE c_payment_type = '$c_car_type'";
		$check_result = odbc_exec($this->conn, $car_type_check);
	
		if (odbc_num_rows($check_result) == 0) {
			$insert_car_type = "INSERT INTO t_car_type (c_payment_type, status) VALUES ('$c_car_type', 0)";
			$insert_result = odbc_exec($this->conn, $insert_car_type);
			if (!$insert_result) {
				error_log("Failed to insert new car type: " . odbc_errormsg($this->conn));
			}
		}
	
		$maxIdQuery = "SELECT MAX(id) AS max_id FROM t_car_payment";
		$maxIdResult = odbc_exec($this->conn, $maxIdQuery);
	
		if ($maxIdResult) {
			$row = odbc_fetch_array($maxIdResult);
			$maxId = $row['max_id'] + 1; 
		} else {
			$maxId = 1; 
			error_log("Failed to retrieve max ID: " . odbc_errormsg($this->conn));
		}
	
		$data = "id, c_car_no, c_name, c_phase, c_block, c_lot";
		$values = "'$maxId', '$c_car_no', '$c_name', '$c_phase', '$c_block', '$c_lot'";

		$c_account_no = '';
		$data1 = "id, c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount, c_encoded_by, c_tran_date, c_tran_updated, c_mop";
		$values1 = "'$maxId', '$c_account_no', '$c_car_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$c_mop'";
	
		$resp = array();
	
		if (empty($id)) {
			$insert = "INSERT INTO t_other_car_payment ($data) VALUES ($values)";
			$insert1 = "INSERT INTO t_car_payment ($data1) VALUES ($values1)";
			$save = odbc_exec($this->conn, $insert);
			$save1 = odbc_exec($this->conn, $insert1);
	
			if ($save && $save1) {
				$this->car_logs('Car Management', "ADDED - CAR#$c_car_no");
				$resp['status'] = 'success';
				$resp['msg'] = "New car payment successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else { 
			$update = "UPDATE t_other_car_payment SET 
						c_car_no = '$c_car_no',
						c_name = '$c_name',
						c_phase = '$c_phase',
						c_block = '$c_block',
						c_lot = '$c_lot'
					  WHERE id = '$id'";
			$update1 = "UPDATE t_car_payment SET 
						c_car_type = '$c_car_type',
						c_car_no = '$c_car_no',
						c_car_paydate = '$c_car_paydate',
						c_car_amount = '$c_car_amount',
						c_tran_updated = '$c_tran_date',
						c_mop = '$c_mop'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
			$save1 = odbc_exec($this->conn, $update1);
	
			if ($save && $save1) {
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
	
	function save_car_type() {
		extract($_POST);
	
		$data = "c_payment_type, status";
		$values = "'$c_payment_type','0'";
		$resp = array();
	
		if (empty($id)) {
			
			$insert = "INSERT INTO t_car_type ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$this->car_logs('Car Type Management', "ADDED - $c_payment_type");
				$resp['status'] = 'success';
				$resp['msg'] = "New car type successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_car_type SET 
						c_payment_type = '$c_payment_type',
						status = '$status'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$this->car_logs('Car Type Management', "UPDATED - $c_payment_type");
				$resp['status'] = 'success';
				$resp['msg'] = "Car type successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	}

	// function save_remarks() {
	// 	extract($_POST);
	// 	$resp = array();

	// 	$buyer_acc_no = isset($buyer_acc_no) ? (int)$buyer_acc_no : 0;
	// 	$buyer_remarks = isset($buyer_remarks) ? trim($buyer_remarks) : '';

	// 	if ($buyer_acc_no > 0 && !empty($buyer_remarks)) {
	// 		try {
	// 			$update_query = "UPDATE t_buyers_account SET c_remarks = '" . addslashes($buyer_remarks) . "' WHERE c_account_no = $buyer_acc_no";
	// 			$save = odbc_exec($this->conn, $update_query);
	
	// 			if ($save) {
	// 				$this->car_logs('Car Management', "Updated remarks - $buyer_acc_no - $buyer_remarks");
	// 				$resp['status'] = 'success';
	// 				$resp['msg'] = "Buyer's account successfully updated.";
	// 				$resp['remarks'] = $buyer_remarks; 
	// 			} else {
	// 				$resp['status'] = 'failed';
	// 				$resp['err'] = odbc_errormsg($this->conn);
	// 			}
	// 		} catch (Exception $e) {
	// 			$resp['status'] = 'failed';
	// 			$resp['err'] = 'SQL error: ' . $e->getMessage();
	// 		}
	// 	} else {
	// 		$resp['status'] = 'failed';
	// 		$resp['err'] = 'Invalid input or missing account number or remarks.';
	// 	}
	// 	echo json_encode($resp);
	// }
		
	

	
	////////////////Naloka ako rito. Jujubels. Dhen, pa-add nalang din ng logs sa ibang functions na wala pa. -dhonitsxzkie
	function save_remarks() {
		ob_start();
	
		extract($_POST);
		$resp = array();
	
		$buyer_acc_no = isset($buyer_acc_no) ? (int)$buyer_acc_no : 0;
		$buyer_remarks = isset($buyer_remarks) ? trim($buyer_remarks) : '';
	
		if ($buyer_acc_no > 0 && !empty($buyer_remarks)) {
			try {
				$fetch_query = "SELECT c_remarks FROM t_buyers_account WHERE c_account_no = $buyer_acc_no";
				$fetch_result = odbc_exec($this->conn, $fetch_query);
				$current_remarks = odbc_result($fetch_result, 'c_remarks');
	
				$update_query = "UPDATE t_buyers_account SET c_remarks = '" . pg_escape_string($buyer_remarks) . "' WHERE c_account_no = $buyer_acc_no";
				$save = odbc_exec($this->conn, $update_query);
	
				if ($save) {
					$current_lines = explode("\n", $current_remarks);
					$new_lines = explode("\n", $buyer_remarks);
					$modified_lines = [];
					foreach ($new_lines as $index => $line) {
						if (!isset($current_lines[$index]) || $current_lines[$index] !== $line) {
							$prev_line = isset($current_lines[$index]) ? $current_lines[$index] : '';
							$this->car_logs('Car Management', "UPDATED REMARKS - $buyer_acc_no - PREVIOUS: $prev_line, UPDATED: $line");
	
							$modified_lines[] = $line;
						}
					}
	
					$resp['status'] = 'success';
					$resp['msg'] = "Buyer's account successfully updated.";
					$resp['remarks'] = $buyer_remarks;
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} catch (Exception $e) {
				$resp['status'] = 'failed';
				$resp['err'] = 'SQL error: ' . $e->getMessage();
			}
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = 'Invalid input or missing account number or remarks.';
		}
	
		ob_end_clean();
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
		
	
	
	function save_locked_trans() {
		extract($_POST);
		
		$tran_date = htmlspecialchars($tran_date); 
		$total_cash = floatval(str_replace(',', '', $total_cash)); 
		$total_check = floatval(str_replace(',', '', $total_check)); 
		$total = floatval(str_replace(',', '', $total)); 
	
		$data = "tran_date, total_cash, total_check, total, status";
		$values = "'$tran_date', '$total_cash', $total_check, $total, '1'";
		$resp = array();
		$insert = "INSERT INTO t_summary_reports ($data) VALUES ($values)";

		$update = "UPDATE t_car_payment SET e_status = 1 WHERE DATE(c_tran_date) = '$tran_date'";
		
		$save = odbc_exec($this->conn, $insert);
		$save1 = odbc_exec($this->conn, $update);
	
		if ($save && $save1) {
			$this->car_logs('Summary Reports', "REPORT LOCKED - $tran_date");
			$resp['status'] = 'success';
			$resp['msg'] = "Summary report locked successfully.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = odbc_errormsg($this->conn);
		}
	
		echo json_encode($resp);
	}
	
	function unlock_trans() {
		extract($_POST);
		
		$tran_date = htmlspecialchars($tran_date); 

		$resp = array();
		$update = "UPDATE t_summary_reports SET status = 0 WHERE DATE(tran_date) = '$tran_date'";

		$update1 = "UPDATE t_car_payment SET e_status = 0 WHERE DATE(c_tran_date) = '$tran_date'";
		
		$save = odbc_exec($this->conn, $update);
		$save1 = odbc_exec($this->conn, $update1);
	
		if ($save && $save1) {
			$this->car_logs('Summary Reports', "UNLOCKED - $tran_date");
			$resp['status'] = 'success';
			$resp['msg'] = "Summary report unlocked successfully.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = odbc_errormsg($this->conn);
		}
	
		echo json_encode($resp);
	}
	
	public function car_logs($module, $notes){
		require_once('../auth/session_auth.php');

		$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'unknown';
		
	
		$escaped_username = $username;
		$escaped_notes = pg_escape_string($notes); ///shutainamerliiiiiiiiiiiiiiiiiii
		$escaped_date = date('Y-m-d');
		$escaped_time = date('H:i:s');
		$escaped_module = $module;

		$insert = "INSERT INTO t_car_logs (c_name, c_log, c_date, c_time, c_module) 
				   VALUES ('$escaped_username', '$escaped_notes', '$escaped_date', '$escaped_time', '$escaped_module')";
		

		$save = odbc_exec($this->conn, $insert);
		
		$resp = [];
		if ($save) {
			$resp['status'] = 'success';
			$resp['msg'] = "Logs have been successfully inserted.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = odbc_errormsg($this->conn) . " [$insert]";
		}
		//echo json_encode($resp);
	}
	
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'save_car_payment':
        echo $Master->save_car_payment();
        break;
	case 'save_other_car_payment':
		echo $Master->save_other_car_payment();
		break;
	case 'save_car_type':
		echo $Master->save_car_type();
		break;
	case 'save_remarks':
		echo $Master->save_remarks();
		break;
    case 'delete_car':
        if (isset($_POST['carId']) && isset($_POST['carNo'])) {
            echo $Master->delete_car($_POST['carId'], $_POST['carNo']);
        } else {
            echo json_encode(array('status' => 'failed', 'msg' => 'Car type not provided.'));
        }
        break;
	case 'delete_car_type':
		if (isset($_POST['carTypeId']) && isset($_POST['carType'])) {
			echo $Master->delete_car_type($_POST['carTypeId'], $_POST['carType']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'Car type not provided.'));
		}
		break;
    case 'save_car_users':
        echo $Master->save_car_users();
        break;
    case 'delete_user':
        echo $Master->delete_user();
        break;
	case 'save_locked_trans':
		echo $Master->save_locked_trans();
		break;
	case 'unlock_trans':
		echo $Master->unlock_trans();
		break;
    default:
        break;
}
