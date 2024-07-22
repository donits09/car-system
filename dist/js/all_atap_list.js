    //$(document).ready(function() {
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

    $('#create_new_atap').click(function() {
        loadModal('Create New ATAP', '../atap/manage_atap.php', '#createCarModal');
    });

    $('#create_other_atap').click(function() {
        loadModal('Create New ATAP', '../atap/manage_other_atap.php', '#createCarModal');
    });

    $(document).on('click', '.view_data', function() {
        var accountId = $(this).data('id');
        loadModal('Car Details', 'view_car.php?id=' + accountId, '#viewModal');
    });

    $(document).on('click', '.view_atap', function() {
        var atapId = $(this).data('id');
        var atapNo = $(this).data('no');
        loadModal('ATAP Details', '../atap/view_atap.php?id=' + atapId + '&no=' + atapNo, '#viewModal');
    });

    $(document).on('click', '.delete_data', function() {
        var atapId = $(this).data('id');
        var atapNo = $(this).data('no');
        _conf("Are you sure you want to cancel this ATAP permanently?", delete_atap, [atapId, atapNo]);
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
