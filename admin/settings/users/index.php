<?php
session_start();
require_once('../../../config.php');
include('../../../inc/header.php');
include('../../../inc/navbar.php');

if (!isset($_SESSION['user_group']) || $_SESSION['user_group'] != 1) {
    require_once('../logout.php');
    exit();
}
?>

<div class="container mt-5">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="user-tab" data-toggle="tab" href="#user" role="tab" aria-controls="user" aria-selected="true">User Management</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs" aria-selected="false">User Logs</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
            <?php include('user.php'); ?>
        </div>
        <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="logs-tab">
            <?php include('logs.php'); ?>
        </div>
    </div>
</div>

<?php include('../../../inc/footer.php'); ?>