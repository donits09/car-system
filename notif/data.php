<?php
include('../config.php');

$conn = odbc_connect($dsn, $user, $pass);
if (!$conn) {
    error_log('Database connection failed: ' . odbc_errormsg(), 0);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Failed to connect to database. Please try again later.']);
    exit;
}
function timeAgo($timestamp) {
    $currentTime = time();
    $notificationTime = strtotime($timestamp);
    $timeDiff = $currentTime - $notificationTime;

    if ($timeDiff < 60) {
        return $timeDiff . 's ago';
    } elseif ($timeDiff < 3600) {
        return floor($timeDiff / 60) . 'm ago';
    } elseif ($timeDiff < 86400) {
        return floor($timeDiff / 3600) . 'h ago';
    } else {
        return floor($timeDiff / 86400) . 'd ago';
    }
}
$username = isset($_GET['username']) ? $_GET['username'] : 'Guest';

try {
    $sql = "SELECT * FROM t_car_notif WHERE user_to_be_notified = ? ORDER BY date_created";
    $stmt = odbc_prepare($conn, $sql);
    if (!$stmt) {
        throw new Exception('Failed to prepare the statement: ' . odbc_errormsg($conn));
    }
    
    $result = odbc_execute($stmt, [$username]);
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
