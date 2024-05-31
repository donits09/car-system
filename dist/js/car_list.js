

$(document).ready(function() {
    $(document).on('click', '.edit_data', function() {
        var accountId = $(this).data('id');
        loadModal('Edit Car Details', 'manage_car.php?id=' + accountId, '#editModal');
    });

    $(document).on('click', '.view_data', function() {
        var accountId = $(this).data('id');
        loadModal('Car Details', 'view_car.php?id=' + accountId, '#viewModal');
    });
   
    $('#create_new').click(function() {
        var accountNo = $(this).data('account-no');
        loadModal('Create New Car', 'manage_car.php?c_account_no=' + accountNo, '#createCarModal');
    });

    $(document).on('click', '.delete_data', function() {
        var carId = $(this).data('id');
        _conf("Are you sure you want to delete this car permanently?", delete_car, [carId]);
    });

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

    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };

    function delete_car(carId) {
        start_loader();
        $.ajax({
            url: "../../classes/Master.php?f=delete_car",
            method: "POST",
            data: { carId: carId },
            dataType: "json",
            error: function(err) {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    setTimeout(function() {
                        //location.reload();
                    }, 2000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }
        });
    }

    window.uni_modal = function($title = '', $url = '', $size = '') {
        loadModal($title, $url, '#uni_modal', $size);
    };
});
