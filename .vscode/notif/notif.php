<?php

header('Content-Type: application/json');

$notifications = [
    [
        'message' => 'You have a new message',
        'link' => '#'
    ],
    [
        'message' => 'Your profile was viewed',
        'link' => '#'
    ],
];

echo json_encode(['notifications' => $notifications]);
?>
