<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <style>
    .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
    }
    .btn-rounded{
            border-radius: 50px;
    }
    .notify{
        position:absolute;

    }
    .notify-btn{
        margin-left:-180px;
        margin-top:260px;
        float:right;
    }
    .notify-menu {
        /* ... your existing styles ... */
        overflow-y: auto; /* Add this line to enable vertical scrollbar */
        max-height: 600px; /* Adjust max-height as needed */
        box-shadow: 1px 1px 1px;
        position: absolute;
        width: 250px;
        top: 6px;
        right: 33px;
        display: none;
    }
    .show{
        display:block;
    }
    .notification-title {
        font-size: 18px;
        color: black;
        padding:7px;
        background-color: gainsboro;
        margin:0px;
    }
    .notify-menu li{
        padding:7px;
        font-size:14px;
        width:100%;
        list-style:none;
        background-color: #f2f2f2;
        color:black;
        cursor:hand;
    }
    .notify-menu li:hover{
        background-color:#007bff;
        cursor:pointer;
        color:white;
    }
    .icon-button{
        position:absolute;
        display: flex;
        align-items:center;
        justify-content: center;
        width:25px;
        height:25px;
        margin-top:-282px;
        margin-left:-60px;
        border:none!important;
        transition: opacity 0.3s;
        /* color:#333333; */
        /* background:#dddddd; */
        /* border:none;
        outline:none; */
    }
    .icon-button:hover {
        cursor: pointer;
        opacity: 0.9;
    }
    .icon-button:active{
        border: none !important;
    }
    .icon-button__badge{
        position: absolute;
        top:-10px;
        right:-10px;
        width:25px;
        height:25px;
        background:red;
        color:#ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }
    .notification-container {
    max-height: 300px;
    overflow-y: auto;
    /* Add the following line to allow scrolling when content exceeds max-height */
    overflow-x: hidden;
}

.notification-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.notification-list li {
    margin: 0;
    padding: 7px;
    /* Add additional styles as needed */
}
/* Add this to your existing <style> section */
.notification-list hr {
    margin: 0px;
    border: none;
    border-top: 1px dashed #ccc;
}

</style>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-light border border-light border-top-0  border-left-0 border-right-0 navbar-light text-sm shadow-sm">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo base_url ?>" class="nav-link"><?php echo (!isMobileDevice()) ? $_settings->info('name'):$_settings->info('short_name'); ?> - Admin</a>
          </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Navbar Search -->
          <!-- <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
              <form class="form-inline">
                <div class="input-group input-group-sm">
                  <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </li> -->
          <!-- Messages Dropdown Menu -->
          <li class="nav-item">
            <div class="btn-group nav-link">
                  <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <span><img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
                    <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu" role="menu">
                                      <a class="dropdown-item" href="<?php echo base_url.'admin/?page=user/manage_user&id='.urlencode($_settings->userdata('user_code')); ?>">
                        <span class="fa fa-user"></span> My Account
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=logout' ?>"><span class="fas fa-sign-out-alt"></span> Logout</a>
                  </div>



        <div class="notify">
            <div class="notify-btn" id="notify-btn">
                <button type="button" class="icon-button">
                    <span><img src="./notifications/Notif.png" width="30" height="30"></span>
                    <span class="icon-button__badge" id="show_notif">0</span>
                </button>
            </div>
            <div class="notify-menu" id="notify-menu">
            </div>
              </div>
              </div>
          </li>
          <li class="nav-item">
            
          </li>
         <!--  <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
            </a>
          </li> -->
        </ul>
      </nav>
      <script>
    const notify_btn = document.getElementById('notify-btn');
    const notify_label = document.getElementById('show_notif');
    const notify_container = document.getElementById('notify-menu');

    let xhr = new XMLHttpRequest();
    <?php $usertype = $_settings->userdata('user_type'); ?>

    function notify_me() {
        xhr.open('GET', `./notifications/select.php?user_type=<?php echo urlencode($usertype); ?>`, true);
        xhr.send();
        xhr.onload = () => {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                try {
                    let get_data = JSON.parse(xhr.responseText);
                    //console.log(get_data);
                    //console.log('<?php echo $usertype; ?>');
                    if (get_data == get_data) {
                        // Set notification count
                        notify_label.innerHTML = get_data;
                    } else {
                        // Append new notifications
                        notify_container.innerHTML += get_data;
                    }
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            }
        };
    }

    window.onload = () => {
        //notify_me();

        setInterval(() => {
            //notify_me();
        }, 1000);
    };

    notify_btn.addEventListener('click', (e) => {
    e.preventDefault();

    notify_container.classList.toggle('show');

    if (notify_container.classList.contains('show')) {
        xhr.open('GET', `./notifications/data.php?user_type=<?php echo urlencode($usertype); ?>`, true);
        xhr.send();
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    let data = JSON.parse(xhr.responseText);
                    let title = document.createElement('h2');
                    title.textContent = 'Notifications';
                    title.classList.add('notification-title');
                    notify_container.appendChild(title);

                    let messageList = document.createElement('ul');
                    messageList.classList.add('notification-list');

                    // Loop through notifications
                    for (let i = 0; i < data.length; i++) {
                        // Create <hr> before each message
                        if (i > 0) {
                            let hr = document.createElement('hr');
                            hr.classList.add('custom-hr');
                            messageList.appendChild(hr);
                        }

                        let formattedMsg = formatFirstWordBold(data[i].msg);
                        let timeAgo = calculateTimeAgo(data[i].date_time_created); // Use date_time_created
                        let li = document.createElement('li');
                        li.innerHTML = `${formattedMsg}<br><small>${timeAgo}</small>`; // Include time ago
                        messageList.appendChild(li);
                    }

                    notify_container.appendChild(messageList);
                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            }
        }
    } else {
        notify_container.innerHTML = ''; // Clear content when hiding
    }
});
function calculateTimeAgo(date_time_created) {
    const currentTime = new Date();
    const notificationTime = new Date(date_time_created);
    const timeDifference = currentTime - notificationTime;
    const seconds = Math.floor(timeDifference / 1000);
    if (seconds < 60) {
        return `${seconds} seconds ago`;
    } else if (seconds < 3600) {
        const minutes = Math.floor(seconds / 60);
        return `${minutes} minutes ago`;
    } else if (seconds < 86400) {
        const hours = Math.floor(seconds / 3600);
        return `${hours} hours ago`;
    } else {
        const days = Math.floor(seconds / 86400);
        return `${days} days ago`;
    }
}

    function formatFirstWordBold(msg) {
        let words = msg.split(' ');
        if (words.length > 0) {
            words[0] = '<strong>' + words[0] + '</strong>';
        }
        return words.join(' ');
    }
</script>
    </body>
</html>