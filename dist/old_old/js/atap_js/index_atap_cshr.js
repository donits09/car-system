
//  ATAP MAIN FUNCTIONS
function filterTableAtap() {
    var input, filter, table, tbody, tr, td, i, txtValue;
    input = document.getElementById("searchInputAtap");
    filter = input.value.toUpperCase();
    table = document.getElementById("atap-list-table");
    tbody = table.getElementsByTagName("tbody")[0]; 

    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        tds = tr[i].getElementsByTagName("td");
        var found = false;
        for (var j = 0; j < tds.length; j++) {
            td = tds[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        if (found) {
            tr[i].style.display = ""; 
        } else {
            tr[i].style.display = "none"; 
        }
    }
    calculateTotalAtapAmount();
}

$(document).ready(function() {
    $('#atap-list-tab').on('click', function(e) {
        e.preventDefault(); 

        var username = $('#username').val();
        var buyer_acc_no = $('#buyer_acc_no').val();

        $.ajax({
            url: '../atap/fetch_atap_list.php',
            type: 'GET',
            data: { username: username, buyer_acc_no: buyer_acc_no },
            success: function(response) {
              
                $('#atap-list-body').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching car list:', error);
              
            }
        });
    });
});

function delete_atap(atapId, atapNo) {
    start_loader();
    $.ajax({
        url: "../../classes/Master.php?f=delete_atap",
        method: "POST",
        data: { atapId: atapId, atapNo: atapNo },
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
                    updateAtapList(); 
                    end_loader();
                }, 1000);
            } else if (resp && resp.status === 'failed' && resp.err) {
                alert_toast("An error occurred: " + resp.err, 'error');
                end_loader();
            } else {
                alert_toast("An unexpected error occurred", 'error');
                end_loader();
            }
        }
    });
}

function loadAtapList(username = '', buyer_acc_no = '') {
    $.ajax({
        url: '../atap/fetch_atap_list.php',
        type: 'GET',
        data: { username: username, buyer_acc_no: buyer_acc_no },
        success: function(response) {
            $('#atap-list-body').html(response);
            calculateTotalAtapAmount();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching ATAP list:', error);
        }
    });
    calculateTotalAtapAmount();
}

function updateAtapList() {
    const username = $('#username').val(); 
    const accountNo = $('#buyer_acc_no').val();

    fetch(`../atap/fetch_atap_list.php?username=${username}&buyer_acc_no=${accountNo}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('atap-list-body').innerHTML = data;
            calculateTotalAtapAmount(); 
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
        calculateTotalAtapAmount();
}

// function calculateTotalAtapAmount() {
//     var totalAmount = 0;
//     $('#atap-list-body tr').each(function() {
//         var amount = parseFloat($(this).find('td:nth-child(5)').text().replace(/[^0-9.-]+/g,""));
//         if (!isNaN(amount)) {
//             totalAmount += amount;
//         }
//     });
//     $('#totalAtapAmount').text(totalAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
// }

function calculateTotalAtapAmount() {
    var table = document.getElementById("atap-list-table");
    var tbody = table.getElementsByTagName("tbody")[0];
    var rows = tbody.getElementsByTagName("tr");
    var total = 0;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== "none") { 
            var amountCell = rows[i].getElementsByTagName("td")[4]; 
            if (amountCell) {
                var amountValue = amountCell.textContent.trim().replace(/[^0-9.-]+/g, ''); 
                total += parseFloat(amountValue); 
            }
        }
    }

    document.getElementById("totalAtapAmount").textContent = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); 
}



$(document).ready(function() {
    function searchByAccount() {
        var acc_no = $('#acc_no').val();
        loadAtapList('', acc_no);
        return false;
    }

    function searchByLocation() {
        var phase = $('#phase').val();
        var block = $('#block').val();
        var lot = $('#lot').val();
        var buyer_acc_no = phase + '-' + block + '-' + lot;
        loadAtapList('', buyer_acc_no);
        return false;
    }

    function searchByLastName() {
        var last_name = $('#last_name').val();
        var first_name = $('#first_name').val();
        var buyer_acc_no = last_name + '-' + first_name;
        loadAtapList('', buyer_acc_no);
        return false;
    }

    $('#searchAcc').on('click', function(e) {
        e.preventDefault();
        searchByAccount();
    });

    $('#searchLoc').on('click', function(e) {
        e.preventDefault();
        searchByLocation();
    });

    $('#searchName').on('click', function(e) {
        e.preventDefault();
        searchByLastName();
    });

    $('#atap-list-tab').on('click', function(e) {
        e.preventDefault();
        var username = $('#username').val();
        var buyer_acc_no = $('#buyer_acc_no').val();
        loadAtapList(username, buyer_acc_no);
    });

    calculateTotalAtapAmount(); 
});

