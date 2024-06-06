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
            $c_group = $user_data['c_group'];
            initialize_session($username, $c_group);
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
<link rel="stylesheet" href="../dist/css/login.css">
<link rel="stylesheet" href="<?php echo base_url ?>dist/css/login.css">

<body>
    <div class="container login_padding">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card card-container p-2">
                    <div class="card-body">
                        <h2 class="card-title text-center">Log In</h2>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form action="" method="POST">
                            <div class="form-group pt-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Employee ID" required>
                            </div>
                            <div class="form-group pt-2">
                                <input type="password" class="form-control" id="password" name="password" placeholder="***********" required>
                            </div>
                            <div class="form-group pt-4">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>