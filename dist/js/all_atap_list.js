function handleCreateNewATAP() {
    var specIdNo = document.getElementById('spec_idno').value;
    document.getElementById('c_account_no').value = specIdNo;
    $('#createCarModal').modal('show');
}

function updateButtonStates() {
    var specIdNo = document.getElementById('spec_idno').value;

    if (specIdNo != '----------') {
        document.getElementById('create_new_atap').classList.remove('disabled');
        document.getElementById('create_new_atap').style.pointerEvents = 'auto';
        document.getElementById('create_other_atap').classList.add('disabled');
        document.getElementById('create_other_atap').style.pointerEvents = 'none';
    } else {
        document.getElementById('create_new_atap').classList.add('disabled');
        document.getElementById('create_new_atap').style.pointerEvents = 'none';
        document.getElementById('create_other_atap').classList.remove('disabled');
        document.getElementById('create_other_atap').style.pointerEvents = 'auto';
    }
}
document.addEventListener('DOMContentLoaded', updateButtonStates);

function handleCreateNewATAP() {
    var specIdNo = document.getElementById('spec_idno') ? document.getElementById('spec_idno').value : '';
    console.log('specIdNo:', specIdNo); 

    if (specIdNo) {
        var url = '../atap/manage_atap.php?spec_idno=' + encodeURIComponent(specIdNo);
        loadModal('Create New ATAP', url, '#createCarModal');
    } else {
        loadModal('Create New ATAP', '../atap/manage_atap.php', '#createCarModal');
    }
}

document.getElementById('create_new_atap').addEventListener('click', handleCreateNewATAP);

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

$(document).on('click', '.view_atap', function() {
    var atapId = $(this).data('id');
    var atapNo = $(this).data('no');
    $('#viewModal').modal('hide'); 
    setTimeout(function() {
        loadModal('ATAP Details', '../atap/view_atap.php?id=' + atapId + '&no=' + atapNo, '#viewModal');
    }, 500); 
});

$('#create_other_atap').click(function() {
    loadModal('Create New ATAP', '../atap/manage_other_atap.php', '#createCarModal');
});

$(document).on('click', '.view_data', function() {
    var accountId = $(this).data('id');
    loadModal('Car Details', 'view_car.php?id=' + accountId, '#viewModal');
});

$(document).on('click', '.delete_data', function() {
    var atapId = $(this).data('id');
    var atapNo = $(this).data('no');
    _conf("Are you sure you want to cancel this transaction permanently?", delete_atap, [atapId, atapNo]);
});

$(document).on('click', '.edit_atap', function() {
    var atapId = $(this).data('id');
    var atapNo = $(this).data('no');
    var accountNo = $(this).data('acc-no');
    var modalTitle = 'Edit ATAP Details ';
    var modalSelector = '#createCarModal';
    var url;

    if (!accountNo) {
        url = '../atap/manage_other_atap.php?id=' + atapId + '&no=' + atapNo + '&acc-no=' + accountNo;
    } else {
        url = '../atap/manage_atap.php?id=' + atapId + '&no=' + atapNo + '&acc-no=' + accountNo;
    }

    loadModal(modalTitle, url, modalSelector);
});

$(document).on('click', '.edit_atap_spec', function() {
    var atapId = $(this).data('id');
    var atapNo = $(this).data('no');
    var accountNo = $(this).data('acc-no');
    var modalTitle = 'Edit ATAP Details ';
    var modalSelector = '#createCarModal';
    var url;

    url = '../atap/manage_atap.php?id=' + atapId + '&no=' + atapNo + '&acc-no=' + accountNo;
    
    loadModal(modalTitle, url, modalSelector);
});

window._conf = function(msg, func, params) {
    $('#confirm_modal .modal-body').html(msg);
    $('#confirm_modal #confirm').off('click').on('click', function() {
        func.apply(this, params);
    });
    $('#confirm_modal').modal('show');
};
//});

