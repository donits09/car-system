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

function validateAlphaNumericInput(event) {
    const input = event.target;
    let value = input.value;

    value = value.replace(/[^a-zA-Z0-9-\s]/g, '');
    input.value = value;
}

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
    var idValue = "<?php echo isset($c_car_type) ? htmlspecialchars($c_car_type, ENT_QUOTES, 'UTF-8') : ''; ?>";
    if (idValue) {
        $('#comboBoxMenu').find('a[data-value="' + idValue + '"]').addClass('active');
    }
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
    } else if (modeOfPayment == '3') {
        checkList.style.display = "none";
        cCheckNo.style.display = "none";
        onlineBankList.style.display = "block";
        cRefNo.style.display = "block";
        cBankOnlineInput.setAttribute("required", "true");
        cBankCheckInput.removeAttribute("required");
    } else {
        checkList.style.display = "none";
        cCheckNo.style.display = "none";
        cRefNo.style.display = "none";
        onlineBankList.style.display = "none";
        cBankCheckInput.removeAttribute("required");
        cBankOnlineInput.removeAttribute("required");
        cBankCheckInput.value = "";
        cBankOnlineInput.value = "";
    }
}
