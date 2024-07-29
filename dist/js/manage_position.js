function validateAlphaNumericInput(event) {
    const input = event.target;
    let value = input.value;

    value = value.replace(/[^a-zA-Z0-9-\s]/g, '');
    input.value = value;
}

$('#create_new').click(function() {
    loadModal('Create New Position', 'manage_position.php', '#createCarModal');
});

$(document).on('click', '.edit_data', function() {
    var positionId = $(this).data('id');
    loadModal('Edit Position', 'manage_position.php?id=' + positionId, '#createCarModal');
});

$(document).on('click', '.delete_data', function() {
    var positionId = $(this).data('id');
    var positionType = $(this).data('position-type');
    _conf("Are you sure you want to delete this car type permanently?", delete_position, [positionId, positionType]);
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

function delete_position(positionId, positionType) {
    start_loader();
    $.ajax({
        url: "../../../classes/Master.php?f=delete_emp_position",
        method: "POST",
        data: { positionId: positionId, positionType: positionType },
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
                    $('.delete_data[data-id="' + carId + '"]').closest('tr').remove();
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

$(document).ready(function() {
    $('#position-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);

        start_loader();

        $.ajax({
            url: "../../../classes/Master.php?f=save_emp_position",
            data: new FormData(_this[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(err) {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                console.log(resp);
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else if (resp && resp.status === 'failed' && resp.msg) {
                    alert_toast(resp.msg, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }
        });
    });
});