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
        loadModal('Create New Car', 'all_manage_car.php?c_account_no=' + accountNo, '#createCarModal');
    });

    $(document).on('click', '.edit_data', function() {
        var accountId = $(this).data('id');
        var accountNo = $(this).data('account-no');
    
        if (!accountNo) {
            loadModal('Edit Car Details', 'manage_other_car.php?id=' + accountId, '#createCarModal');
        } else {
            loadModal('Edit Car Details', 'all_manage_car.php?id=' + accountId, '#createCarModal');
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
        _conf("Are you sure you want to delete this car permanently?", delete_car, [carId, carNo]);
    });

    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };
});

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


function calculateTotalAmount() {
    var table = document.getElementById("car-list-table");
    if (!table) {
        console.log("Table not found.");
        return;
    }

    var tbody = table.getElementsByTagName("tbody")[0];
    if (!tbody) {
        console.log("Table body not found.");
        return;
    }

    var rows = tbody.getElementsByTagName("tr");
    var total = 0;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== "none") {
            var amountCell = rows[i].getElementsByTagName("td")[6]; 
            if (amountCell) {
                var amountValue = amountCell.textContent.trim().replace(/,/g, '');
                var parsedValue = parseFloat(amountValue);
                if (!isNaN(parsedValue)) {
                    total += parsedValue;
                } else {
                    console.log("Invalid number:", amountValue);
                }
            } else {
                console.log(i);
            }
        } else {
            console.log(i);
        }
    }

    var totalAmountElement = document.getElementById("totalAmount");
    if (totalAmountElement) {
        totalAmountElement.textContent = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    } else {
        console.log("Total amount element not found.");
    }
}

$(document).ready(function() {
    calculateTotalAmount();
});


function updateCarList() {
    const accountNo = document.getElementById('buyer_acc_no').value;
    fetch(`car_list.php?account_no=${accountNo}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('car-list-body').innerHTML = data;
            calculateTotalAmount();
        });
        calculateTotalAmount();
}

function delete_car(carId, carNo) {
    start_loader();
    $.ajax({
        url: "../../classes/Master.php?f=delete_car",
        method: "POST",
        data: { carId: carId, carNo: carNo },
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
    $('#car-form').submit(function(e) {
        e.preventDefault();

        const buyerName = $('#buyer_name').val();
        if (!buyerName || buyerName === 'Unknown') {
            alert('Name field is required.');
            return;
        }

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

    
    var idValue = "<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>";
    if (idValue) {
        $('#comboBoxMenu').find('a[data-value="' + idValue + '"]').addClass('active');
    }
});
