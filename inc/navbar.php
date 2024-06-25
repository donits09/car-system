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
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="https://asianland.ph/">
        <img src="<?php echo base_url ?>images/logo.jpg" alt="ALSC Logo"> CAR ENCODING
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php if ($c_group == 1) { ?>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item<?php echo isActive(['/admin/car?page=car_list']) ? ' active' : ''; ?>">
          <a class="nav-link" href="<?php echo base_url ?>admin/car?page=car_list">Home</a>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['/admin/car/all_car_list.php']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Files
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownFiles">
                <a class="dropdown-item" href="<?php echo base_url ?>admin/car/all_car_list.php">Car List</a>
            </div>
        </li>

        <li class="nav-item dropdown<?php echo isActive(['car_type', 'user']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSettings" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Settings
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownSettings">
                <a class="dropdown-item" href="<?php echo base_url ?>admin/settings/car_type?page=index">Car Type</a>
                <a class="dropdown-item" href="<?php echo base_url ?>admin/settings/users?page=index">System Users</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['car_reports']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Reports
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                <a class="dropdown-item" href="<?php echo base_url ?>admin/reports/car_reports.php">Car Reports</a>
                <a class="dropdown-item" href="<?php echo base_url ?>admin/reports/summary_car_reports.php">Summary of Reports</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['profile']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo htmlspecialchars($c_realname); ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                <a class="dropdown-item" href="<?php echo base_url ?>auth/logout.php">Logout</a>
            </div>
        </li>
      </ul>
    </div>
    <?php } ?>
    <?php if ($c_group == 2) { ?>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item<?php echo isActive(['/supervisor/car?page=car_list']) ? ' active' : ''; ?>">
          <a class="nav-link" href="<?php echo base_url ?>supervisor/car?page=car_list">Home</a>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['/supervisor/car/all_car_list.php']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Files
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownFiles">
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/car/all_car_list.php">Car List</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['car_reports']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Reports
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/reports/car_reports.php">Car Reports</a>
                <a class="dropdown-item" href="<?php echo base_url ?>supervisor/reports/summary_car_reports.php">Summary of Reports</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['profile']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo htmlspecialchars($c_realname); ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
                <a class="dropdown-item" href="<?php echo base_url ?>auth/logout.php">Logout</a>
            </div>
        </li>
      </ul>
    </div>
    <?php } ?>
    <?php if ($c_group == 3) { ?>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item<?php echo isActive(['/cashier/car?page=car_list']) ? ' active' : ''; ?>">
          <a class="nav-link" href="<?php echo base_url ?>cashier/car?page=car_list">Home</a>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['/cashier/car/all_car_list.php']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFiles" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Files
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownFiles">
                <a class="dropdown-item" href="<?php echo base_url ?>cashier/car/all_car_list.php">Car List</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['car_reports']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownReports" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Reports
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownReports">
                <a class="dropdown-item" href="<?php echo base_url ?>cashier/reports/car_reports.php">Car Reports</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive(['profile']) ? ' active' : ''; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo htmlspecialchars($c_realname); ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownProfile">
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
