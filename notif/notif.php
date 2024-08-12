<?php
include('../config.php');
$conn = odbc_connect($dsn, $user, $pass);
if (!$conn) {
    error_log('Database connection failed: ' . odbc_errormsg(), 0);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to connect to database. Please try again later.']);
    exit;
}

$notif_id = $_GET['notif_id'] ?? null;
var_dump($notif_id); 

if (!$notif_id) {
    echo json_encode(['success' => false, 'message' => 'Notification ID is missing.']);
    exit;
}

try {
    $query = 'UPDATE t_car_notif SET seen_status = 1 WHERE notif_id = ?';

    $stm = odbc_prepare($conn, $query);

    if (odbc_execute($stm, [$notif_id])) {
        echo json_encode(['success' => true, 'message' => 'Notification updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update notification.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

odbc_close($conn);
?>
