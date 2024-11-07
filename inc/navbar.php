    
    <!-- FAVICON -->
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url ?>dist/header_files/favicon/alsc-32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url ?>dist/header_files/favicon/alsc-16.png">


<?php 
$c_realname = '';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $get_realname_query = "SELECT c_realname, c_group FROM t_car_users WHERE c_employee_code = ?";
    $stmt = odbc_prepare($conn, $get_realname_query);
    if (odbc_execute($stmt, array($username))) {
        if ($result = odbc_fetch_array($stmt)) {
            $c_realname = $result['c_realname'];
            $c_group = $result['c_group'];
        }
    }
}

function isActive($pages) {
    foreach ($pages as $page) {
        if (strpos($_SERVER['REQUEST_URI'], $page) !== false) {
            return ' active';
        }
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CAR ENCODING</title>
  <link rel="stylesheet" href="<?php echo base_url ?>dist/css/navbar.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body.modal-open {
      overflow: hidden;
    }
    .dropdown-item {
      position: relative;
    }

    .dropdown-item .fas {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-45%);
    }


    .notify {
        position: relative;
        display: flex;
        align-items: center;
    }

    .notify-btn {
        position: relative;
    }

    .icon-button {
        width: 30px; 
        height: 30px;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .icon-button__badge {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: red;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 12px;
    }

    .notify-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        display: none;
        z-index: 1000;
        width: 200px;
    }

    .notify-menu.show {
        display: block;
    }

    .notification-title {
        font-size: 15px;
        color: black;
        padding: 7px;
        background-color: gainsboro;
        margin: 0;
    }

    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .notification-list li {
        padding: 7px;
        font-size: 14px;
        background-color: white;
        color: black;
        cursor: pointer;
    }

    /* .notification-list li:hover {
        background-color: #007bff;
        color: white;
    } */

    .unseen-notification {
        background-color: whitesmoke;
    }

    .seen-notification {
        background-color: white;
    }

  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <input type="hidden" value="<?php echo $c_group; ?>" id="c_group">
  <div class="container">
    <a class="navbar-brand" href="#">
        <img src="<?php echo base_url ?>images/logo.jpg" alt="ALSC Logo"> CASH ACKNOWLEDGEMENT RECEIPT ENCODING
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="background-color:rgba(255,255,255,0.5);">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php if ($c_group == 1) { ?>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item dropdown<?php echo isActive(['/admin/car?page=car_list']) ? ' active' : ''; ?>">
                    <div class="notify">
                        <a class="nav-link" href="#" id="notify-btn">
                            <img src="<?php echo base_url; ?>notif/Notif.png" width="30" height="30" alt="Notifications">
                            <span class="icon-button__badge" id="show_notif">0</span>
                        </a>
                        <div class="notify-menu" id="notify-menu" aria-labelledby="notify-btn">
                        </div>
                    </div>
                </li> -->
                <li class="nav-item<?php echo isActive(['/admin/car?page=car_list']) ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url ?>admin/car?page=car_list">Home</a>
                </li>
                <li class="nav-item dropdown<?php echo isActive(['/admin/car/all_car_list.php', '/admin/atap/all_atap_list.php', '/admin/other_fees/all_of_list.php']) ? ' active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Files
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownFiles">
                        <a class="dropdown-item" href="<?php echo base_url ?>admin/car/all_car_list.php">CAR List</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>admin/atap/all_atap_list.php">ATAP List</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>admin/other_fees/all_of_list.php">Other Fees</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>tenants/list.php">Tenant List</a>
                    </div>
                </li>
                <li class="nav-item dropdown<?php echo isActive([
                        'car_type',
                        'users',
                        'logs',
                        'bank/check_type.php',
                        'bank/online_type.php',
                        'users/index_department.php',
                        'users/index_position.php'
                    ]) ? ' active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSettings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Settings
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownSettings">
                        <a class="dropdown-item" href="<?php echo base_url ?>admin/settings/car_type?page=index">Transaction Types</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>admin/settings/users?page=index">System Users</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>admin/settings/logs?page=index">User Logs</a>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-submenu">
                            <a class="dropdown-item" href="#" id="navbarDropdownBank" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Bank Types <i class="fas fa-caret-right float-right"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownBank">
                                <li><a class="dropdown-item" href="<?php echo base_url ?>admin/settings/bank/check_type.php">Check</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url ?>admin/settings/bank/online_type.php">Online</a></li>
                            </ul>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-submenu">
                            <a class="dropdown-item" href="#" id="navbarDropdownPosition" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Users Details<i class="fas fa-caret-right float-right"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownPosition">
                                <li><a class="dropdown-item" href="<?php echo base_url ?>admin/settings/users/index_department.php">Department</a></li>
                                <li><a class="dropdown-item" href="<?php echo base_url ?>admin/settings/users/index_position.php">Position</a></li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown<?php echo isActive(['car_reports']) ? ' active' : ''; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Reports
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                    <a class="dropdown-item" href="<?php echo base_url ?>admin/reports/car_reports.php">CAR Reports</a>
                    <a class="dropdown-item" href="<?php echo base_url ?>admin/reports/or_reports.php">OR Reports</a>
                    <a class="dropdown-item" href="<?php echo base_url ?>admin/reports/summary_car_reports.php">Summary of Reports</a>
                </div>
                </li>
                <li class="nav-item dropdown<?php echo isActive(['profile']) ? ' active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($c_realname); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="<?php echo base_url ?>admin/settings/users/my_account.php">My Account</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>auth/logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    <?php } ?>
    <?php if ($c_group == 2) { ?>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <!-- <li class="nav-item dropdown<?php echo isActive(['/supervisor/car?page=car_list']) ? ' active' : ''; ?>">
            <div class="notify">
                <a class="nav-link" href="#" id="notify-btn">
                    <img src="<?php echo base_url; ?>notif/Notif.png" width="30" height="30" alt="Notifications">
                    <span class="icon-button__badge" id="show_notif">0</span>
                </a>
                <div class="notify-menu" id="notify-menu" aria-labelledby="notify-btn">
                </div>
            </div>
        </li> -->
        <li class="nav-item<?php echo isActive(['/supervisor/car?page=car_list']) ? ' active' : ''; ?>">
          <a class="nav-link" href="<?php echo base_url ?>supervisor/car?page=car_list">Home</a>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['/supervisor/car/all_car_list.php']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Files
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownFiles">
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/car/all_car_list.php">Car List</a>
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/atap/all_atap_list.php">ATAP List</a>
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/other_fees/all_of_list.php">Other Fees</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['car_type', 'user', 'bank']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSettings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Settings
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownSettings">
                <!-- <a class="dropdown-item" href="<?php echo base_url ?>supervisor/settings/car_type?page=index">Transaction Types</a>
                <div class="dropdown-divider"></div> -->
                <div class="dropdown-submenu">
                <a class="dropdown-item" href="#" id="navbarDropdownBank" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bank Types <i class="fas fa-caret-right float-right"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownBank">
                    <li><a class="dropdown-item" href="<?php echo base_url ?>supervisor/settings/bank/check_type.php">Check</a></li>
                    <li><a class="dropdown-item" href="<?php echo base_url ?>supervisor/settings/bank/online_type.php">Online</a></li>
                </ul>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown<?php echo isActive(['car_reports']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Reports
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/reports/car_reports.php">CAR Reports</a>
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/reports/or_reports.php">OR Reports</a>
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/reports/summary_car_reports.php">Summary of Reports</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['profile']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo htmlspecialchars($c_realname); ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/settings/users/my_account.php">My Account</a>
                <a class="dropdown-item" href="<?php echo base_url ?>auth/logout.php">Logout</a>
            </div>
        </li>
      </ul>
    </div>
    <?php } ?>
      <?php if ($c_group == 3) { ?>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <!-- <li class="nav-item dropdown<?php echo isActive(['/cashier/car?page=car_list']) ? ' active' : ''; ?>">
                <div class="notify">
                    <a class="nav-link" href="#" id="notify-btn">
                        <img src="<?php echo base_url; ?>notif/Notif.png" width="30" height="30" alt="Notifications">
                        <span class="icon-button__badge" id="show_notif">0</span>
                    </a>
                    <div class="notify-menu" id="notify-menu" aria-labelledby="notify-btn">
                    </div>
                </div>
             </li> -->
              <li class="nav-item<?php echo isActive(['/cashier/car?page=car_list']) ? ' active' : ''; ?>">
                  <a class="nav-link" href="<?php echo base_url ?>cashier/car?page=car_list">Home</a>
              </li>
              <li class="nav-item dropdown<?php echo isActive(['/cashier/car/all_car_list.php']) ? ' active' : ''; ?>">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Files
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownFiles">
                      <a class="dropdown-item" href="<?php echo base_url ?>cashier/car/all_car_list.php">Car List</a>
                      <a class="dropdown-item" href="<?php echo base_url ?>cashier/atap/all_atap_list.php">ATAP List</a>
                      <a class="dropdown-item" href="<?php echo base_url ?>cashier/other_fees/all_of_list.php">Other Fees</a>
                  </div>
              </li>
              <li class="nav-item dropdown<?php echo isActive(['car_type', 'user', 'bank']) ? ' active' : ''; ?>">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSettings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Settings
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownSettings">
                      <!-- <a class="dropdown-item" href="<?php echo base_url ?>cashier/settings/car_type?page=index">Transaction Typess</a>
                      <div class="dropdown-divider"></div> -->
                      <div class="dropdown-submenu">
                          <a class="dropdown-item" href="#" id="navbarDropdownBank" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Bank Types<i class="fas fa-caret-right float-right"></i>
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="navbarDropdownBank">
                              <li><a class="dropdown-item" href="<?php echo base_url ?>cashier/settings/bank/check_type.php">Check</a></li>
                              <li><a class="dropdown-item" href="<?php echo base_url ?>cashier/settings/bank/online_type.php">Online</a></li>
                          </ul>
                      </div>
                  </div>
              </li>
              <li class="nav-item dropdown<?php echo isActive(['car_reports']) ? ' active' : ''; ?>">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Reports
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                      <a class="dropdown-item" href="<?php echo base_url ?>cashier/reports/car_reports.php">CAR Reports</a>
                      <a class="dropdown-item" href="<?php echo base_url ?>cashier/reports/or_reports.php">OR Reports</a>
                  </div>
              </li>
              <li class="nav-item dropdown<?php echo isActive(['profile']) ? ' active' : ''; ?>">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <?php echo htmlspecialchars($c_realname); ?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                      <a class="dropdown-item" href="<?php echo base_url ?>cashier/settings/users/my_account.php">My Account</a>
                      <a class="dropdown-item" href="<?php echo base_url ?>auth/logout.php">Logout</a>
                  </div>
              </li>
          </ul>
      </div>


    <?php } ?>

    <?php if ($c_group == 4) { ?>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item dropdown<?php echo isActive(['/viewer/car?page=car_list']) ? ' active' : ''; ?>">
                    <div class="notify">
                        <a class="nav-link" href="#" id="notify-btn">
                            <img src="<?php echo base_url; ?>notif/Notif.png" width="30" height="30" alt="Notifications">
                            <span class="icon-button__badge" id="show_notif">0</span>
                        </a>
                        <div class="notify-menu" id="notify-menu" aria-labelledby="notify-btn">
                        </div>
                    </div>
                </li> -->
                <li class="nav-item<?php echo isActive(['/viewer/car?page=car_list']) ? ' active' : ''; ?>">
                    <a class="nav-link" href="<?php echo base_url ?>viewer/car?page=car_list">Home</a>
                </li>
                <li class="nav-item dropdown<?php echo isActive(['/viewer/atap/atap_list.php']) ? ' active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Files
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownFiles">
                        <a class="dropdown-item" href="<?php echo base_url ?>viewer/atap/all_atap_list.php">ATAP List</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>viewer/car/all_car_list.php">Car List</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>viewer/other_fees/all_of_list.php">Other Fees</a>
                    </div>
                </li>
                <li class="nav-item dropdown<?php echo isActive(['profile']) ? ' active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($c_realname); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="<?php echo base_url ?>viewer/settings/users/my_account.php">My Account</a>
                        <a class="dropdown-item" href="<?php echo base_url ?>auth/logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    <?php } ?>
  </div>
</nav>
</body>
</html>
<script>

    //const username = "<?php echo $_SESSION['username']; ?>";
    const cGroup = document.getElementById('c_group').value; 
    const baseURL = "<?php echo base_url; ?>";
    const notify_btn = document.getElementById('notify-btn');
    const notify_label = document.getElementById('show_notif');
    const notify_container = document.getElementById('notify-menu');
    const xhr = new XMLHttpRequest();

    notify_btn.addEventListener('click', (e) => {
    e.preventDefault();
    notify_container.classList.toggle('show');
    if (notify_container.classList.contains('show')) {

        xhr.open('GET', `${baseURL}notif/data.php?cGroup=${cGroup}`, true);

        xhr.send();
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    if (xhr.getResponseHeader('Content-Type') === 'application/json') {
                        let data = JSON.parse(xhr.responseText);
                        notify_container.innerHTML = ''; 
                        
                        let title = document.createElement('h2');
                        title.textContent = 'Notifications';
                        title.classList.add('notification-title');
                        notify_container.appendChild(title);
                        
                        let messageList = document.createElement('ul');
                        messageList.classList.add('notification-list');
                        
                        data.forEach(notification => {
                            let li = document.createElement('li');
                            /* li.innerHTML = formatFirstWordBold(notification.message); */
                            li.innerHTML = notification.message;
                            li.classList.add('notification-item');
                            li.setAttribute('data-id', notification.notif_id);
                            li.setAttribute('data-atap-no', notification.c_atap_no); 

                            if (notification.seen_status == 0) {
                                li.style.backgroundColor = 'whitesmoke'; 
                            } else {
                                li.style.backgroundColor = 'white';
                            }
                            li.addEventListener('click', () => {

                            let notifId = notification.notif_id;
                            let atapNo = li.getAttribute('data-atap-no');

                            let cGroup = document.getElementById('c_group').value; 
                            
                            let xhrUpdate = new XMLHttpRequest();
                            xhrUpdate.open('GET', `<?php echo base_url; ?>notif/notif.php?notif_id=${notifId}`, true);
                            xhrUpdate.send();
                            xhrUpdate.onload = function () {
                                if (xhrUpdate.status === 200) {
                                    console.log('Response:', xhrUpdate.responseText);

                                    let redirectUrl;
                                    if (cGroup === '4') {
                                        redirectUrl = `<?php echo base_url; ?>viewer/atap/all_atap_list.php?atap_no=${atapNo}`;
                                    } else if (cGroup === '3') {
                                        redirectUrl = `<?php echo base_url; ?>cashier/atap/all_atap_list.php?atap_no=${atapNo}`;
                                    } else if (cGroup === '2') {
                                        redirectUrl = `<?php echo base_url; ?>supervisor/atap/all_atap_list.php?atap_no=${atapNo}`;
                                    } else if (cGroup === '1') {
                                        redirectUrl = `<?php echo base_url; ?>admin/atap/all_atap_list.php?atap_no=${atapNo}`;

                                    } else {
                                        console.error('Unknown cGroup value:', cGroup);
                                        return;
                                    }

                                    window.location.href = redirectUrl;
                                } else {
                                    console.error('Request failed with status:', xhrUpdate.status);
                                }
                            };
                        });
                            messageList.appendChild(li);
                        });
                        
                        notify_container.appendChild(messageList);
                    } else {
                        console.error('Unexpected response format:', xhr.responseText);
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            } else {
                console.error('Request failed with status:', xhr.status);
            }
        };
    }
});

function isActive($pages) {
    $currentPage = $_SERVER['REQUEST_URI']; 
    foreach ($pages as $page) {
        if (strpos($currentPage, $page) !== false) {
            return true;
        }
    }
    return false;
}

</script>