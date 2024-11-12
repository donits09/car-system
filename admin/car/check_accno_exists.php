<?php
header('Content-Type: application/json');
include('../../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accno'])) {
        $accno = $_POST['accno'];
        error_log("Received account number: " . $accno);

        try {
            $query = "SELECT old_acc FROM t_transfer_logs WHERE old_acc = ?";
            $stmt = odbc_prepare($conn, $query);
            odbc_execute($stmt, array($accno));
            $result = odbc_fetch_array($stmt);

            if ($result) {
                echo json_encode(['status' => 'exists']);
            } else {
                echo json_encode(['status' => 'not_exists']);
            }
        } catch (Exception $e) {
            error_log("Error occurred: " . $e->getMessage());
            echo json_encode(['status' => 'error']);
        }
    } else {
        error_log("Error: 'accno' was not received in POST request");
        echo json_encode(['status' => 'error', 'message' => "'accno' was not received"]);
    }
}

?>
