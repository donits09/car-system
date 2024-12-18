<?php
require_once('../../config.php');

$car_type_query = "SELECT DISTINCT c_payment_type, id FROM t_car_type WHERE status = 0 AND payment_status = 'C' ORDER BY c_payment_type ASC";
$type_result = odbc_exec($conn, $car_type_query);

echo '<label for="c_car_type">Transaction Type</label>';
echo '<input type="text" class="form-control" oninput="validateAlphaNumericInput(event)" id="c_car_type" name="c_car_type" placeholder="Type or select an option" autocomplete="off">';
echo '<div class="dropdown-menu w-100" id="comboBoxMenu_car" style="max-height: 200px; overflow-y: auto;">';

while ($row = odbc_fetch_array($type_result)) {
    echo "<a class='dropdown-item' href='#' data-value='" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['c_payment_type'], ENT_QUOTES, 'UTF-8') . "</a>";
}

echo '</div>';
?>
