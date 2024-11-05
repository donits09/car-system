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

			odbc_fetch_row($check_result);
			if (odbc_num_rows($check_result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Employee already exists.";
				echo json_encode($resp);
				return;
			}
	
			$hashed_password = password_hash($c_password, PASSWORD_BCRYPT);
			$data = "c_employee_code, c_password, c_realname, c_group, c_department, c_position";
			$values = "'$c_employee_code', '$hashed_password', '$c_realname', '$c_group', '$c_department', '$c_position' ";
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
	
			odbc_fetch_row($check_result);
			if (odbc_num_rows($check_result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Employee already exists.";
				echo json_encode($resp);
				return;
			}
	
			$update_fields = array(
				"c_employee_code = '$c_employee_code'",
				"c_realname = '$c_realname'",
				"c_group = '$c_group'",
				"c_department = '$c_department'",
				"c_position = '$c_position'"
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
	function delete_car($carNo, $atapNo) {
		$resp = array();
		
		if (!empty($carNo)) {
			if ($atapNo == 0) {
				$sql = "UPDATE t_car_payment SET status = 1 WHERE c_car_no = ?";
				$stmt = odbc_prepare($this->conn, $sql);
	
				if ($stmt) {
					$result = @odbc_execute($stmt, array($carNo));
	
					if ($result) {
						$this->car_logs('Car Management', "CANCELLED - CAR#$carNo");
						$resp['status'] = 'success';
						$resp['msg'] = "Car payment successfully canceled.";
					} else {
						$resp['status'] = 'failed';
						$resp['err'] = "Failed to cancel car payment: " . odbc_errormsg($this->conn);
					}
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = "Failed to prepare car payment cancellation: " . odbc_errormsg($this->conn);
				}

				header('Content-Type: application/json');
				echo json_encode($resp);
				return;
			}

			if (!empty($atapNo)) {
				$sql = "UPDATE t_car_payment SET status = 1 WHERE c_car_no = ?";
				$stmt = odbc_prepare($this->conn, $sql);
		
				if ($stmt) {
					$result = @odbc_execute($stmt, array($carNo));
		
					if ($result) {
						$this->car_logs('Car Management', "CANCELLED - CAR#$carNo");
		
						$sql2 = "UPDATE t_atap_items SET atap_status = 0 WHERE c_car_no = ?";
						$stmt2 = odbc_prepare($this->conn, $sql2);
		
						if ($stmt2) {
							$result2 = @odbc_execute($stmt2, array($carNo));
		
							if ($result2) {
								$sqlGetCarNo = "SELECT c_car_no FROM t_atap WHERE c_atap_no = ?";
								$stmtGetCarNo = odbc_prepare($this->conn, $sqlGetCarNo);
		
								if ($stmtGetCarNo) {
									$resultGetCarNo = @odbc_execute($stmtGetCarNo, array($atapNo));
		
									if ($resultGetCarNo) {
										$row = odbc_fetch_array($stmtGetCarNo);
										$currentCarNo = $row['c_car_no'];
		
										if ($currentCarNo) {
											$carNoArray = array_filter(explode(',', $currentCarNo), function($car) use ($carNo) {
												return $car != $carNo;
											});
		
											$updatedCarNo = implode(',', $carNoArray);
		
											if (!empty($updatedCarNo)) {
												$sqlUpdateAtap = "UPDATE t_atap SET c_car_no = ?, status = 2 WHERE c_atap_no = ?";
												$stmtUpdateAtap = odbc_prepare($this->conn, $sqlUpdateAtap);
												$resultUpdateAtap = @odbc_execute($stmtUpdateAtap, array($updatedCarNo, $atapNo));
											} else {
												$sqlUpdateAtap = "UPDATE t_atap SET c_car_no = NULL, status = 0 WHERE c_atap_no = ?";
												$stmtUpdateAtap = odbc_prepare($this->conn, $sqlUpdateAtap);
												$resultUpdateAtap = @odbc_execute($stmtUpdateAtap, array($atapNo));
											}
		
											if ($resultUpdateAtap) {
												$resp['status'] = 'success';
												$resp['msg'] = "Car and ATAP status successfully updated.";
											} else {
												$resp['status'] = 'failed';
												$resp['err'] = "Failed to update ATAP status: " . odbc_errormsg($this->conn);
											}
										} else {
											$resp['status'] = 'failed';
											$resp['msg'] = "No car number found for the given ATAP number.";
										}
									} else {
										$resp['status'] = 'failed';
										$resp['err'] = "Failed to retrieve car number: " . odbc_errormsg($this->conn);
									}
								} else {
									$resp['status'] = 'failed';
									$resp['err'] = "Failed to prepare car number retrieval: " . odbc_errormsg($this->conn);
								}
							} else {
								$resp['status'] = 'failed';
								$resp['err'] = "Failed to update ATAP items status: " . odbc_errormsg($this->conn);
							}
						} else {
							$resp['status'] = 'failed';
							$resp['err'] = "Failed to prepare ATAP items update: " . odbc_errormsg($this->conn);
						}
					} else {
						$resp['status'] = 'failed';
						$resp['err'] = "Failed to cancel car payment: " . odbc_errormsg($this->conn);
					}
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = "Failed to prepare car payment cancellation: " . odbc_errormsg($this->conn);
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = 'ATAP No not provided.';
			}
		/* Handle yung $atapNo na 0 (yung mga walang atap) */
		}else if(!empty($carNo) && $atapNo == 0) {
			$sql = "UPDATE t_car_payment SET status = 1 WHERE c_car_no = ?";
			$stmt = odbc_prepare($this->conn, $sql);
			if ($stmt && @odbc_execute($stmt, array($carNo))) {
				$this->car_logs('Car Management', "CANCELLED - CAR#$carNo");
				$resp['status'] = 'success';
				$resp['msg'] = "Car and ATAP status successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = "Failed to cancel car payment without atap " . odbc_errormsg($this->conn);
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'Car No not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}

	
	function delete_atap($atapId, $atapNo) {
		$resp = array();
	
		if (isset($atapId) && isset($atapNo)) {
			error_log("Attempting to cancel ATAP with number: $atapNo");
	
			$sql = "UPDATE t_atap SET status = 3 WHERE id = ?";
			$sql2 = "UPDATE t_atap_items SET atap_status = 3 WHERE c_atap_no = ?";
			$stmt = odbc_prepare($this->conn, $sql);
			$stmt2 = odbc_prepare($this->conn, $sql2);
	
			if ($stmt && $stmt2) {
				$result = @odbc_execute($stmt, array($atapId));
				$result2 = @odbc_execute($stmt2, array($atapNo));
	
				if ($result && $result2) {
					$this->car_logs('Car Management - ATAP', "CANCELLED - ATAP#$atapNo");
					$resp['status'] = 'success';
					$resp['msg'] = "ATAP successfully cancelled.";
				} else {
					error_log("Error executing updates for ATAP with number: $atapNo");
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} else {
				
				error_log("Error preparing statements for ATAP with number: $atapNo");
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'ATAP ID or ATAP No not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}

	function delete_tenant($tenantId) {
		$resp = array();
	
		if (isset($tenantId)) {
	
			$sql = "UPDATE t_tenant_accounts SET c_status = 2 WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($tenantId));
	
				if ($result) {
					$this->car_logs('Tenant Accounts', "DELETED - Tenant#$tenantId");
					$resp['status'] = 'success';
					$resp['msg'] = "Tenant's Information successfully removed.";
				} else {
					error_log("Error executing updates for Tenant #: $tenantId");
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} else {
				
				error_log("Error preparing statements for Tenant #: $tenantId");
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'Tenant # not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}

	function approve_atap($atapId, $atapNo) {
		$resp = array();
	
		if (isset($atapId) && isset($atapNo)) {
			$sql = "UPDATE t_atap SET approval_status = 2 WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($atapId));
			
				if ($result) {
					$resp['status'] = 'success';
					$resp['msg'] = "ATAP successfully approved.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} else {
				error_log("Error preparing statements for ATAP with number: $atapNo");
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'ATAP ID or ATAP No not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	
	function disapprove_atap($atapId, $atapNo) {
		$resp = array();
	
		if (isset($atapId) && isset($atapNo)) {
			$sql = "UPDATE t_atap SET approval_status = 3 WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($atapId));
			
				if ($result) {
					$resp['status'] = 'success';
					$resp['msg'] = "ATAP successfully disapproved.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} else {
				error_log("Error preparing statements for ATAP with number: $atapNo");
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'ATAP ID or ATAP No not provided.';
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

	function update_items_status() {
		$resp = array();
		$Logs = false;

		error_log(print_r($_POST['items'], true));
	
		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$items = $_POST['items'];
			$conn = $this->conn;

			$atapNo = null;
	
			foreach ($items as $item) {
				error_log("Item data: " . print_r($item, true));
	
				$atapId = isset($item['atapId']) ? $item['atapId'] : null;
				$status = isset($item['status']) ? $item['status'] : null;
				$atapNo = isset($item['atapNo']) ? $item['atapNo'] : $atapNo;
	
				if ($atapId !== null) {
					$sql = "UPDATE t_atap_items SET atap_status = ? WHERE id = ?";
					$stmt = odbc_prepare($conn, $sql);
	
					if ($stmt) {
						$result = @odbc_execute($stmt, array($status, $atapId));
	
						if ($result) {
							$Logs = true;
							$resp['status'] = 'success';
							$resp['msg'] = "ATAP status successfully updated.";
						} else {
							$resp['status'] = 'failed';
							$resp['err'] = odbc_errormsg($conn);
							break;
						}
					} else {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($conn);
						break;
					}
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = 'ATAP ID or ATAP No not provided.';
					break;
				}
			}
	
			if ($atapNo !== null) {
				$check_status_sql = "SELECT atap_status FROM t_atap_items WHERE c_atap_no = ?";
				$check_status_stmt = odbc_prepare($conn, $check_status_sql);
			
				if ($check_status_stmt) {
					$check_result = @odbc_execute($check_status_stmt, array($atapNo));
			
					if ($check_result) {
						$hasPartial = false;
						$allPaid = true;
			
						while ($row = odbc_fetch_array($check_status_stmt)) {
							if ($row['atap_status'] == 1) {
								$hasPartial = true;
							} elseif ($row['atap_status'] == 0) {
								$allPaid = false;
							}
						}
			
						$new_status = 0;
						if ($allPaid) {
							$new_status = 1; 
						} elseif ($hasPartial) {
							$new_status = 2; 
						}
			
			
						$update_atap_sql = "UPDATE t_atap SET status = ? WHERE c_atap_no = ?";
						$update_atap_stmt = odbc_prepare($conn, $update_atap_sql);
			
						if ($update_atap_stmt) {
							$update_result = @odbc_execute($update_atap_stmt, array($new_status, $atapNo));
			
							if ($update_result) {
								$Logs = true;
								$resp['status'] = 'success';
								$resp['msg'] = "ATAP #$atapNo successfully updated.";
							} else {
								$resp['status'] = 'failed';
								$resp['err'] = odbc_errormsg($conn);
							}
						} else {
							$resp['status'] = 'failed';
							$resp['err'] = odbc_errormsg($conn);
						}
					} else {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($conn);
					}
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($conn);
					
				}
			}
			
			if ($atapNo !== null && $Logs && $atapId !== null) {
				$this->car_logs('Car Type Management - ATAP', "UPDATED PAYMENT STATUS - $atapNo");
			}

		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'Invalid data format.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	

	function save_or_payment() {
		extract($_POST);
		$conn = $this->conn;
		$c_or_amount = str_replace(',', '', $c_or_amount);
		
		$atap_id = $_POST['c_atap_no_or'];
		$atap_val = $_POST['atap_id_or'];

		if ($c_check_no == '' || $c_check_no == null) {
			$c_check_no = $c_ref_no;
		}
	
		if ($c_mop_or == 1) {
			$c_bank = "";
		} elseif ($c_mop_or == 2) {
			$c_bank = isset($_POST['c_bank_check_or']) ? $_POST['c_bank_check_or'] : "";
		} elseif ($c_mop_or == 3) {
			$c_bank = isset($_POST['c_bank_online_or']) ? $_POST['c_bank_online_or'] : "";
		}

		if (!empty($_POST['c_tran_type_single_or'])) {
			$c_tran_type = $_POST['c_tran_type_single_or'];
		} elseif (!empty($_POST['c_tran_type_or'])) {
			$c_tran_type = $_POST['atap_val_or'];
		} elseif (!empty($_POST['c_or_type'])) {
			$c_tran_type = $_POST['c_or_type'];
	
			$tran_type = trim(pg_escape_string($c_tran_type));
	
			$check_type_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE LOWER(c_payment_type) = LOWER('$tran_type')";
			$check_type_result = odbc_exec($conn, $check_type_query);
	
			if (!$check_type_result) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error executing query.";
				echo json_encode($resp);
				return;
			}
	
			$type_exists = odbc_fetch_array($check_type_result)['count'];
	
			if ($type_exists == 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "OR type does not exist.";
				echo json_encode($resp);
				return;
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "Transaction type or OR type is required.";
			echo json_encode($resp);
			return;
		}

		if (empty($id)) {
			$check_or_no_query = "SELECT COUNT(*) AS count FROM t_or_payment WHERE c_or_no = '$c_or_no'";
			$check_or_no_result = odbc_exec($this->conn, $check_or_no_query);

			if ($check_or_no_result) {
				$row = odbc_fetch_array($check_or_no_result);
				$or_no_exists = $row['count'];

				if ($or_no_exists > 0) {
					$resp['status'] = 'failed';
					$resp['msg'] = "OR number '$c_or_no' already exists.";
					echo json_encode($resp);
					return;
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error checking OR number.";
				echo json_encode($resp);
				return;
			}
		}

		$maxIdQuery = "SELECT MAX(id) AS max_id FROM t_or_payment";
		$maxIdResult = odbc_exec($this->conn, $maxIdQuery);
	
		if ($maxIdResult) {
			$row = odbc_fetch_array($maxIdResult);
			$maxId = $row['max_id'] + 1;
		} else {
			$maxId = 1;
			error_log("Failed to retrieve max ID: " . odbc_errormsg($this->conn));
		}

		$data = "id, c_account_no, c_or_type, c_or_no, c_or_paydate, c_or_amount, c_encoded_by, c_tran_date, c_tran_updated,c_mop,c_bank,c_check_no,c_remarks, c_atap_no";
		$values = "'$maxId','$c_account_no_or', '$c_tran_type', '$c_or_no', '$c_or_paydate', '$c_or_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date','$c_mop_or','$c_bank','$c_check_no','$c_remarks', '" . (!empty($atap_id) ? $atap_id : '0') . "'";
	
		$resp = array();
	
		if (empty($id)) {
			$this->car_logs('OR Management', "ADDED - OR#$c_or_no");
			$insert = "INSERT INTO t_or_payment ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				if (!empty($atap_id)) {
					$update_tran_type = "UPDATE t_atap_items SET atap_status = 1, c_car_no ='$c_or_no' WHERE id = '$atap_val'";
					$update_tran_type_result = odbc_exec($this->conn, $update_tran_type);
					if (!$update_tran_type_result) {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($this->conn);
					}
				}

				if (!empty($atap_id)) {
					$check_items = "SELECT COUNT(*) AS count_items FROM t_atap_items WHERE c_atap_no = '$atap_id' AND atap_status = 0";
					$check_items_result = odbc_exec($this->conn, $check_items);
	
					if ($check_items_result) {
						$row = odbc_fetch_array($check_items_result);
						$count_items = $row['count_items'];
			
						$current_or_no_query = "SELECT c_car_no FROM t_atap WHERE c_atap_no = '$atap_id'";
						$current_or_no_result = odbc_exec($this->conn, $current_or_no_query);
					
						if ($current_or_no_result) {
							$row_car = odbc_fetch_array($current_or_no_result);
							$current_or_no = $row_car['c_car_no'];
					
							$or_no_array = explode(',', $current_or_no); 
							if (!in_array($c_or_no, $or_no_array)) { 
								$new_or_no = $current_or_no ? $current_or_no . ',' . $c_or_no : $c_or_no;
					
								if ($count_items > 0) {
									$update_atap = "UPDATE t_atap SET status = 2, c_car_no = '$new_or_no' WHERE c_atap_no = '$atap_id'";
								} else {
									$update_atap = "UPDATE t_atap SET status = 1, c_car_no = '$new_or_no' WHERE c_atap_no = '$atap_id'";
								}
								$update = odbc_exec($this->conn, $update_atap);
							} else {
								$update = true; 
							}
						} else {
							$update = false;
						}
					} else {
						$update = false;
					}
					
				} else {
					$update = true;
				}
	
				if ($update) {
					$resp['status'] = 'success';
					$resp['msg'] = "New or payment successfully saved.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_or_payment SET 
						c_or_type = '$c_tran_type',
						c_or_no = '$c_or_no',
						c_or_paydate = '$c_or_paydate',
						c_or_amount = '$c_or_amount',
						c_tran_updated = '$c_tran_date',
						c_mop = '$c_mop_or',
						c_bank = '$c_bank',
						c_check_no = '$c_check_no',
						c_remarks = '$c_remarks'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$this->car_logs('OR Management', "UPDATED - OR#$c_or_no");
				if (!empty($atap_id)) {
					$update_tran_type = "UPDATE t_atap_items SET atap_status = 1 WHERE id = '$atap_id'";
					$update_tran_type_result = odbc_exec($this->conn, $update_tran_type);
					if (!$update_tran_type_result) {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($this->conn);
					}
				}
	
				$resp['status'] = 'success';
				$resp['msg'] = "OR payment record successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	}
	
	function save_car_payment() {
		extract($_POST);
		$conn = $this->conn;
		$c_car_amount = str_replace(',', '', $c_car_amount);
	
		
		$atap_id = $_POST['atap_id'];
	

		// if(empty($atap_id)){
		// 	$resp['status'] = 'failed';
		// 	$resp['msg'] = "CAR type does not exist.";
		// 	echo json_encode($resp);
		// 	return;
		// }

		if ($c_check_no == '' || $c_check_no == null) {
			$c_check_no = $c_ref_no;
		}
	
		if ($c_mop == 1) {
			$c_bank = "";
		} elseif ($c_mop == 2) {
			$c_bank = isset($_POST['c_bank_check']) ? $_POST['c_bank_check'] : "";
		} elseif ($c_mop == 3) {
			$c_bank = isset($_POST['c_bank_online']) ? $_POST['c_bank_online'] : "";
		}
	
		if (!empty($_POST['c_tran_type_single'])) {
			$c_tran_type = $_POST['c_tran_type_single'];
		} elseif (!empty($_POST['c_tran_type_car'])) {
			$c_tran_type = $_POST['atap_val'];
		} elseif (!empty($_POST['c_car_type'])) {
			$c_tran_type = $_POST['c_car_type'];
	
			$tran_type = trim(pg_escape_string($c_tran_type));
	
			$check_type_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE LOWER(c_payment_type) = LOWER('$tran_type')";
			$check_type_result = odbc_exec($conn, $check_type_query);
	
			if (!$check_type_result) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error executing query.";
				echo json_encode($resp);
				return;
			}
	
			$type_exists = odbc_fetch_array($check_type_result)['count'];
	
			if ($type_exists == 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "CAR type does not exist.";
				echo json_encode($resp);
				return;
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "Transaction type or car type is required.";
			echo json_encode($resp);
			return;
		}
	
		if (empty($id)) {
			$check_car_no_query = "SELECT COUNT(*) AS count FROM t_car_payment WHERE c_car_no = '$c_car_no'";
			$check_car_no_result = odbc_exec($this->conn, $check_car_no_query);

			if ($check_car_no_result) {
				$row = odbc_fetch_array($check_car_no_result);
				$car_no_exists = $row['count'];

				if ($car_no_exists > 0) {
					$resp['status'] = 'failed';
					$resp['msg'] = "Car number '$c_car_no' already exists.";
					echo json_encode($resp);
					return;
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error checking car number.";
				echo json_encode($resp);
				return;
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

		/* Sa $c_atap_no if empty sya 0 yung nilalaman dito sa $values -dhendhen */
	
		$data = "id, c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount, c_encoded_by, c_tran_date, c_tran_updated, c_mop, c_bank, c_check_no, c_remarks, c_atap_no";

		$values = "'$maxId', '$c_account_no', '$c_tran_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$c_mop', '$c_bank', '$c_check_no', '$c_remarks', " . (!empty($c_atap_no) ? "'$c_atap_no'" : 0);

// 		$values = "'$maxId', '$c_account_no', '$c_tran_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$c_mop', '$c_bank', '$c_check_no', '$c_remarks', '" . (!empty($c_atap_no) ? $c_atap_no : '0') . "'";

	
		$resp = array();
	
		if (empty($id)) {
			$this->car_logs('Car Management', "ADDED - CAR#$c_car_no");
			$insert = "INSERT INTO t_car_payment ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {

				/* may problem dito dhen */

				if (!empty($atap_id)) {
					$update_tran_type = "UPDATE t_atap_items SET atap_status = 1, c_car_no ='$c_car_no' WHERE id = '$atap_id'";
					$update_tran_type_result = odbc_exec($this->conn, $update_tran_type);
					if (!$update_tran_type_result) {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($this->conn);
					}
				}
	
				if (!empty($c_atap_no)) {
					$check_items = "SELECT COUNT(*) AS count_items FROM t_atap_items WHERE c_atap_no = '$c_atap_no' AND atap_status = 0";
					$check_items_result = odbc_exec($this->conn, $check_items);
	
					if ($check_items_result) {
						$row = odbc_fetch_array($check_items_result);
						$count_items = $row['count_items'];
			
						$current_car_no_query = "SELECT c_car_no FROM t_atap WHERE c_atap_no = '$c_atap_no'";
						$current_car_no_result = odbc_exec($this->conn, $current_car_no_query);
					
						if ($current_car_no_result) {
							$row_car = odbc_fetch_array($current_car_no_result);
							$current_car_no = $row_car['c_car_no'];
					
							$car_no_array = explode(',', $current_car_no); 
							if (!in_array($c_car_no, $car_no_array)) { 
								$new_car_no = $current_car_no ? $current_car_no . ',' . $c_car_no : $c_car_no;
					
								if ($count_items > 0) {
									$update_atap = "UPDATE t_atap SET status = 2, c_car_no = '$new_car_no' WHERE c_atap_no = '$c_atap_no'";
								} else {
									$update_atap = "UPDATE t_atap SET status = 1, c_car_no = '$new_car_no' WHERE c_atap_no = '$c_atap_no'";
								}
								$update = odbc_exec($this->conn, $update_atap);
							} else {
								$update = true; 
							}
						} else {
							$update = false;
						}
					} else {
						$update = false;
					}
					
				} else {
					$update = true;
				}
	
				if ($update) {
					$resp['status'] = 'success';
					$resp['msg'] = "New car payment successfully saved.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_car_payment SET 
						c_car_type = '$c_tran_type',
						c_car_no = '$c_car_no',
						c_car_paydate = '$c_car_paydate',
						c_car_amount = '$c_car_amount',
						c_tran_updated = '$c_tran_date',
						c_mop = '$c_mop',
						c_bank = '$c_bank',
						c_check_no = '$c_check_no',
						c_remarks = '$c_remarks'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$this->car_logs('Car Management', "UPDATED - CAR#$c_car_no");
				if (!empty($atap_id)) {
					$update_tran_type = "UPDATE t_atap_items SET atap_status = 1 WHERE id = '$atap_id'";
					$update_tran_type_result = odbc_exec($this->conn, $update_tran_type);
					if (!$update_tran_type_result) {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($this->conn);
					}
				}
	
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
		$conn = $this->conn;
		$c_car_amount = str_replace(',', '', $c_car_amount);
		$atap_id = $_POST['atap_id'];
		
		if ($c_check_no == '' || $c_check_no == null){
			$c_check_no = $c_ref_no;
		}
		
		/* Nag add lang me here -DhenDwen */
		if ($c_mop == 1) {
			$c_bank = "";
		} elseif ($c_mop == 2) {
			$c_bank = isset($_POST['c_bank_check']) ? $_POST['c_bank_check'] : "";
		} elseif ($c_mop == 3) {
			$c_bank = isset($_POST['c_bank_online']) ? $_POST['c_bank_online'] : "";
		}

		if (!empty($_POST['c_tran_type_single'])) {
			$c_tran_type = $_POST['c_tran_type_single'];
		} elseif (!empty($_POST['c_tran_type'])) {
			$c_tran_type = $_POST['atap_val'];
		} elseif (!empty($_POST['c_car_type'])) {
			$c_tran_type = $_POST['c_car_type'];
	
			$tran_type = trim(pg_escape_string($c_tran_type));
	
			$check_type_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE LOWER(c_payment_type) = LOWER('$tran_type')";
			$check_type_result = odbc_exec($conn, $check_type_query);
	
			if (!$check_type_result) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error executing query.";
				echo json_encode($resp);
				return;
			}
	
			$type_exists = odbc_fetch_array($check_type_result)['count'];
	
			if ($type_exists == 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "CAR type does not exist.";
				echo json_encode($resp);
				return;
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "Transaction type or car type is required.";
			echo json_encode($resp);
			return;
		}
		
		if (empty($id)) {
			$check_car_no_query = "SELECT COUNT(*) AS count FROM t_car_payment WHERE c_car_no = '$c_car_no'";
			$check_car_no_result = odbc_exec($this->conn, $check_car_no_query);

			if ($check_car_no_result) {
				$row = odbc_fetch_array($check_car_no_result);
				$car_no_exists = $row['count'];

				if ($car_no_exists > 0) {
					$resp['status'] = 'failed';
					$resp['msg'] = "Car number '$c_car_no' already exists.";
					echo json_encode($resp);
					return;
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error checking car number.";
				echo json_encode($resp);
				return;
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

		/* Sa $c_atap_no if empty sya 0 yung ilalaman dito sa $values1 -dhendhen */

		$c_account_no = '';
		$data1 = "id, c_account_no, c_car_type, c_car_no, c_car_paydate, c_car_amount, c_encoded_by, c_tran_date, c_tran_updated, c_mop, c_bank, c_check_no, c_remarks, c_atap_no";

		$values1 = "'$maxId', '$c_account_no', '$c_tran_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$c_mop', '$c_bank', '$c_check_no', '$c_remarks'," . (!empty($c_atap_no) ? "'$c_atap_no'" : 0);

// 		$values1 = "'$maxId', '$c_account_no', '$c_tran_type', '$c_car_no', '$c_car_paydate', '$c_car_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$c_mop', '$c_bank', '$c_check_no', '$c_remarks', '" . (!empty($c_atap_no) ? $c_atap_no : '0') . "'";

		$resp = array();
	
		if (empty($id)) {

			$insert = "INSERT INTO t_other_car_payment ($data) VALUES ($values)";
			$insert1 = "INSERT INTO t_car_payment ($data1) VALUES ($values1)";
			$save = odbc_exec($this->conn, $insert);
			$save1 = odbc_exec($this->conn, $insert1);
	
			if ($save && $save1) {
				if (!empty($atap_id)) {
					$update_tran_type = "UPDATE t_atap_items SET atap_status = 1, c_car_no ='$c_car_no' WHERE id = '$atap_id'";
					$update_tran_type_result = odbc_exec($this->conn, $update_tran_type);
					if (!$update_tran_type_result) {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($this->conn);
					}
				}
				if (!empty($c_atap_no)) {
					$check_items = "SELECT COUNT(*) AS count_items FROM t_atap_items WHERE c_atap_no = '$c_atap_no' AND atap_status = 0";
					$check_items_result = odbc_exec($this->conn, $check_items);
				
					if ($check_items_result) {
						$row = odbc_fetch_array($check_items_result);
						$count_items = $row['count_items'];

						$current_car_no_query = "SELECT c_car_no FROM t_atap WHERE c_atap_no = '$c_atap_no'";
						$current_car_no_result = odbc_exec($this->conn, $current_car_no_query);
				
						if ($current_car_no_result) {
							$row_car = odbc_fetch_array($current_car_no_result);
							$current_car_no = $row_car['c_car_no'];
					
							$car_no_array = explode(',', $current_car_no); 
							if (!in_array($c_car_no, $car_no_array)) { 
								$new_car_no = $current_car_no ? $current_car_no . ',' . $c_car_no : $c_car_no;

								if ($count_items > 0) {
									$update_atap = "UPDATE t_atap SET status = 2, c_car_no = '$new_car_no' WHERE c_atap_no = '$c_atap_no'";
								} else {
								
									$update_atap = "UPDATE t_atap SET status = 1, c_car_no = '$new_car_no' WHERE c_atap_no = '$c_atap_no'";
								}
						
								$update = odbc_exec($this->conn, $update_atap);
							} else {
								$update = false;
							}
						} else {
							$update = true;
						}
					} else {
						$update = false;
					}
					
				} else {
					$update = true;
				}
				
				if ($update) {
					$this->car_logs('Car Management', "ADDED - CAR#$c_car_no");
					$resp['status'] = 'success';
					$resp['msg'] = "New car payment successfully saved.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			}else {
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
						c_car_type = '$c_tran_type',
						c_car_no = '$c_car_no',
						c_car_paydate = '$c_car_paydate',
						c_car_amount = '$c_car_amount',
						c_tran_updated = '$c_tran_date',
						c_mop = '$c_mop',
						c_bank = '$c_bank',
						c_check_no = '$c_check_no',
						c_remarks = '$c_remarks'
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

	function save_other_or_payment() {
		extract($_POST);
		$conn = $this->conn;
		$c_or_amount = str_replace(',', '', $c_or_amount);
		// $atap_id = $_POST['atap_id_or'];
		// $c_tran_type = $_POST['atap_val'];
		
		$atap_id = $_POST['c_atap_no_or'];
		$atap_val = $_POST['atap_id_or'];


		if ($c_check_no == '' || $c_check_no == null){
			$c_check_no = $c_ref_no;
		}
		
		/* Nag add lang me here -DhenDwen */
		if ($c_mop_or == 1) {
			$c_bank = "";
		} elseif ($c_mop_or == 2) {
			$c_bank = isset($_POST['c_bank_check_or']) ? $_POST['c_bank_check_or'] : "";
		} elseif ($c_mop_or == 3) {
			$c_bank = isset($_POST['c_bank_online_or']) ? $_POST['c_bank_online_or'] : "";
		}

		if (!empty($_POST['c_tran_type_single_or'])) {
			$c_tran_type = $_POST['c_tran_type_single_or'];
		} elseif (!empty($_POST['c_tran_type_or'])) {
			$c_tran_type = $_POST['atap_val_or'];
		} elseif (!empty($_POST['c_or_type'])) {
			$c_tran_type = $_POST['c_or_type'];

			$tran_type = trim(pg_escape_string($c_tran_type));
	
			$check_type_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE LOWER(c_payment_type) = LOWER('$tran_type')";
			$check_type_result = odbc_exec($conn, $check_type_query);
	
			if (!$check_type_result) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error executing query.";
				echo json_encode($resp);
				return;
			}
	
			$type_exists = odbc_fetch_array($check_type_result)['count'];
	
			if ($type_exists == 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "OR type does not exist.";
				echo json_encode($resp);
				return;
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "Transaction type or car type is required.";
			echo json_encode($resp);
			return;
		}
	
		if (empty($id)) {
			$check_or_no_query = "SELECT COUNT(*) AS count FROM t_or_payment WHERE c_or_no = '$c_or_no'";
			$check_or_no_result = odbc_exec($this->conn, $check_or_no_query);

			if ($check_or_no_result) {
				$row = odbc_fetch_array($check_or_no_result);
				$or_no_exists = $row['count'];

				if ($or_no_exists > 0) {
					$resp['status'] = 'failed';
					$resp['msg'] = "OR number '$c_or_no' already exists.";
					echo json_encode($resp);
					return;
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error checking OR number.";
				echo json_encode($resp);
				return;
			}
		}

		$maxIdQuery = "SELECT MAX(id) AS max_id FROM t_or_payment";
		$maxIdResult = odbc_exec($this->conn, $maxIdQuery);
	
		if ($maxIdResult) {
			$row = odbc_fetch_array($maxIdResult);
			$maxId = $row['max_id'] + 1; 
		} else {
			$maxId = 1; 
			error_log("Failed to retrieve max ID: " . odbc_errormsg($this->conn));
		}
	
		$data = "id, c_or_no, c_name, c_phase, c_block, c_lot";
		$values = "'$maxId', '$c_or_no', '$c_name', '$c_phase', '$c_block', '$c_lot'";

		$c_account_no = '';
		$data1 = "id, c_account_no, c_or_type, c_or_no, c_or_paydate, c_or_amount, c_encoded_by, c_tran_date, c_tran_updated, c_mop, c_bank, c_check_no, c_remarks, c_atap_no";
		$values1 = "'$maxId', '$c_account_no', '$c_tran_type', '$c_or_no', '$c_or_paydate', '$c_or_amount', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$c_mop_or', '$c_bank', '$c_check_no', '$c_remarks','" . (!empty($atap_id) ? $atap_id : '0') . "'";

	
		$resp = array();
	
		if (empty($id)) {

			$insert = "INSERT INTO t_other_or_payment ($data) VALUES ($values)";
			$insert1 = "INSERT INTO t_or_payment ($data1) VALUES ($values1)";
			$save = odbc_exec($this->conn, $insert);
			$save1 = odbc_exec($this->conn, $insert1);
	
			if ($save && $save1) {
				if (!empty($atap_id)) {
					$update_tran_type = "UPDATE t_atap_items SET atap_status = 1, c_car_no ='$c_or_no' WHERE id = '$atap_val'";
					$update_tran_type_result = odbc_exec($this->conn, $update_tran_type);
					if (!$update_tran_type_result) {
						$resp['status'] = 'failed';
						$resp['err'] = odbc_errormsg($this->conn);
					}
				}
				// if (!empty($c_atap_no)) {
				// 	$check_items = "SELECT COUNT(*) AS count_items FROM t_atap_items WHERE c_atap_no = '$c_atap_no' AND atap_status = 0";
				// 	$check_items_result = odbc_exec($this->conn, $check_items);
				
				// 	if ($check_items_result) {
				// 		$row = odbc_fetch_array($check_items_result);
				// 		$count_items = $row['count_items'];
				
				// 		if ($count_items > 0) {
				// 			$update_atap = "UPDATE t_atap SET status = 2 WHERE c_atap_no = '$c_atap_no'";
				// 		} else {
						
				// 			$update_atap = "UPDATE t_atap SET status = 1 WHERE c_atap_no = '$c_atap_no'";
				// 		}
				
				// 		$update = odbc_exec($this->conn, $update_atap);
				// 	} else {
				// 		$update = false;
				// 	}
				// } else {
				// 	$update = true;
				// }
				if (!empty($atap_id)) {
					$check_items = "SELECT COUNT(*) AS count_items FROM t_atap_items WHERE c_atap_no = '$atap_id' AND atap_status = 0";
					$check_items_result = odbc_exec($this->conn, $check_items);
	
					if ($check_items_result) {
						$row = odbc_fetch_array($check_items_result);
						$count_items = $row['count_items'];
			
						$current_or_no_query = "SELECT c_car_no FROM t_atap WHERE c_atap_no = '$atap_id'";
						$current_or_no_result = odbc_exec($this->conn, $current_or_no_query);
					
						if ($current_or_no_result) {
							$row_car = odbc_fetch_array($current_or_no_result);
							$current_or_no = $row_car['c_car_no'];
					
							$or_no_array = explode(',', $current_or_no); 
							if (!in_array($c_or_no, $or_no_array)) { 
								$new_or_no = $current_or_no ? $current_or_no . ',' . $c_or_no : $c_or_no;
					
								if ($count_items > 0) {
									$update_atap = "UPDATE t_atap SET status = 2, c_car_no = '$new_or_no' WHERE c_atap_no = '$atap_id'";
								} else {
									$update_atap = "UPDATE t_atap SET status = 1, c_car_no = '$new_or_no' WHERE c_atap_no = '$atap_id'";
								}
								$update = odbc_exec($this->conn, $update_atap);
							} else {
								$update = true; 
							}
						} else {
							$update = false;
						}
					} else {
						$update = false;
					}
					
				} else {
					$update = true;
				}
				if ($update) {
					$this->car_logs('OR Management', "ADDED - OR#$c_or_no");
					$resp['status'] = 'success';
					$resp['msg'] = "New OR payment successfully saved.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = odbc_errormsg($this->conn);
				}
			}else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else { 
			$update = "UPDATE t_other_or_payment SET 
						c_or_no = '$c_or_no',
						c_name = '$c_name',
						c_phase = '$c_phase',
						c_block = '$c_block',
						c_lot = '$c_lot'
					  WHERE id = '$id'";
			$update1 = "UPDATE t_or_payment SET 
						c_or_type = '$c_tran_type',
						c_or_no = '$c_or_no',
						c_or_paydate = '$c_or_paydate',
						c_or_amount = '$c_or_amount',
						c_tran_updated = '$c_tran_date',
						c_mop = '$c_mop_or',
						c_bank = '$c_bank',
						c_check_no = '$c_check_no',
						c_remarks = '$c_remarks'
					  WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
			$save1 = odbc_exec($this->conn, $update1);
	
			if ($save && $save1) {
				$this->car_logs('OR Management', "UPDATED - OR#$c_or_no");
				$resp['status'] = 'success';
				$resp['msg'] = "OR payment record successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
	
		echo json_encode($resp);
	}
	
	function save_car_type() {
		extract($_POST);
	
		$data = "c_payment_type,status,payment_status,groupings";
		$values = "'$c_payment_type','0','$payment_status','$groupings'";
		$resp = array();
	
		if (empty($id)) {
			$check_car_exist = "SELECT * FROM t_car_type WHERE c_payment_type = '$c_payment_type' and payment_status = '$payment_status'";
			$result = odbc_exec($this->conn, $check_car_exist);
	
			if (odbc_num_rows($result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "CAR type already exists.";
				echo json_encode($resp);
				return;
			}
			
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
			$check_car_exist = "SELECT * FROM t_car_type WHERE c_payment_type = '$c_payment_type' and payment_status = '$payment_status' and id <> '$id'";
			$result = odbc_exec($this->conn, $check_car_exist);
	
			if (odbc_num_rows($result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "CAR type already exists.";
				echo json_encode($resp);
				return;
			}

			$update = "UPDATE t_car_type SET 
						c_payment_type = '$c_payment_type',
						status = '$status',
						payment_status = '$payment_status',
						groupings = '$groupings'
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

	function save_atap_payment() {
		extract($_POST);
		$conn = $this->conn;

		$get_max_query = "SELECT MAX(c_atap_no) AS max_atap_no FROM t_atap";
		$results = odbc_exec($conn, $get_max_query);
		if ($row = odbc_fetch_array($results)) {
			$max_atap_no = $row['max_atap_no'];
			$new_atap_no = $max_atap_no + 1;
		} else {
			$new_atap_no = 1;
		}
	
		$prev_c_atap_no = addslashes($c_atap_no);
		$c_account_no = isset($c_account_no) ? addslashes($c_account_no) : '';
		$c_atap_no = addslashes($new_atap_no);
		$c_encoded_by = addslashes($c_encoded_by);
		$c_tran_date = addslashes($c_tran_date);
		$atap_remarks = pg_escape_string($atap_remarks);
		$approval_status = isset($approval_status) ? (int)$approval_status : 0;
		$approver = isset($approver) ? pg_escape_string($approver) : '';
	
		if ($approval_status == 1) {
			$approver = '';
		}
	
		if (empty($id)) {
			$check_atap_no_query = "SELECT COUNT(*) AS count FROM t_atap WHERE c_tran_date = '$c_tran_date'";
			$check_atap_no_result = odbc_exec($this->conn, $check_atap_no_query);

			if ($check_atap_no_result) {
				$row = odbc_fetch_array($check_atap_no_result);
				$atap_no_exists = $row['count'];

				if ($atap_no_exists > 0) {
					$resp['status'] = 'failed';
					$resp['msg'] = "ATAP number '$c_atap_no' already exists.";
					echo json_encode($resp);
					return;
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error checking atap number.";
				echo json_encode($resp);
				return;
			}
		}

		foreach ($transaction_type as $key => $tran_type) {
			$tran_type = pg_escape_string($tran_type);
			$check_type_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE c_payment_type = '$tran_type'";
			$check_type_result = odbc_exec($conn, $check_type_query);
			$type_exists = odbc_fetch_array($check_type_result)['count'];
	
			if ($type_exists == 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "CAR type not exist.";
				echo json_encode($resp);
				return; 
			}
		}
	
		
		$data = "c_account_no, c_atap_no, c_encoded_by, c_tran_date, c_tran_updated, atap_remarks, approval_status, approver";
		$values = "'$c_account_no', '$c_atap_no', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$atap_remarks', '$approval_status', '$approver'";
		$resp = array();
	
		if (empty($id)) {
			$insert_atap = "INSERT INTO t_atap ($data) VALUES ($values)";
			$save_atap = odbc_exec($conn, $insert_atap);
	
			if ($save_atap) {
				$atap_id = odbc_exec($conn, "SELECT LASTVAL() AS id");
				$atap_id = odbc_result($atap_id, 'id');

				foreach ($transaction_type as $key => $tran_type) {
					$tran_type = pg_escape_string($tran_type);
					$atap_amount = addslashes($transaction_amount[$key]);
	
					$insert_item = "INSERT INTO t_atap_items (c_atap_no, c_tran_type, c_atap_amount) VALUES ('$c_atap_no', '$tran_type', '$atap_amount')";
					odbc_exec($conn, $insert_item);
				}
	
				$this->car_logs('Car Type Management - ATAP', "ADDED - $atap_id");
				$resp['status'] = 'success';
				$resp['msg'] = "New ATAP successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($conn);
			}
	
		} else {
			$update_atap = "UPDATE t_atap SET 
							c_tran_updated = '$c_tran_date',
							atap_remarks = '$atap_remarks',
							approval_status = '$approval_status',
							approver = '$approver'
							WHERE c_atap_no = '$prev_c_atap_no'";
			$save_atap = odbc_exec($conn, $update_atap);
	
			if ($save_atap) {
				$delete_items = "DELETE FROM t_atap_items WHERE c_atap_no = '$prev_c_atap_no'";
				odbc_exec($conn, $delete_items);

				foreach ($transaction_type as $key => $tran_type) {
					$tran_type = addslashes($tran_type);
					$atap_amount = addslashes($transaction_amount[$key]);
	
					$insert_item = "INSERT INTO t_atap_items (c_atap_no, c_tran_type, c_atap_amount) VALUES ('$prev_c_atap_no', '$tran_type', '$atap_amount')";
					odbc_exec($conn, $insert_item);
				}
	
				$this->car_logs('Car Type Management - ATAP', "UPDATED - $id");
				$resp['status'] = 'success';
				$resp['msg'] = "ATAP successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($conn);
			}
		}
	
		echo json_encode($resp);
	}
	
	
	function save_other_atap_payment() {
		extract($_POST);
		$conn = $this->conn;
	
		$get_max_query = "SELECT MAX(c_atap_no) AS max_atap_no FROM t_atap";
		$results = odbc_exec($conn, $get_max_query);
		if ($row = odbc_fetch_array($results)) {
			$max_atap_no = $row['max_atap_no'];
			$new_atap_no = $max_atap_no + 1;
		} else {
			$new_atap_no = 1;
		}
	
		$prev_c_atap_no = addslashes($c_atap_no);
		$c_atap_no = addslashes($new_atap_no);
		$c_name = addslashes($c_name);
		$c_phase = addslashes($c_phase);
		$c_block = addslashes($c_block);
		$c_lot = addslashes($c_lot);
		$atap_remarks = pg_escape_string($atap_remarks);
		$c_encoded_by = addslashes($c_encoded_by);
		$c_tran_date = addslashes($c_tran_date);
		$approval_status = isset($approval_status) ? (int)$approval_status : 0; 
		$approver = isset($approver) ? pg_escape_string($approver) : ''; 
	
		if ($approval_status == 1){
			$approver = '';
		}
		
		if (empty($id)) {
			$check_atap_no_query = "SELECT COUNT(*) AS count FROM t_atap WHERE c_tran_date = '$c_tran_date'";
			$check_atap_no_result = odbc_exec($this->conn, $check_atap_no_query);

			if ($check_atap_no_result) {
				$row = odbc_fetch_array($check_atap_no_result);
				$atap_no_exists = $row['count'];

				if ($atap_no_exists > 0) {
					$resp['status'] = 'failed';
					$resp['msg'] = "ATAP number '$c_atap_no' already exists.";
					echo json_encode($resp);
					return;
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error checking atap number.";
				echo json_encode($resp);
				return;
			}
		}
		
		foreach ($transaction_type as $key => $tran_type) {
			$tran_type = pg_escape_string($tran_type);
			$check_type_query = "SELECT COUNT(*) AS count FROM t_car_type WHERE c_payment_type = '$tran_type'";
			$check_type_result = odbc_exec($conn, $check_type_query);
			$type_exists = odbc_fetch_array($check_type_result)['count'];
	
			if ($type_exists == 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "CAR type not exist.";
				echo json_encode($resp);
				return; 
			}
		}
	
		$data = "c_atap_no, c_name, c_phase, c_block, c_lot";
		$values = "'$c_atap_no', '$c_name', '$c_phase', '$c_block', '$c_lot'";
		
		$c_account_no = '';
		$data1 = "c_account_no, c_atap_no, c_encoded_by, c_tran_date, c_tran_updated, atap_remarks, approval_status, approver";
		$values1 = "'$c_account_no','$c_atap_no', '$c_encoded_by', '$c_tran_date', '$c_tran_date', '$atap_remarks', '$approval_status', '$approver'";
		$resp = array();
		
		if (empty($id)) {
			$insert_atap = "INSERT INTO t_other_atap ($data) VALUES ($values)";
			$insert_atap1 = "INSERT INTO t_atap ($data1) VALUES ($values1)";
			$save_atap = odbc_exec($conn, $insert_atap);
			$save_atap1 = odbc_exec($conn, $insert_atap1);
	
			if ($save_atap && $save_atap1) {
				$atap_id = odbc_exec($conn, "SELECT @@IDENTITY AS id");
				$atap_id = odbc_result($atap_id, 'id');
	
				foreach ($transaction_type as $key => $tran_type) {
					$tran_type = addslashes($tran_type);
					$atap_amount = addslashes($transaction_amount[$key]);
					$insert_item = "INSERT INTO t_atap_items (c_atap_no, c_tran_type, c_atap_amount) VALUES ('$c_atap_no', '$tran_type', '$atap_amount')";
					odbc_exec($conn, $insert_item);
				}
	
				$this->car_logs('Car Management - ATAP', "ADDED - $atap_id");
				$resp['status'] = 'success';
				$resp['msg'] = "New ATAP successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($conn);
			}
		} else {
			$update_atap = "UPDATE t_other_atap SET 
							c_name = '$c_name',
							c_phase = '$c_phase',
							c_block = '$c_block',
							c_lot = '$c_lot'
							WHERE c_atap_no = '$prev_c_atap_no'";
			$update_atap1 = "UPDATE t_atap SET 
							 c_tran_updated = '$c_tran_date',
							 atap_remarks = '$atap_remarks',
							 approval_status = '$approval_status',
							 approver = '$approver'
							 WHERE c_atap_no = '$prev_c_atap_no'";
			$save_atap = odbc_exec($conn, $update_atap);
			$save_atap1 = odbc_exec($conn, $update_atap1);
	
			if ($save_atap && $save_atap1) {
				$delete_items = "DELETE FROM t_atap_items WHERE c_atap_no = '$prev_c_atap_no'";
				odbc_exec($conn, $delete_items);
	
				foreach ($transaction_type as $key => $tran_type) {
					$tran_type = addslashes($tran_type);
					$atap_amount = addslashes($transaction_amount[$key]);
					$insert_item = "INSERT INTO t_atap_items (c_atap_no, c_tran_type, c_atap_amount) VALUES ('$prev_c_atap_no', '$tran_type', '$atap_amount')";
					odbc_exec($conn, $insert_item);
				}
	
				$this->car_logs('Car Type Management - ATAP', "UPDATED - $id");
				$resp['status'] = 'success';
				$resp['msg'] = "ATAP successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($conn);
			}
		}
	
		echo json_encode($resp);
	}
	
	
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
    
	function save_sr() {
		extract($_POST);
	
		
		$data = "date_created, c_time, requestor, nature_of_request, notes, assigned_to, c_status";
		$values = "'$date_created', '$c_time', '$requestor', '$nature_of_request','$notes','$assigned_to','$c_status'";
		
		$resp = array();
	
		if (empty($id)) {
			$insert = "INSERT INTO t_service_requests ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				//$this->car_logs('Service Requests', "Created - $id");
				$resp['status'] = 'success';
				$resp['msg'] = "New service request created successfully.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_service_requests SET 
						date_created = '$date_created',
						c_time = '$c_time',
						requestor = '$requestor',
						nature_of_request = '$nature_of_request',
						notes = '$notes',
						assigned_to = '$assigned_to',
						c_status = '$c_status'
					WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				//$this->car_logs('Tenant Accounts', "UPDATED - $tenant_acc_no");
				$resp['status'] = 'success';
				$resp['msg'] = "Service request successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
		echo json_encode($resp);
	}

	function save_tenant() {
		extract($_POST);
	
		$data = "c_account_no, c_last_name, c_first_name, c_middle_name, c_address, c_city_prov, c_zip_code, c_tel_no, c_lot_area, c_price_sqm, c_mobile_no, c_email, c_civil_status, c_birthday, c_sex, c_employment_status, c_remarks, c_phase, c_block, c_lot, c_encoded_by";
		$values = "'$tenant_acc_no', '$tenant_lname', '$tenant_fname', '$tenant_mname','$tenant_address','$tenant_city_prov','$tenant_zip','$tenant_tel','$tenant_area','$tenant_sqm','$tenant_mobile','$tenant_email','$tenant_civil','$tenant_birthday','$tenant_gender','$tenant_emp_status','$tenant_remarks', '$c_phase','$c_block','$c_lot','$c_encoded_by'";
	
		$resp = array();
	
		if (empty($id)) {
			$insert = "INSERT INTO t_tenant_accounts ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$this->car_logs('Tenant Accounts', "ADDED - $tenant_acc_no");
				$resp['status'] = 'success';
				$resp['msg'] = "New tenant account successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$update = "UPDATE t_tenant_accounts SET 
						c_account_no = '$tenant_acc_no',
						c_last_name = '$tenant_lname',
						c_first_name = '$tenant_fname',
						c_middle_name = '$tenant_mname',
						c_address = '$tenant_address',
						c_city_prov = '$tenant_city_prov',
						c_zip_code = '$tenant_zip',
						c_tel_no = '$tenant_tel',
						c_mobile_no = '$tenant_mobile',
						c_email = '$tenant_email',
						c_civil_status = '$tenant_civil',
						c_birthday = '$tenant_birthday',
						c_sex = '$tenant_gender',
						c_employment_status = '$tenant_emp_status',
						c_remarks = '$tenant_remarks'
					WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				//$this->car_logs('Tenant Accounts', "UPDATED - $tenant_acc_no");
				$resp['status'] = 'success';
				$resp['msg'] = "Tenant account successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
		echo json_encode($resp);
	}

	
	function save_car_check() {
		extract($_POST);
	
		$data = "c_bank_type, status, c_name";
		$values = "'$c_bank_type', '$status', '$c_name'";
	
		$resp = array();
	
		if (empty($id)) {
			$check_bank = "SELECT * FROM t_car_check WHERE c_bank_type = '$c_bank_type'";
			$result_check = odbc_exec($this->conn, $check_bank);

			odbc_fetch_row($result_check);
			if (odbc_num_rows($result_check) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Check bank already exists.";
				echo json_encode($resp);
				return;
			}

			$insert = "INSERT INTO t_car_check ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$this->car_logs('Car Check Bank', "ADDED - $c_bank_type - $c_name");
				$resp['status'] = 'success';
				$resp['msg'] = "New check bank successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$check_bank = "SELECT * FROM t_car_check WHERE c_bank_type = '$c_bank_type' AND id <> '$id'";
			$result_check = odbc_exec($this->conn, $check_bank);

			odbc_fetch_row($result_check);
			if (odbc_num_rows($result_check) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Check bank already exists.";
				echo json_encode($resp);
				return;
			}

			$update = "UPDATE t_car_check SET 
						c_bank_type = '$c_bank_type',
						c_name = '$c_name',
						status = '$status'
					WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$this->car_logs('Car Check Bank', "UPDATED - $c_bank_type - $c_name");
				$resp['status'] = 'success';
				$resp['msg'] = "Car check bank successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
		echo json_encode($resp);
	}

	/* function delete_car_check($carTypeId, $carType) {
		$resp = array();
	
		if (isset($carTypeId) && isset($carType)) {
			$sql = "DELETE FROM t_car_check WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($carTypeId)); 
	
				if ($result) {
					$this->car_logs('Car Check Bank', "DELETED - $carType");
					$resp['status'] = 'success';
					$resp['msg'] = "Car check bank successfully deleted.";
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
			$resp['msg'] = 'Check bank not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	} */

	function save_car_online() {
		extract($_POST);

		$data = "c_bank_type, status, c_name";
		$values = "'$c_bank_type', '$status', '$c_name'";
	
		$resp = array();

		if (empty($id)) {
			$check_online = "SELECT * FROM t_car_online WHERE c_bank_type = '$c_bank_type'";
			$result_online = odbc_exec($this->conn, $check_online);

			odbc_fetch_row($result_online);
			if (odbc_num_rows($result_online) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Online bank already exists.";
				echo json_encode($resp);
				return;
			}

			$insert = "INSERT INTO t_car_online ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$this->car_logs('Car Online Bank', "ADDED - $c_bank_type - $c_name");
				$resp['status'] = 'success';
				$resp['msg'] = "New online bank successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$check_online = "SELECT * FROM t_car_online WHERE c_bank_type = '$c_bank_type' AND id <> '$id'";
			$result_online = odbc_exec($this->conn, $check_online);

			odbc_fetch_row($result_online);
			if (odbc_num_rows($result_online) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Online bank already exists.";
				echo json_encode($resp);
				return;
			}

			$update = "UPDATE t_car_online SET 
						c_bank_type = '$c_bank_type',
						c_name = '$c_name',
						status = '$status'
					WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$this->car_logs('Car Online Bank', "UPDATED - $c_bank_type - $c_name");
				$resp['status'] = 'success';
				$resp['msg'] = "Car online bank successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
		echo json_encode($resp);
	}

	/* function delete_car_online($onlineTypeId, $onlineType) {
		$resp = array();
	
		if (isset($onlineTypeId) && isset($onlineType)) {
			$sql = "DELETE FROM t_car_online WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($onlineTypeId)); 
	
				if ($result) {
					$this->car_logs('Car Online Bank', "DELETED - $onlineType");
					$resp['status'] = 'success';
					$resp['msg'] = "Car online bank successfully deleted.";
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
			$resp['msg'] = 'Online bank not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	} */

	function save_my_account() {
		extract($_POST);
		$resp = array();    
	
		$check_query = "SELECT * FROM t_car_users WHERE c_employee_code = '$c_employee_code' AND id != '$id'";
		$check_result = odbc_exec($this->conn, $check_query);
	
		if (odbc_num_rows($check_result) > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Employee code already exists.";
			echo json_encode($resp);
			return;
		}
	
		/* Password lang and need ma update dito den shunga ka */
		/* $update_fields = array(
			"c_employee_code = '$c_employee_code'",
			"c_realname = '$c_realname'",
			"c_department = '$c_department'",
			"c_position = '$c_position'"
		); */
	
		if (!empty($c_password)) {
			$hashed_password = password_hash($c_password, PASSWORD_BCRYPT);
			$update_fields[] = "c_password = '$hashed_password'";
		}

		$update_query = "UPDATE t_car_users SET " . implode(", ", $update_fields) . " WHERE id = '$id'";
		$save = odbc_exec($this->conn, $update_query);
	
		if ($save) {
			$c_employee_code = $c_employee_code;
			$c_realname = $c_realname;
			
			$this->car_logs('My Account', "UPDATE - $c_employee_code - $c_realname - CHANGED PASSWORD");
			
			session_unset();
			session_destroy();
		
			$resp['status'] = 'success';
			$resp['msg'] = "User successfully updated.";
			$resp['logout'] = true;
		}else {
			$resp['status'] = 'failed';
			$resp['err'] = odbc_errormsg($this->conn);
		}
	
		echo json_encode($resp);
	}

	function save_emp_position() {
		extract($_POST);
		$data = "c_position, status";
		$values = "'$c_position', '$status'";
		$resp = array();
	
		if (empty($id)) {
			$check_pos_exist = "SELECT * FROM t_emp_position WHERE c_position = '$c_position'";
			$result_pos = odbc_exec($this->conn, $check_pos_exist);

			odbc_fetch_row($result_pos);
			if (odbc_num_rows($result_pos) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Position already exists.";
				echo json_encode($resp);
				return;
			}

			$insert = "INSERT INTO t_emp_position ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);

			if ($save) {
				$this->car_logs('User Position', "ADDED - $c_position");
				$resp['status'] = 'success';
				$resp['msg'] = "New user position successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$check_pos_exist = "SELECT * FROM t_emp_position WHERE c_position = '$c_position' AND id <> '$id'";
			$result_pos = odbc_exec($this->conn, $check_pos_exist);

			odbc_fetch_row($result_pos);
			if (odbc_num_rows($result_pos) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Position already exists.";
				echo json_encode($resp);
				return;
			}

			$update = "UPDATE t_emp_position SET c_position = '$c_position', status = '$status' WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);

			if ($save) {
				$this->car_logs('User Position', "UPDATED - $c_position");
				$resp['status'] = 'success';
				$resp['msg'] = "User position successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
		echo json_encode($resp);
	}	

	function delete_emp_position($positionId, $positionType) {
		$resp = array();
	
		if (isset($positionId) && isset($positionType)) {
			$sql = "DELETE FROM t_emp_position WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($positionId)); 
	
				if ($result) {
					$this->car_logs('User Position', "DELETED - $positionType");
					$resp['status'] = 'success';
					$resp['msg'] = "User position successfully deleted.";
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
			$resp['msg'] = 'Position not provided.';
		}
		header('Content-Type: application/json');
		echo json_encode($resp);
	}

	function save_emp_department() {
		extract($_POST);
		$resp = array();
	
		if (empty($id)) {
			$check_dep_exist = "SELECT * FROM t_emp_department WHERE c_department = '$c_department'";
			$result = odbc_exec($this->conn, $check_dep_exist);
	
			if (odbc_num_rows($result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Department already exists.";
				echo json_encode($resp);
				return;
			}
	
			$data = "c_department, status";
			$values = "'$c_department', '$status'";
			$insert = "INSERT INTO t_emp_department ($data) VALUES ($values)";
			$save = odbc_exec($this->conn, $insert);
	
			if ($save) {
				$this->car_logs('User Department', "ADDED - $c_department");
				$resp['status'] = 'success';
				$resp['msg'] = "New user department successfully saved.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		} else {
			$check_dep_exist = "SELECT * FROM t_emp_department WHERE c_department = '$c_department' AND id <> '$id'";
			$result = odbc_exec($this->conn, $check_dep_exist);
	
			if (odbc_num_rows($result) > 0) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Department already exists.";
				echo json_encode($resp);
				return;
			}
	
			$update = "UPDATE t_emp_department SET c_department = '$c_department', status = '$status' WHERE id = '$id'";
			$save = odbc_exec($this->conn, $update);
	
			if ($save) {
				$this->car_logs('User Department', "UPDATED - $c_department");
				$resp['status'] = 'success';
				$resp['msg'] = "User department successfully updated.";
			} else {
				$resp['status'] = 'failed';
				$resp['err'] = odbc_errormsg($this->conn);
			}
		}
		echo json_encode($resp);
	}	

	function delete_or($orNo, $atapNo) {
		$resp = array();

		if (empty($orNo)) {
			$resp['status'] = 'failed';
			$resp['msg'] = 'OR No not provided.';
			header('Content-Type: application/json');
			echo json_encode($resp);
			return;
		}

		$sql = "UPDATE t_or_payment SET status = 1 WHERE c_or_no = ?";
		$stmt = odbc_prepare($this->conn, $sql);
	
		if (!$stmt || !@odbc_execute($stmt, array($orNo))) {
			$resp['status'] = 'failed';
			$resp['err'] = "Failed to cancel OR payment: " . odbc_errormsg($this->conn);
			header('Content-Type: application/json');
			echo json_encode($resp);
			return;
		}
	
		$this->car_logs('OR Management', "CANCELLED - CAR#$orNo");
	

		if ($atapNo == 0) {
			$resp['status'] = 'success';
			$resp['msg'] = "OR payment successfully canceled.";
			header('Content-Type: application/json');
			echo json_encode($resp);
			return;
		}
	

		if (!empty($atapNo)) {
			$sql2 = "UPDATE t_atap_items SET atap_status = 0 WHERE c_car_no = ?";
			$stmt2 = odbc_prepare($this->conn, $sql2);
	
			if (!$stmt2 || !@odbc_execute($stmt2, array($orNo))) {
				$resp['status'] = 'failed';
				$resp['err'] = "Failed to update ATAP items status: " . odbc_errormsg($this->conn);
				header('Content-Type: application/json');
				echo json_encode($resp);
				return;
			}

			$sqlGetOrNo = "SELECT c_car_no FROM t_atap WHERE c_atap_no = ?";
			$stmtGetOrNo = odbc_prepare($this->conn, $sqlGetOrNo);
	
			if (!$stmtGetOrNo || !@odbc_execute($stmtGetOrNo, array($atapNo))) {
				$resp['status'] = 'failed';
				$resp['err'] = "Failed to retrieve OR number: " . odbc_errormsg($this->conn);
				header('Content-Type: application/json');
				echo json_encode($resp);
				return;
			}

			$row = odbc_fetch_array($stmtGetOrNo);
			$currentOrNo = $row['c_car_no'];
	
			if ($currentOrNo) {
				$orNoArray = array_filter(explode(',', $currentOrNo), function($car) use ($orNo) {
					return $car != $orNo;
				});
	
				$updatedOrNo = implode(',', $orNoArray);
	
				if (!empty($updatedOrNo)) {
					$sqlUpdateAtap = "UPDATE t_atap SET c_car_no = ?, status = 2 WHERE c_atap_no = ?";
					$stmtUpdateAtap = odbc_prepare($this->conn, $sqlUpdateAtap);
					$resultUpdateAtap = @odbc_execute($stmtUpdateAtap, array($updatedOrNo, $atapNo));
				} else {
					$sqlUpdateAtap = "UPDATE t_atap SET c_car_no = NULL, status = 0 WHERE c_atap_no = ?";
					$stmtUpdateAtap = odbc_prepare($this->conn, $sqlUpdateAtap);
					$resultUpdateAtap = @odbc_execute($stmtUpdateAtap, array($atapNo));
				}
	
				if ($resultUpdateAtap) {
					$resp['status'] = 'success';
					$resp['msg'] = "OR and ATAP status successfully updated.";
				} else {
					$resp['status'] = 'failed';
					$resp['err'] = "Failed to update ATAP status: " . odbc_errormsg($this->conn);
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "No OR number found for the given ATAP number.";
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'ATAP No not provided.';
		}
	
		header('Content-Type: application/json');
		echo json_encode($resp);
	}
	
	// function delete_or($orNo, $atapNo) {
	// 	$resp = array();
		
	// 	if (!empty($orNo)) {
	// 		if ($atapNo == 0) {
	// 			$sql = "UPDATE t_or_payment SET status = 1 WHERE c_or_no = ?";
	// 			$stmt = odbc_prepare($this->conn, $sql);
	
	// 			if ($stmt) {
	// 				$result = @odbc_execute($stmt, array($orNo));
	
	// 				if ($result) {
	// 					$this->car_logs('OR Management', "CANCELLED - CAR#$orNo");
	// 					$resp['status'] = 'success';
	// 					$resp['msg'] = "OR payment successfully canceled.";
	// 				} else {
	// 					$resp['status'] = 'failed';
	// 					$resp['err'] = "Failed to cancel OR payment: " . odbc_errormsg($this->conn);
	// 				}
	// 			} else {
	// 				$resp['status'] = 'failed';
	// 				$resp['err'] = "Failed to prepare OR payment cancellation: " . odbc_errormsg($this->conn);
	// 			}

	// 			header('Content-Type: application/json');
	// 			echo json_encode($resp);
	// 			return;
	// 		}

	// 		if (!empty($atapNo)) {
	// 			$sql = "UPDATE t_or_payment SET status = 1 WHERE c_or_no = ?";
	// 			$stmt = odbc_prepare($this->conn, $sql);
		
	// 			if ($stmt) {
	// 				$result = @odbc_execute($stmt, array($orNo));
		
	// 				if ($result) {
	// 					$this->car_logs('OR Management', "CANCELLED - CAR#$orNo");
		
	// 					$sql2 = "UPDATE t_atap_items SET atap_status = 0 WHERE c_car_no = ?";
	// 					$stmt2 = odbc_prepare($this->conn, $sql2);
		
	// 					if ($stmt2) {
	// 						$result2 = @odbc_execute($stmt2, array($orNo));
		
	// 						if ($result2) {
	// 							$sqlGetOrNo = "SELECT c_car_no FROM t_atap WHERE c_atap_no = ?";
	// 							$stmtGetOrNo = odbc_prepare($this->conn, $sqlGetOrNo);
		
	// 							if ($stmtGetOrNo) {
	// 								$resultGetCarNo = @odbc_execute($stmtGetOrNo, array($atapNo));
		
	// 								if ($resultGetCarNo) {
	// 									$row = odbc_fetch_array($stmtGetOrNo);
	// 									$currentOrNo = $row['c_car_no'];
		
	// 									if ($currentOrNo) {
	// 										$orNoArray = array_filter(explode(',', $currentOrNo), function($car) use ($orNo) {
	// 											return $car != $orNo;
	// 										});
		
	// 										$updatedOrNo = implode(',', $orNoArray);
		
	// 										if (!empty($updatedOrNo)) {
	// 											$sqlUpdateAtap = "UPDATE t_atap SET c_car_no = ?, status = 2 WHERE c_atap_no = ?";
	// 											$stmtUpdateAtap = odbc_prepare($this->conn, $sqlUpdateAtap);
	// 											$resultUpdateAtap = @odbc_execute($stmtUpdateAtap, array($updatedOrNo, $atapNo));
	// 										} else {
	// 											$sqlUpdateAtap = "UPDATE t_atap SET c_car_no = NULL, status = 0 WHERE c_atap_no = ?";
	// 											$stmtUpdateAtap = odbc_prepare($this->conn, $sqlUpdateAtap);
	// 											$resultUpdateAtap = @odbc_execute($stmtUpdateAtap, array($atapNo));
	// 										}
		
	// 										if ($resultUpdateAtap) {
	// 											$resp['status'] = 'success';
	// 											$resp['msg'] = "OR and ATAP status successfully updated.";
	// 										} else {
	// 											$resp['status'] = 'failed';
	// 											$resp['err'] = "Failed to update ATAP status: " . odbc_errormsg($this->conn);
	// 										}
	// 									} else {
	// 										$resp['status'] = 'failed';
	// 										$resp['msg'] = "No OR number found for the given ATAP number.";
	// 									}
	// 								} else {
	// 									$resp['status'] = 'failed';
	// 									$resp['err'] = "Failed to retrieve OR number: " . odbc_errormsg($this->conn);
	// 								}
	// 							} else {
	// 								$resp['status'] = 'failed';
	// 								$resp['err'] = "Failed to prepare OR number retrieval: " . odbc_errormsg($this->conn);
	// 							}
	// 						} else {
	// 							$resp['status'] = 'failed';
	// 							$resp['err'] = "Failed to update ATAP items status: " . odbc_errormsg($this->conn);
	// 						}
	// 					} else {
	// 						$resp['status'] = 'failed';
	// 						$resp['err'] = "Failed to prepare ATAP items update: " . odbc_errormsg($this->conn);
	// 					}
	// 				} else {
	// 					$resp['status'] = 'failed';
	// 					$resp['err'] = "Failed to cancel OR payment: " . odbc_errormsg($this->conn);
	// 				}
	// 			} else {
	// 				$resp['status'] = 'failed';
	// 				$resp['err'] = "Failed to prepare OR payment cancellation: " . odbc_errormsg($this->conn);
	// 			}
	// 		} else {
	// 			$resp['status'] = 'failed';
	// 			$resp['msg'] = 'ATAP No not provided.';
	// 		}
	// 	} else {
	// 		$resp['status'] = 'failed';
	// 		$resp['msg'] = 'OR No not provided.';
	// 	}
	
	// 	header('Content-Type: application/json');
	// 	echo json_encode($resp);
	// }
	function delete_emp_department($departmentId, $departmentType) {
		$resp = array();
	
		if (isset($departmentId) && isset($departmentType)) {
			$sql = "DELETE FROM t_emp_department WHERE id = ?";
			$stmt = odbc_prepare($this->conn, $sql);
	
			if ($stmt) {
				$result = @odbc_execute($stmt, array($departmentId)); 
	
				if ($result) {
					$this->car_logs('User Department', "DELETED - $departmentType");
					$resp['status'] = 'success';
					$resp['msg'] = "User department successfully deleted.";
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
			$resp['msg'] = 'Department not provided.';
		}
	
		header('Content-Type: application/json');
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
	case 'save_or_payment':
        echo $Master->save_or_payment();
        break;
    case 'save_car_payment':
        echo $Master->save_car_payment();
        break;
	case 'save_other_car_payment':
		echo $Master->save_other_car_payment();
		break;
	case 'save_other_or_payment':
		echo $Master->save_other_or_payment();
		break;
	case 'save_car_type':
		echo $Master->save_car_type();
		break;
	case 'save_car_check':
		echo $Master->save_car_check();
		break;
	case 'save_tenant':
		echo $Master->save_tenant();
		break;
	case 'save_sr':
		echo $Master->save_sr();
		break;
	case 'save_car_online':
		echo $Master->save_car_online();
		break;
	case 'save_remarks':
		echo $Master->save_remarks();
		break;
	case 'delete_or':
		if (isset($_POST['orNo']) && isset($_POST['atapNo'])) {
			$response = $Master->delete_or($_POST['orNo'], $_POST['atapNo']);
			echo $response;
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'Required parameters not provided.'));
		}
		break;
	case 'delete_car':
		if (isset($_POST['carNo']) && isset($_POST['atapNo'])) {
			echo $Master->delete_car($_POST['carNo'], $_POST['atapNo']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'Car No or ATAP No not provided.'));
		}
		break;
	case 'delete_car_type':
		if (isset($_POST['carTypeId']) && isset($_POST['carType'])) {
			echo $Master->delete_car_type($_POST['carTypeId'], $_POST['carType']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'Car type not provided.'));
		}
		break;
	case 'delete_tenant':
		if (isset($_POST['tenantId'])) {
			echo $Master->delete_tenant($_POST['tenantId']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'Tenant # not provided.'));
		}
		break;
    case 'save_car_users':
        echo $Master->save_car_users();
        break;
	case 'save_my_account':
		echo $Master->save_my_account();
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
	case 'save_atap_payment':
		echo $Master->save_atap_payment();
		break;
	case 'save_other_atap_payment':
		echo $Master->save_other_atap_payment();
		break;
	case 'delete_atap':
		if (isset($_POST['atapId']) && isset($_POST['atapNo'])) {
			echo $Master->delete_atap($_POST['atapId'], $_POST['atapNo']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'ATAP # not provided.'));
		}
		break;
	case 'approve_atap':
		if (isset($_POST['atapId']) && isset($_POST['atapNo'])) {
			echo $Master->approve_atap($_POST['atapId'], $_POST['atapNo']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'ATAP # not provided.'));
		}
		break;
	case 'disapprove_atap':
		if (isset($_POST['atapId']) && isset($_POST['atapNo'])) {
			echo $Master->disapprove_atap($_POST['atapId'], $_POST['atapNo']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'ATAP # not provided.'));
		}
		break;
	case 'update_items_status':
			echo $Master->update_items_status();
		break;
	case 'save_emp_position':
		echo $Master->save_emp_position();
		break;
	case 'delete_emp_position':
		if (isset($_POST['positionId']) && isset($_POST['positionType'])) {
			echo $Master->delete_emp_position($_POST['positionId'], $_POST['positionType']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'Position not provided.'));
		}
		break;
	case 'save_emp_department':
		echo $Master->save_emp_department();
		break;
	case 'delete_emp_department':
		if (isset($_POST['departmentId']) && isset($_POST['departmentType'])) {
			echo $Master->delete_emp_department($_POST['departmentId'], $_POST['departmentType']);
		} else {
			echo json_encode(array('status' => 'failed', 'msg' => 'Department not provided.'));
		}
		break;
    default:
        break;
}

    