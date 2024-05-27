<?php
include('../config.php');

$payment_type = $_GET['term']; // assuming you're sending the search term via GET parameter 'term'

// Insert new payment type into the database
$insert_query = "INSERT INTO t_car_type (c_payment_type) VALUES (?)";
$insert_statement = odbc_prepare($conn, $insert_query);
if (odbc_execute($insert_statement, array($payment_type))) {
    // Return the new payment type for display
    echo json_encode(array(array('id' => $payment_type, 'text' => $payment_type)));
} else {
    // Return an error message if insertion fails
    echo json_encode(array('error' => 'Failed to insert payment type.'));
}
?>
