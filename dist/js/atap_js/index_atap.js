//Updating ng car-list-table body everytime na may click function or searching
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


//Para sa search form
function searchBuyer(type) {
    var formData = new FormData();

    if (type === 'account') {
        var acc_no = document.getElementById('acc_no').value;
        formData.append('acc_no', acc_no);
    } else if (type === 'location') {
        var phase = document.getElementById('phase').value;
        var block = document.getElementById('block').value;
        var lot = document.getElementById('lot').value;
        var loc;

        if (block.length === 1) {
            block = "00" + block;
        } else if (block.length === 2) {
            block = "0" + block;
        }

        if (lot.length === 1) {
            lot = "0" + lot;
        } 
        loc = phase + block + lot;

        formData.append('loc', loc); 
    } else if (type === 'last-name') {
        var last_name = document.getElementById('last_name').value;
        var first_name = document.getElementById('first_name').value;
        formData.append('last_name', last_name);
        formData.append('first_name', first_name); 
    }

    var xhr = new XMLHttpRequest();
    var url = 'search_buyer.php';

    var params = [];
    formData.forEach(function(value, key) {
        params.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
    });
    var queryString = params.join('&');

    xhr.open('GET', url + '?' + queryString, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                if (Array.isArray(response.data)) {
                    if ((type === 'last-name' || type === 'location') && response.data.length > 1) {
                        showMultipleResults(response.data);
                    } else {
                        fillBuyerDetails(response.data[0]);
                    }
                } else {
                    fillBuyerDetails(response.data);
                }
            } else {
                alert('No data found');
            }
            updateCarList(); 
            calculateTotalAmount();
        } else {
            alert('Error: ' + xhr.status);
        }
    };
    xhr.send();
    
    return false;
}

function toggleForm() {
    var searchType = document.getElementById("search_type").value;
    document.getElementById("account-form").style.display = searchType === "account" ? "block" : "none";
    document.getElementById("location-form").style.display = searchType === "location" ? "block" : "none";
    document.getElementById("last-name-form").style.display = searchType === "last-name" ? "block" : "none";

    clearFormFields();
}

function clearFormFields() {
    var forms = document.querySelectorAll('.filter-form');
    forms.forEach(function(form) {
        form.reset();
    });
}

//populate ng textboxes and so on
function fillBuyerDetails(data) {
    document.getElementById('buyer_acc_no').value = data.c_account_no;
    document.getElementById('buyer_date_of_sale').value = data.c_date_of_sale; 
    document.getElementById('buyer_acc_status').value = data.c_account_status; 
    document.getElementById('buyer_lname').value = data.c_b1_last_name;
    document.getElementById('buyer_fname').value = data.c_b1_first_name;
    document.getElementById('buyer_mname').value = data.c_b1_middle_name;
    document.getElementById('buyer_address').value = data.c_address; 
    document.getElementById('buyer_remarks').value = data.c_remarks; 
    document.getElementById('fullname').value = data.c_b1_first_name + ' ' + data.c_b1_last_name;
    document.getElementById('atap_fullname').value = data.c_b1_first_name + ' ' + data.c_b1_last_name;
    document.getElementById('accno').value = data.c_account_no;
    document.getElementById('atap_accno').value = data.c_account_no;
    document.getElementById('or_accno').value = data.c_account_no;
    document.getElementById('or_fullname').value = data.c_b1_first_name + ' ' + data.c_b1_last_name;
    
    document.getElementById('acct_no').value = data.c_account_no;
    document.getElementById('fullname_pr').value = data.c_b1_first_name + ' ' + data.c_b1_last_name;

    document.getElementById('buyer_bal').value = parseFloat(data.c_balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('buyer_tcp').value = parseFloat(data.c_net_tcp).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (data.c_retention == '1') {
        document.getElementById('buyer_ret').value = "Retention";
    } else {
        document.getElementById('buyer_ret').value = "------";
    }
    document.getElementById('buyer_email').value = data.c_email;
    document.getElementById('buyer_mobile').value = data.c_mobile_no;

    var c_lid = data.c_account_no.substring(0, 8);
    var phase = data.c_account_no.substring(0, 3);
    var block = data.c_account_no.substring(3, 6).replace(/^0+/, ''); 
    var lot = data.c_account_no.substring(6, 8);

    var xhrTitle = new XMLHttpRequest();
    var urlTitle = 'fetch_title_details.php?c_lid=' + encodeURIComponent(c_lid);
    xhrTitle.open('GET', urlTitle, true);
    xhrTitle.onload = function() {
        if (xhrTitle.status === 200) {
            var lid_details = JSON.parse(xhrTitle.responseText);
            if (lid_details && lid_details.c_doc_tct_jun_2020) {
                document.getElementById('buyer_title').value = lid_details.c_doc_tct_jun_2020;
            } else {
                document.getElementById('buyer_title').value = "-----";
            }
        } else {
            document.getElementById('buyer_title').value = "-----";
        }
    };
    xhrTitle.onerror = function() {
        document.getElementById('buyer_title').value = "-----";
    };
    xhrTitle.send();

    var xhrPhase = new XMLHttpRequest();
    var urlPhase = 'fetch_phase_details.php?phase=' + encodeURIComponent(phase);
    xhrPhase.open('GET', urlPhase, true);
    xhrPhase.onload = function() {
        if (xhrPhase.status === 200) {
            var phase_details = JSON.parse(xhrPhase.responseText);
            if (phase_details && phase_details.c_acronym) {
                var buyerLoc = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + data.c_type + ')';
                document.getElementById('buyer_loc').value = buyerLoc;
                document.getElementById('car_buyer_loc').value = buyerLoc;
                document.getElementById('atap_car_buyer_loc').value = buyerLoc;
                document.getElementById('car_buyer_loc_pr').value = buyerLoc;
                document.getElementById('or_buyer_loc').value = buyerLoc;
            } else {
                document.getElementById('buyer_loc').value = "-----";
                document.getElementById('car_buyer_loc').value = "-----";
                document.getElementById('atap_car_buyer_loc').value = "-----";
                document.getElementById('car_buyer_loc_pr').value = "-----";
                document.getElementById('or_buyer_loc').value = "-----";
            }
        } else {
            document.getElementById('buyer_loc').value = "-----";
            document.getElementById('car_buyer_loc').value = "-----";
            document.getElementById('atap_car_buyer_loc').value = "-----";
            document.getElementById('car_buyer_loc_pr').value = "-----";
            document.getElementById('or_buyer_loc').value = "-----";
        }
    };
    xhrPhase.onerror = function() {
        document.getElementById('buyer_loc').value = "-----";
        document.getElementById('car_buyer_loc').value = "-----";
        document.getElementById('atap_car_buyer_loc').value = "-----";
        document.getElementById('car_buyer_loc_pr').value = "-----";
        document.getElementById('or_buyer_loc').value = "-----";
    };
    xhrPhase.send();
}


var currentPage = 1;
var rowsPerPage = 10; 
var multipleResultsData; 

//displaying of accounts - yung modal kineme
function showMultipleResults(data, page) {
    currentPage = page || 1;
    multipleResultsData = data;
    var startIndex = (currentPage - 1) * rowsPerPage;
    var endIndex = startIndex + rowsPerPage;
    var paginatedData = data.slice(startIndex, endIndex);

    var modalBody = document.getElementById('multipleResultsBody');
    modalBody.innerHTML = '';

    paginatedData.forEach(function(buyer) {
        var tr = document.createElement('tr');
        var tdAccountNo = document.createElement('td');
        var tdLastName = document.createElement('td');
        var tdFirstName = document.createElement('td');
        var tdMiddleName = document.createElement('td');
        var tdStats = document.createElement('td');
        var tdButton = document.createElement('td');
    
        tdAccountNo.textContent = buyer.c_account_no;
        tdLastName.textContent = buyer.c_b1_last_name;
        tdFirstName.textContent = buyer.c_b1_first_name;
        tdMiddleName.textContent = buyer.c_b1_middle_name;
    
        var phase = buyer.c_account_no.substring(0, 3);
        var block = buyer.c_account_no.substring(3, 6).replace(/^0+/, ''); 
        var lot = buyer.c_account_no.substring(6, 8);
    
     
        var xhr = new XMLHttpRequest();
        var url = 'fetch_phase_details.php?phase=' + encodeURIComponent(phase);
        xhr.open('GET', url, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var phase_details = JSON.parse(xhr.responseText);
                if (phase_details && phase_details.c_acronym) {
                    tdStats.textContent = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
                } else {
                    tdStats.textContent = "-----";
                }
            } else {
                tdStats.textContent = "-----";
            }
        };
        xhr.onerror = function() {
            tdStats.textContent = "-----";
        };
        xhr.send();
    
        var button = document.createElement('button');
        button.className = 'btn btn-primary';
        button.textContent = 'Select';
        button.onclick = function() {
            selectBuyer(buyer);
        };
        tdButton.appendChild(button);
    
        tr.appendChild(tdAccountNo);
        tr.appendChild(tdLastName);
        tr.appendChild(tdFirstName);
        tr.appendChild(tdMiddleName);
        tr.appendChild(tdStats);
        tr.appendChild(tdButton);
    
        modalBody.appendChild(tr);
    });
    
    

    var totalPages = Math.ceil(data.length / rowsPerPage);
    var paginationHtml = '<nav aria-label="Page navigation">' +
                        '<ul class="pagination">';
                        
    if (currentPage > 1) {
        paginationHtml += '<li class="page-item">' +
                        '<a class="page-link" href="#" onclick="showMultipleResultsPagination(' + (currentPage - 1) + ')">Previous</a>' +
                        '</li>';
    }

    paginationHtml += '<li class="page-item">' +
                    '<a class="page-link" href="#" onclick="showMultipleResultsPagination(' + (currentPage + 1) + ')">Next</a>' +
                    '</li>';

    paginationHtml += '</ul></nav>';

    modalBody.insertAdjacentHTML('beforeend', paginationHtml);

    $('#multipleResultsModal').modal('show');
}

//pagination ng modal
function showMultipleResultsPagination(page) {
    showMultipleResults(multipleResultsData, page); 
    return false; 
}

function selectBuyer(buyer) {
    document.getElementById('buyer_acc_no').value = buyer.c_account_no;
    document.getElementById('buyer_date_of_sale').value = buyer.c_date_of_sale; 
    document.getElementById('buyer_acc_status').value = buyer.c_account_status; 
    document.getElementById('buyer_lname').value = buyer.c_b1_last_name;
    document.getElementById('buyer_fname').value = buyer.c_b1_first_name;
    document.getElementById('buyer_mname').value = buyer.c_b1_middle_name;
    document.getElementById('buyer_address').value = buyer.c_address; 
    document.getElementById('buyer_remarks').value = buyer.c_remarks; 
    document.getElementById('fullname').value = buyer.c_b1_first_name + ' ' + buyer.c_b1_last_name;
    document.getElementById('atap_fullname').value = buyer.c_b1_first_name + ' ' + buyer.c_b1_last_name;
    document.getElementById('accno').value = buyer.c_account_no;
    document.getElementById('atap_accno').value = buyer.c_account_no;
    document.getElementById('or_fullname').value = buyer.c_b1_first_name + ' ' + buyer.c_b1_last_name;
    
    document.getElementById('acct_no').value = buyer.c_account_no;
    document.getElementById('fullname_pr').value = buyer.c_b1_first_name + ' ' + buyer.c_b1_last_name;

    document.getElementById('buyer_bal').value = parseFloat(buyer.c_balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('buyer_tcp').value = parseFloat(buyer.c_net_tcp).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (buyer.c_retention == '1') {
        document.getElementById('buyer_ret').value = "Retention";
    } else {
        document.getElementById('buyer_ret').value = "------";
    }
    document.getElementById('buyer_email').value = buyer.c_email;
    document.getElementById('buyer_mobile').value = buyer.c_mobile_no;

    var phase = buyer.c_account_no.substring(0, 3);
    var block = buyer.c_account_no.substring(3, 6).replace(/^0+/, ''); 
    var lot = buyer.c_account_no.substring(6, 8);
    var c_lid = buyer.c_account_no.substring(0, 8);

    var xhrTitle = new XMLHttpRequest();
    var urlTitle = 'fetch_title_details.php?c_lid=' + encodeURIComponent(c_lid);
    xhrTitle.open('GET', urlTitle, true);
    xhrTitle.onload = function() {
        if (xhrTitle.status === 200) {
            var lid_details = JSON.parse(xhrTitle.responseText);
            if (lid_details && lid_details.c_doc_tct_jun_2020) {
                document.getElementById('buyer_title').value = lid_details.c_doc_tct_jun_2020;
            } else {
                document.getElementById('buyer_title').value = "-----";
            }
        } else {
            document.getElementById('buyer_title').value = "-----";
        }
    };
    xhrTitle.onerror = function() {
        document.getElementById('buyer_title').value = "-----";
    };
    xhrTitle.send();


    var xhr = new XMLHttpRequest();
    var url = 'fetch_phase_details.php?phase=' + encodeURIComponent(phase);
    xhr.open('GET', url, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var phase_details = JSON.parse(xhr.responseText);
            if (phase_details && phase_details.c_acronym) {
                document.getElementById('buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
                document.getElementById('car_buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
                document.getElementById('atap_car_buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
                document.getElementById('car_buyer_loc_pr').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
                document.getElementById('or_buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
            } else {
                document.getElementById('buyer_loc').value = "-----";
                document.getElementById('car_buyer_loc').value = "-----";
                document.getElementById('atap_car_buyer_loc').value = "-----";
                document.getElementById('car_buyer_loc_pr').value = "-----";
                document.getElementById('or_buyer_loc').value = "-----";
            }
        } else {
            document.getElementById('buyer_loc').value = "-----";
            document.getElementById('car_buyer_loc').value = "-----";
            document.getElementById('atap_car_buyer_loc').value = "-----";
            document.getElementById('car_buyer_loc_pr').value = "-----";
            document.getElementById('or_buyer_loc').value = "-----";
        }
    };
    xhr.onerror = function() {
        document.getElementById('buyer_loc').value = "-----";
        document.getElementById('car_buyer_loc').value = "-----";
        document.getElementById('atap_car_buyer_loc').value = "-----";
        document.getElementById('car_buyer_loc_pr').value = "-----";
        document.getElementById('or_buyer_loc').value = "-----";
    };
    xhr.send();

    $('#multipleResultsModal').modal('hide'); 
    
    updateCarList();
    calculateTotalAmount();
}

//search textbox CAR
function filterTable() {
    var input, filter, table, tbody, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("car-list-table");
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
    calculateTotalAmount();
}

//dynamic total calculation ng amount column (CAR)
function calculateTotalAmount() {
    var table = document.getElementById("car-list-table");
    var tbody = table.getElementsByTagName("tbody")[0];
    var rows = tbody.getElementsByTagName("tr");
    var total = 0;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== "none") {
            var amountCell = rows[i].getElementsByTagName("td")[7]; 
            if (amountCell) {
                var amountValue = amountCell.textContent.trim().replace(',', '');
                total += parseFloat(amountValue);
            }
        }
    }

    document.getElementById("totalAmount").textContent = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}


function searchAndCalculateTotal(event, type) {
    event.preventDefault();
    searchBuyer(type);

    setTimeout(function() {
        calculateTotalAmount();
    }, 150);
}

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

    document.getElementById("totalAtapAmount").textContent = total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); // Format total with commas
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

// VALIDATION AND FORMATTING OF INPUTS

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
    $('#or-list-tab').on('click', function(e) {
        e.preventDefault(); 

        var username = $('#username').val();
        var buyer_acc_no = $('#buyer_acc_no').val();

        $.ajax({
            url: '../other_fees/fetch_or_list.php',
            type: 'GET',
            data: { username: username, buyer_acc_no: buyer_acc_no },
            success: function(response) {
              
                $('#or-list-body').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching car list:', error);
              
            }
        });
    });
});