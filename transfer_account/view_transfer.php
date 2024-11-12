<?php
header('Content-Type: application/json');
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accountNo'])) {
        
        $accno = $_POST['accountNo'];
        error_log("Received account number: " . $accno);

        try {
            $query = "SELECT * FROM t_transfer_logs WHERE old_acc = ?";
            $stmt = odbc_prepare($conn, $query);
            odbc_execute($stmt, array($accno));
            $data = [];

            while ($row = odbc_fetch_array($stmt)) {
                $data[] = $row;
            }

            if (!empty($data)) {
                echo json_encode([
                    'status' => 'exists',
                    'data' => $data
                ]);
            } else {
                echo json_encode(['status' => 'not_exists']);
            }
        } catch (Exception $e) {
            error_log("Error occurred: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        error_log("Error: 'accountNo' was not received in POST request");
        echo json_encode(['status' => 'error', 'message' => "'accountNo' was not received"]);
    }
}
?>
