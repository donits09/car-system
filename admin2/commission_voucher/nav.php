<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navigation {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }
        .navigation a {
            display: inline-block;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 5px;
            border-radius: 5px;
            background-color: #555;
            transition: background-color 0.3s ease;
        }
        .navigation a:hover {
            background-color: #0038a5;
        }
        .navigation .home-link {
            background-color: #0038a5;
        }
        .navigation .home-link:hover {
            background-color: #0038a5;
        }
        .navigation a i {
            margin-right: 5px;
        }
    </style>

    <div class="navigation">
        <a class="home-link" href="<?php echo base_url ?>admin2/?page=admin_dash">
            <i class="fas fa-home"></i> Home
        </a>
    </div>
<!--     <a href="<?php echo base_url ?>admin2/?page=commission_voucher/commission">Commission</a>
    <a href="<?php echo base_url ?>admin2/?page=commission_voucher/agent_list">Agent List</a>
    <a href="<?php echo base_url ?>admin2/?page=commission_voucher/comm_voucher">Commission Voucher</a>
    <a href="<?php echo base_url ?>admin2/?page=commission_voucher/new_comm_voucher">New Commission Voucher</a> -->
</div>