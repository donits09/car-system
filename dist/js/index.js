

$(document).ready(function() {
    // Handle edit data
    $(document).on('click', '.edit_data', function() {
        var accountId = $(this).data('id');
        loadModal('Edit Car Details', 'manage_car.php?id=' + accountId, '#editModal');
    });

    // Handle view data
    $(document).on('click', '.view_data', function() {
        var accountId = $(this).data('id');
        loadModal('Car Details', 'view_car.php?id=' + accountId, '#viewModal');
    });

    // Handle create new car
    $('#create_new').click(function() {
        loadModal('Create New Car', 'manage_car.php', '#createCarModal');
    });

    // Handle delete data
    $(document).on('click', '.delete_data', function() {
        var carId = $(this).data('id');
        _conf("Are you sure you want to delete this car permanently?", delete_car, [carId]);
    });

    // Load modal content
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

    // Confirmation modal function
    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };

    // Delete car function
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
                        location.reload();
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

    // Universal modal function
    window.uni_modal = function($title = '', $url = '', $size = '') {
        loadModal($title, $url, '#uni_modal', $size);
    };

    // Loader functions
    function start_loader() {
        $('body').append('<div id="preloader"><div class="loader-holder"><div></div><div></div><div></div><div></div></div></div>');
    }

    function end_loader() {
        $('#preloader').fadeOut('fast', function() {
            $('#preloader').remove();
        });
    }

    // Alert function
    window.alert_toast = function($msg = 'TEST', $bg = 'success', $pos = '') {
        var Toast = Swal.mixin({
            toast: true,
            position: $pos || 'top-end',
            showConfirmButton: false,
            timer: 5000
        });
        Toast.fire({
            icon: $bg,
            title: $msg
        });
    };
});
