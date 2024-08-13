<?php
include('../config.php');

$conn = odbc_connect($dsn, $user, $pass);
if (!$conn) {
    error_log('Database connection failed: ' . odbc_errormsg(), 0);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to connect to database. Please try again later.']);
    exit;
}

$cGroup = isset($_GET['cGroup']) ? $_GET['cGroup'] : 'Guest';

try {
    $sql = "SELECT * FROM t_car_notif WHERE user_to_be_notified = ? ORDER BY date_created";
    $stmt = odbc_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception('Failed to prepare the statement: ' . odbc_errormsg($conn));
    }
    
    $result = odbc_execute($stmt, [$cGroup]);
    if (!$result) {
        throw new Exception('Failed to execute the statement: ' . odbc_errormsg($conn));
    }

    $notifications = [];
    while ($row = odbc_fetch_array($stmt)) {
        $notifications[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($notifications);
    
} catch (Exception $e) {
    error_log('Query failed: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Query execution failed']);
}
?>
