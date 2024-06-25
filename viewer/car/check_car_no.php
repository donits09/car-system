<?php

if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != 4) {
    require_once('../logout.php');
    exit();
}

header('Content-Type: application/json');
include('../../config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_no = $_POST['car_no'];

    $query = "SELECT COUNT(*) AS count FROM t_car_payment WHERE c_car_no = ?";
    $stmt = odbc_prepare($conn, $query);
    odbc_execute($stmt, array($car_no));
    $result = odbc_fetch_array($stmt);

    $exists = $result['count'] > 0;

    echo json_encode(['exists' => $exists]);
}
?>
