

<?php include('nav.php'); ?>


<div class="container">
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'commission_voucher/comm_voucher';
    switch ($page) {
        case 'commission_voucher/comm_voucher':
            include('commission_voucher/comm_voucher.php');
            break;
        case 'commission_voucher/agent_list':
            include('commission_voucher/agent_list.php');
            break;
        case 'commission_voucher/summary_report':
            include('commission_voucher/summary_report.php');
            break;
        case 'commission_voucher/new_commission_voucher':
            include('commission_voucher/new_commission_voucher.php');
            break;
        default:
            include('commission_voucher/comm_voucher.php');
            break;
    }
    ?>
</div>
