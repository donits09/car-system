function toggleForm() {
    var searchType = document.getElementById("search_type").value;
    document.getElementById("account-form").style.display = searchType === "account" ? "block" : "none";
    document.getElementById("location-form").style.display = searchType === "location" ? "block" : "none";
    document.getElementById("last-name-form").style.display = searchType === "last-name" ? "block" : "none";
}

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
                    if (type === 'last-name' && response.data.length > 1) {
                        showMultipleResults(response.data);
                    } else if (type === 'location' && response.data.length > 1) {
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
    document.getElementById('accno').value = data.c_account_no;

    var phase = data.c_account_no.substring(0, 3);
    var block = data.c_account_no.substring(3, 6).replace(/^0+/, ''); 
    var lot = data.c_account_no.substring(6, 8);

    var xhr = new XMLHttpRequest();
    var url = 'fetch_phase_details.php?phase=' + encodeURIComponent(phase);
    xhr.open('GET', url, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var phase_details = JSON.parse(xhr.responseText);
            if (phase_details && phase_details.c_acronym) {
                document.getElementById('buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + data.c_type + ')';
                document.getElementById('car_buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + data.c_type + ')';
            } else {
                document.getElementById('buyer_loc').value = "-----";
                document.getElementById('car_buyer_loc').value = "-----";
            }
        } else {
            document.getElementById('buyer_loc').value = "-----";
            document.getElementById('car_buyer_loc').value = "-----";
        }
    };
    xhr.onerror = function() {
        document.getElementById('buyer_loc').value = "-----";
        document.getElementById('car_buyer_loc').value = "-----";
    };
    xhr.send();
}

var currentPage = 1;
var rowsPerPage = 10; 
var multipleResultsData; 

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
    document.getElementById('accno').value = buyer.c_account_no;

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
                document.getElementById('buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
                document.getElementById('car_buyer_loc').value = phase_details.c_acronym + ' B' + block + ' L' + lot + ' (' + buyer.c_type + ')';
            } else {
                document.getElementById('buyer_loc').value = "-----";
                document.getElementById('car_buyer_loc').value = "-----";
            }
        } else {
            document.getElementById('buyer_loc').value = "-----";
            document.getElementById('car_buyer_loc').value = "-----";
        }
    };
    xhr.onerror = function() {
        document.getElementById('buyer_loc').value = "-----";
        document.getElementById('car_buyer_loc').value = "-----";
    };
    xhr.send();

    $('#multipleResultsModal').modal('hide'); 
    
    updateCarList();
    calculateTotalAmount();
}

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
function calculateTotalAmount() {
    var table = document.getElementById("car-list-table");
    var tbody = table.getElementsByTagName("tbody")[0];
    var rows = tbody.getElementsByTagName("tr");
    var total = 0;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].style.display !== "none") {
            var amountCell = rows[i].getElementsByTagName("td")[6]; 
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
