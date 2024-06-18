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
                            updateCarList();
                           
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
