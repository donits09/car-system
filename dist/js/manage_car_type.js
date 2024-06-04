$(document).ready(function() {
    $(document).on('click', '.edit_data', function() {
        var carId = $(this).data('payment-id');
        var carType = $(this).data('payment-type');
        loadModal('Edit Car Type Details', 'manage_car.php?id=' + carId + '&type=' + carType, '#createCarModal');
    });

    $('#create_new').click(function() {
        loadModal('Create New Car Type', 'manage_car_type.php','#createCarModal');
    });

    $(document).on('click', '.delete_data', function() {
        var carId = $(this).data('id'); 
        _conf("Are you sure you want to delete car type #" + carId + "?", 'delete_car_type', [carId]); 
    });

    $('#data-table').DataTable();

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

    function delete_car_type(carId) {
        console.log("Car ID:", carId); 
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_car_type",
            method:"POST",
            data: { carId: carId },
            dataType:"json",
            error: function(err) {
                console.log(err);
                alert("An error occurred.");
                end_loader();
            },
            success: function(resp) {
                if (typeof resp === 'object' && resp.status === 'success') {
                    alert(resp.msg);
                    location.reload();
                } else {
                    alert("An error occurred.");
                    end_loader();
                }
            }
        });
    }

    window.uni_modal = function($title = '', $url = '', $size = '') {
        loadModal($title, $url, '#uni_modal', $size);
    };
});
