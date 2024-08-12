<?php
require('notif_con.php');

$username = $_GET['username'];

$query = 'UPDATE t_car_notif SET seen_status = 1 WHERE user_to_be_notified = :username';
$stm = $pdo->prepare($query);
$stm->bindParam(':usertype', $username);

if ($stm->execute()) {
    $query2 = 'SELECT message AS msg FROM t_car_notif WHERE seen_status = 1 AND user_to_be_notified = :username';
    $stm2 = $pdo->prepare($query2);
    $stm2->bindParam(':username', $username);

    if ($stm2->execute()) {
        $result = $stm2->fetchAll();
        echo json_encode($result);
    }
}
?>
