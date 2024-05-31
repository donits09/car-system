<?php
Class Master{
	private $conn;

    public function __construct() {
        require_once('../config.php');
        $this->conn = odbc_connect($dsn, $user, $pass);
    }

	function save_car_users(){
		extract($_POST);
	
		$values = "'$c_employee_code', '$c_password','$c_realname','$c_group','$c_department','$c_user_type";
		$insert = "INSERT INTO t_car_users (c_employee_code,c_password,c_realname,c_group,c_department,c_user_type) VALUES ($values)";
		$save = odbc_exec($this->conn, $insert);
	
		$resp = array(); //
	
		if($save){
			$resp['status'] = 'success';
			$resp['msg'] = "New User Successfully saved.";
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = odbc_errormsg($this->conn);
		}
	

		echo json_encode($resp);
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
	
	function save_car_payment() {
		extract($_POST);
        
		$data = "c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount, c_encoded_by";
		$values = "'$c_account_no', '$c_car_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by'";
		$resp = array();
	
		if (empty($id)) {
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
						c_account_no = '$c_account_no',
						c_car_type = '$c_car_type',
						c_car_no = '$c_car_no',
						c_car_paydate = '$c_car_paydate',
						c_car_amount = '$c_car_amount',
						c_encoded_by = '$c_encoded_by'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$resp['status'] = 'success';
				$resp['msg'] = "Car payment record successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	}
	

// function save_car_payment() {
//     extract($_POST);
//     $resp = array();
    
//     $final_car_type = !empty($new_payment_type) ? $new_payment_type : $c_car_type;

    
//     if (!$this->conn) {
//         $resp['status'] = 'failed';
//         $resp['msg'] = "Database connection failed.";
//         echo json_encode($resp);
//         return;
//     }

//     $check_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE c_payment_type = ?";
//     $check_stmt = odbc_prepare($this->conn, $check_query);
//     $check_result = odbc_execute($check_stmt, array($final_car_type));

//     if ($check_result) {
//         $check_row = odbc_fetch_array($check_stmt);
//         if ($check_row['count'] == 0) {
//             $insert_car_type_query = "INSERT INTO t_car_type (c_payment_type, status) VALUES (?, 0)";
//             $insert_car_type_stmt = odbc_prepare($this->conn, $insert_car_type_query);
//             $insert_result = odbc_execute($insert_car_type_stmt, array($final_car_type));

//             if (!$insert_result) {
//                 $resp['status'] = 'failed';
//                 $resp['msg'] = "Failed to insert new car type into t_car_type table.";
//                 echo json_encode($resp);
//                 return;
//             }
//         }
//     } else {
//         $resp['status'] = 'failed';
//         $resp['msg'] = "Failed to check existing car types.";
//         echo json_encode($resp);
//         return;
//     }

//     $data = array(
//         "c_account_no" => $c_account_no,
//         "c_car_type" => $final_car_type,
//         "c_car_no" => $c_car_no,
//         "c_car_paydate" => $c_car_paydate,
//         "c_car_amount" => $c_car_amount,
//         "c_encoded_by" => $c_encoded_by
//     );

//     $columns = implode(", ", array_keys($data));
//     $placeholders = implode(", ", array_fill(0, count($data), '?'));
//     $values = array_values($data);

//     if (empty($id)) {
//         $insert_query = "INSERT INTO t_car_payment ($columns) VALUES ($placeholders)";
//         $stmt = odbc_prepare($this->conn, $insert_query);

//         $save = odbc_execute($stmt, $values);

//         if ($save) {
//             $resp['status'] = 'success';
//             $resp['msg'] = "New car payment successfully saved.";
//         } else {
//             $resp['status'] = 'failed';
//             $resp['err'] = odbc_errormsg($this->conn);
//         }
//     } else {
//         $update_query = "UPDATE t_car_payment SET 
//                             c_account_no = ?, 
//                             c_car_type = ?, 
//                             c_car_no = ?, 
//                             c_car_paydate = ?, 
//                             c_car_amount = ?, 
//                             c_encoded_by = ?
//                         WHERE id = ?";
//         $values[] = $id; 
//         $stmt = odbc_prepare($this->conn, $update_query);

//         $save = odbc_execute($stmt, $values);

//         if ($save) {
//             $resp['status'] = 'success';
//             $resp['msg'] = "Car payment record successfully updated.";
//         } else {
//             $resp['status'] = 'failed';
//             $resp['err'] = odbc_errormsg($this->conn);
//         }
//     }

//     header('Content-Type: application/json'); 
//     echo json_encode($resp); 
// }



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
	default:
	break;
}