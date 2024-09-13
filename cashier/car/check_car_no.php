<?php
header('Content-Type: application/json');
include('../../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $or_no = $_POST['c_or_no'];

    $query = "SELECT COUNT(*) AS count FROM t_or_payment WHERE c_or_no = ?";
    $stmt = odbc_prepare($conn, $query);
    odbc_execute($stmt, array($or_no));  
    $result = odbc_fetch_array($stmt);

    $exists = $result['count'] > 0;

    echo json_encode(['exists' => $exists]);

    odbc_close($conn);
}
?>
