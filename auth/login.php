<?php
require_once('../config.php');
require_once('session_auth.php');
include('../inc/header.php');  


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM t_car_users WHERE c_employee_code='$username'";
    $result = odbc_exec($conn, $query);

    if ($result && odbc_num_rows($result) > 0) {
        $user_data = odbc_fetch_array($result);
        $hashed_password = $user_data['c_password'];

        if (password_verify($password, $hashed_password)) {
            $user_group = $user_data['c_group'];
            $user_department = $user_data['c_department'];
            initialize_session($username, $user_group, $user_department);
            check_session();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}

check_session();
?>
 <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CAR ENCODING</title>
<link rel="stylesheet" href="../dist/css/login.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/login.css">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url ?>dist/header_files/favicon/alsc-32.png">
<style>
.login-logo {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}

.input-icon {
    position: relative;
}

.input-icon i {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 13px;
    color: #888;
}

.input-icon input {
    padding-right: 35px;
}
/* body {
    background-image: url('<?php echo base_url; ?>images/asianland.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
} */
</style>
<body>
    <div class="header">
        <h1>Welcome!</h1>
        <!-- <p>Log in to continue</p> -->
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card card-container p-2">
                    <div class="card-body text-center">
                        <img src="<?php echo base_url; ?>images/login.jpg" alt="Logo" class="login-logo">
                        <hr class="line">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <div class="form-group pt-3">
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Employee ID" required>
                                </div>
                            </div>
                            <div class="form-group pt-2">
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="*************" required>
                                </div>
                            </div>
                            <div class="form-group pt-2">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>