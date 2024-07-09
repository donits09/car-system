<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .dashboard {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .dashboard .button {
        width: 200px;
        height: 200px;
        margin: 10px;
        background-color: #f0f0f0;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .dashboard .button:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    .dashboard .button i {
        font-size: 48px;
        margin-bottom: 10px;
    }
    .dashboard .button span {
        font-size: 18px;
    }
    .navigation {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #333;
        padding: 10px 0;
        text-align: center;
    }
    .navigation a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        margin: 0 5px;
        border-radius: 5px;
        background-color: #555;
        transition: background-color 0.3s ease;
    }
    .navigation a:hover {
        background-color: #777;
    }
</style>
<section class="content">
<div class="dashboard">
    <div class="button">
        <a href="<?php echo base_url ?>admin2/?page=commission_voucher/commission">
            <i class="fas fa-money-check-alt"></i>
            <span>Commission</span>
        </a>
    </div>
    <div class="button">
        <a href="<?php echo base_url ?>admin2/?page=commission_voucher/agent_list">
            <i class="fas fa-users"></i>
            <span>Agent List</span>
        </a>
    </div>
    <div class="button">
        <a href="<?php echo base_url ?>admin2/?page=commission_voucher/comm_voucher">
            <i class="fas fa-receipt"></i>
            <span>Commission Voucher</span>
        </a>
    </div>
    <div class="button">
        <a href="<?php echo base_url ?>admin2/?page=commission_voucher/new_comm_voucher">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>New Commission Voucher</span>
        </a>
    </div>
</div>
</section>