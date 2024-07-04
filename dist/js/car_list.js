$(document).ready(function() {
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
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred while loading data.");
                end_loader();
            }
        });
    }

    $('#create_new').click(function() {
        var accountNo = $(this).data('account-no');
        loadModal('Create New Car', 'manage_car.php?c_account_no=' + accountNo, '#createCarModal');
    });

    $(document).on('click', '.edit_data', function() {
        var accountId = $(this).data('id');
        var accountNo = $(this).data('account-no');
    
        if (!accountNo) {
            loadModal('Edit Car Details', 'manage_other_car.php?id=' + accountId, '#createCarModal');
        } else {
            loadModal('Edit Car Details', 'manage_car.php?id=' + accountId, '#createCarModal');
        }
    });

    $(document).on('click', '.view_data', function() {
        var accountId = $(this).data('id');
        loadModal('Car Details', 'view_car.php?id=' + accountId, '#viewModal');
    });

    $('#create_other_new').click(function() {
        loadModal('Create New Car', 'manage_other_car.php', '#createCarModal');
    });

    $(document).on('click', '.delete_data', function() {
        var carId = $(this).data('id');
        var carNo = $(this).data('car-no');
        _conf("Are you sure you want to cancel this car permanently?", delete_car, [carId, carNo]);
    });

    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };
});
