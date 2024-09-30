
function validateNumberInputAmt(event) {
    const input = event.target;
    let value = input.value;

    value = value.replace(/,/g, '');

    value = value.replace(/[^\d.]/g, '');

    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }

    input.value = value;
}

function validateNumberInput(event) {
    const input = event.target;
    const value = input.value;

    input.value = value.replace(/\D/g, '');
}

function validateAlphaNumericInput(event) {
    const input = event.target;
    let value = input.value;

    value = value.replace(/[^a-zA-Z0-9-\s]/g, '');
    input.value = value;
}

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

$(document).ready(function() {
    $('#comboBoxMenuCheck').on('click', '.dropdown-item', function () {
        handleBankSelection('#c_bank_check', '#comboBoxMenuCheck', $(this));
    });

    $('#comboBoxMenuOnline').on('click', '.dropdown-item', function () {
        handleBankSelection('#c_bank_online', '#comboBoxMenuOnline', $(this));
    });

    function handleBankSelection(inputSelector, menuSelector, selectedItem) {
        var selectedText = selectedItem.data('value');
        $(inputSelector).val(selectedText);
        $(menuSelector).hide();
    }

    $('#c_bank_check, #c_bank_online').on('click', function () {
        $('#comboBoxMenuCheck').show();
        $('#comboBoxMenuOnline').show();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('#comboBoxMenuCheck').hide();
            $('#comboBoxMenuOnline').hide();
        }
    });

    var idValueCheck = "<?php echo isset($c_bank) ? htmlspecialchars($c_bank, ENT_QUOTES, 'UTF-8') : ''; ?>";
    if (idValueCheck) {
        $('#comboBoxMenuCheck').find('a[data-value="' + idValueCheck + '"]').addClass('active');
    }

    var idValueOnline = "<?php echo isset($c_bank) ? htmlspecialchars($c_bank, ENT_QUOTES, 'UTF-8') : ''; ?>";
    if (idValueOnline) {
        $('#comboBoxMenuOnline').find('a[data-value="' + idValueOnline + '"]').addClass('active');
    }

    toggleCheckDropdown();

    $('#c_mop').on('change', function() {
        toggleCheckDropdown();
    });
});

function submitForm() {
    var selectedOption = document.getElementById('c_car_type').value;
    alert('You selected: ' + selectedOption);
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
                    $('#confirm_modal').modal('hide'); 
                    $('body').removeClass('modal-open'); 
                    $('.modal-backdrop').remove(); 
                    location.reload();
                    $('.delete_data[data-id="' + carId + '"]').closest('tr').remove();
                }, 1000);
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
        const carNo = $('#c_car_no').val();
        const carAmount = parseFloat($('#c_car_amount').val().replace(/,/g, ''));

        let valid = true;

        if (carNo.length < 6) {
            $('#car_no_error').text('CAR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            valid = false;
        }

        if (carAmount <= 0) {
            $('#car_amt_error').text('Amount must be greater than zero.').addClass('bold-text').css('color', 'red');
            valid = false;
        }

        if (!buyerName || buyerName === 'Unknown') {
            alert('Name field is required.');
            valid = false;
        }

        if (!valid) {
            return;
        }

        start_loader();

        $.ajax({
            url: "../../classes/Master.php?f=save_car_payment",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: function(err) {
                console.log(err);
                //alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp) {
                console.log(resp); 
                if (resp && resp.status === 'success') {
                    alert_toast(resp.msg, 'success');
                    setTimeout(function() {
                        $('#createCarModal').modal('hide'); 
                        $('body').removeClass('modal-open'); 
                        $('.modal-backdrop').remove(); 
                        location.reload();
                    }, 500);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    //alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
                end_loader();
            }
        });
    });

    function fetchBuyerDetails(accountNo) {
        const buyerNameField = $('#buyer_name');
    
        if (accountNo && accountNo.length > 0) {
            $.ajax({
                type: 'POST',
                url: '../../admin/car/get_buyer_details.php',
                data: { account_no: accountNo },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        buyerNameField.val(response.name);
                        buyerNameField.removeAttr('required');
                    } else {
                        buyerNameField.val('Unknown');
                        buyerNameField.attr('required', 'required');
                    }
                }
            });
        } else {
            buyerNameField.val('');
            buyerNameField.attr('required', 'required');
        }
    }

    const accountNo = $('#c_account_no').val();
    fetchBuyerDetails(accountNo);

    $('#c_account_no').on('input', function() {
        const accountNo = $(this).val();
        fetchBuyerDetails(accountNo);
    });

    $('#c_car_no').on('input', function() {
        const carNo = $(this).val();

        if (carNo.length < 6) {
            $('#car_no_error').text('CAR No. must be 6 digits.').addClass('bold-text').css('color', 'red');
            $('#car-form button[type="submit"]').attr('disabled', true);
        } else if (carNo.length > 6) {
            $('#car_no_error').text('CAR No. exceeds 6 digits.').addClass('bold-text').css('color', 'blue');
            $('#car-form button[type="submit"]').attr('disabled', false);
        } else {
            $.ajax({
                type: 'POST',
                url: '../../admin/car/check_car_no.php',
                data: { car_no: carNo },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        $('#car_no_error').text('CAR No. already exists.').addClass('bold-text').css('color', 'red');
                        $('#car-form button[type="submit"]').attr('disabled', true);
                    } else {
                        $('#car_no_error').text('').removeClass('bold-text');
                        $('#car-form button[type="submit"]').attr('disabled', false);
                    }
                }
            });
        }
    });

    $('#car-form').on('submit', function(e) {
        if ($('#car_no_error').text().includes('must be 6 digits') || $('#car_amt_error').text().includes('greater than zero')) {
            e.preventDefault();
        }
    });
});

function clearAmt(){
    var txtamt = document.getElementById('c_car_amount').value;

    if(txtamt == '0.00'){
        document.getElementById('c_car_amount').value='';
    }
}

/* toggle para sa bank pantropiko */
function toggleCheckDropdown() {
    var modeOfPayment = document.getElementById("c_mop").value;
    var checkList = document.getElementById("checkList");
    var onlineBankList = document.getElementById("onlineBankList");
    var cBankCheckInput = document.getElementById("c_bank_check");
    var cBankOnlineInput = document.getElementById("c_bank_online");
    var cCheckNo = document.getElementById("c_check_no");
    var cRefNo = document.getElementById("c_ref_no");

    if (modeOfPayment == '2') {
        checkList.style.display = "block";
        cCheckNo.style.display = "block";
        onlineBankList.style.display = "none";
        cRefNo.style.display = "none";
        cBankCheckInput.setAttribute("required", "true");
        cBankOnlineInput.removeAttribute("required");
        cBankOnlineInput.value = ""; 
        cRefNo.value = "";
    } else if (modeOfPayment == '3') {
        checkList.style.display = "none";
        cCheckNo.style.display = "none";
        onlineBankList.style.display = "block";
        cRefNo.style.display = "block";
        cBankOnlineInput.setAttribute("required", "true");
        cBankCheckInput.removeAttribute("required");
        cBankCheckInput.value = "";
        cCheckNo.value = "";
    } else {
        checkList.style.display = "none";
        cCheckNo.style.display = "none";
        cRefNo.style.display = "none";
        onlineBankList.style.display = "none";
        cBankCheckInput.removeAttribute("required");
        cBankOnlineInput.removeAttribute("required");
        cBankCheckInput.value = "";
        cBankOnlineInput.value = "";
        cCheckNo.value = "";
        cRefNo.value = "";
    }
}
