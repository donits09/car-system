$(document).ready(function () {
    $('#c_car_type').on('input', function () {
        var input = $(this).val().toLowerCase();
        var hasVisibleOptions = false;
        $('#comboBoxMenu .dropdown-item').each(function () {
            if ($(this).text().toLowerCase().startsWith(input)) {
                $(this).show();
                hasVisibleOptions = true;
            } else {
                $(this).hide();
            }
        });

        if (hasVisibleOptions) {
            $('#comboBoxMenu').show();
        } else {
            $('#comboBoxMenu').hide();
        }
    });

    $('#comboBoxMenu').on('click', '.dropdown-item', function () {
        var selectedText = $(this).data('value');
        $('#c_car_type').val(selectedText);
        $('#comboBoxMenu').hide();
    });

    $('#c_car_type').on('click', function () {
        $('#comboBoxMenu').show();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('#comboBoxMenu').hide();
        }
    });
});

function submitForm() {
    var selectedOption = document.getElementById('c_car_type').value;
    alert('You selected: ' + selectedOption);
}

$(document).ready(function() {
    $('#car-form').submit(function(e) {
        e.preventDefault();
       
        if (confirm("Are you sure you want to save this car payment?")) {
            var _this = $(this);

            start_loader();

            $.ajax({
            url: "../../classes/Master.php?f=save_car_payment",
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
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }

        });
        }
    });
});

var idValue = "<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>";
if (idValue) {
    $('#comboBoxMenu').find('a[data-value="' + idValue + '"]').addClass('active');
}