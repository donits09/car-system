<?php
session_start();
include('../config.php');

if (isset($_GET['searchAcc'])) {
    $searchAcc = '%' . $_GET['searchAcc'] . '%';

    $acc_list = "SELECT * FROM t_buyers_account WHERE CAST(c_account_no AS TEXT) LIKE '$searchAcc'";
    $stmt = odbc_exec($conn, $acc_list);

    if ($stmt === false) {
        echo "<tr><td colspan='6' class='text-center'>No data available or error executing query.</td></tr>";
    } else {
        $i = 1;
        $hasData = false;
        while ($row = odbc_fetch_array($stmt)) {
            $hasData = true;
            echo "<tr>";
            echo "<td class='text-center'>" . $i++ . "</td>";
            echo "<td class='text-center'>" . htmlspecialchars($row['c_account_no']) . "</td>";
            echo "<td class='text-center'>" . htmlspecialchars($row['c_b1_last_name']) . "</td>";
            echo "<td class='text-center'>" . htmlspecialchars($row['c_b1_first_name']) . "</td>";
            echo "<td class='text-center'>" . htmlspecialchars($row['c_b1_middle_name']) . "</td>";
            echo "<td align='center'>
                    <button type='button' class='btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon' data-toggle='dropdown'>Action <span class='sr-only'>Toggle Dropdown</span></button>
                    <div class='dropdown-menu' role='menu'>
                        <a class='dropdown-item create_transfer' href='javascript:void(0)' data-acc-no='" . $row['c_account_no'] . "'>Transfer</a>
                    </div>
                  </td>";
            echo "</tr>";
        }
        if (!$hasData) {
            echo "<tr><td colspan='6' class='text-center'>No data found.</td></tr>";
        }
    }
}
?>
<script>
function loadModal(title, url, modalId) {
    start_loader();
    $.ajax({
        url: url,
        type: 'GET',
        success: function(response) {
            $(modalId + ' .modal-body').html(response);
            $(modalId + ' .modal-title').text(title);
            $(modalId).modal('show');
            end_loader();
            $('.dropdown-toggle').dropdown();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("An error occurred while loading data.");
            end_loader();
        }
    });
}


window._conf = function(msg, func, params) {
    $('#confirm_modal .modal-body').html(msg);
    $('#confirm_modal #confirm').off('click').on('click', function() {
        func.apply(this, params);
    });
    $('#confirm_modal').modal('show');
};

$(document).on('click', '.create_transfer', function() {
    var accountNo = $(this).data('acc-no');
    loadModal('Create New Transfer', '../transfer_account/manage_transfer.php?acc-no=' + accountNo, '#createTransferModal');
});
</script>