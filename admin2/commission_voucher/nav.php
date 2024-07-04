<style>
    .nav-tabs .nav-link.active {
        background-color: #74992e;
        color: white;
    }
</style>

<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : 'commission_voucher/comm_voucher';



?>


<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link <?php if ($current_page == 'commission_voucher/comm_voucher') echo 'active'; ?>" href="?page=commission_voucher/comm_voucher">Commission Voucher</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if ($current_page == 'commission_voucher/agent_list') echo 'active'; ?>" href="?page=commission_voucher/agent_list">Agent List</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if ($current_page == 'commission_voucher/summary_report') echo 'active'; ?>" href="?page=commission_voucher/summary_report">Summary Report</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if ($current_page == 'commission_voucher/new_comm_voucher') echo 'active'; ?>" href="?page=commission_voucher/new_comm_voucher">New Commission Voucher</a>
    </li>
</ul>
