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

    value = value.replace(/[^a-zA-Z0-9\s]/g, '');
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

