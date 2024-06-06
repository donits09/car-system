<?php 
$c_realname = '';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $get_realname_query = "SELECT c_realname FROM t_car_users WHERE c_employee_code = ?";
    $stmt = odbc_prepare($conn, $get_realname_query);
    if (odbc_execute($stmt, array($username))) {
        if ($result = odbc_fetch_array($stmt)) {
            $c_realname = $result['c_realname'];
        }
    }
}
function isActive($page) {
    return strpos($_SERVER['REQUEST_URI'], $page) !== false ? ' active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CAR ENCODING</title>
  <link rel="stylesheet" href="<?php echo base_url ?>dist/css/navbar.css">
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
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item<?php echo isActive('car_list'); ?>">
          <a class="nav-link" href="<?php echo base_url ?>admin/car?page=car_list">Home</a>
        </li>
        <li class="nav-item dropdown<?php echo isActive('car_main'); ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Files
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?php echo base_url; ?>admin/car_main?page=index">Car List</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive('car_reports'); ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Reports
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="">Car List</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive('car_type'); ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Settings
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?php echo base_url ?>admin/settings/car_type?page=index">Car Type</a>
            </div>
        </li>
        <li class="nav-item dropdown<?php echo isActive('profile'); ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo htmlspecialchars($c_realname); ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?php echo base_url ?>auth/logout.php">Logout</a>
            </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
</body>
</html>
