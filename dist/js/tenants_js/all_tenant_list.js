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

    $('#create_new_tenant').click(function() {
        loadModal('Create New Tenant', '../tenants/manage_tenant.php', '#createCarModal');
    });

    $(document).on('click', '.view_tenant', function() {
        var accountId = $(this).data('id');
        loadModal('Tenant Details', '../other_fees/view_or.php?id=' + accountId, '#viewModal');
    });

    $(document).on('click', '.delete_tenant', function() {
        var orId = $(this).data('id');
        var orNo = $(this).data('or-no');
        var atapNo = $(this).data('atap-no');
        
        if (confirm("Are you sure you want to cancel this OR?")) {
            delete_or(orId, orNo, atapNo);
        }
    });
    
    $(document).on('click', '.edit_tenant', function() {
        var accountId = $(this).data('id');
        var accountNo = $(this).data('account-no');
    
        if (!accountNo) {
            loadModal('Edit OR Details', '../other_fees/manage_other_of.php?id=' + accountId, '#createCarModal');
        } else {
            loadModal('Edit OR Details', '../other_fees/manage_of.php?id=' + accountId, '#createCarModal');
        }
    });

    window._conf = function(msg, func, params) {
        $('#confirm_modal .modal-body').html(msg);
        $('#confirm_modal #confirm').off('click').on('click', function() {
            func.apply(this, params);
        });
        $('#confirm_modal').modal('show');
    };
});

function clearAmt(){
    var txtamt = document.getElementById('c_or_amount').value;

    if(txtamt == '0.00'){
        document.getElementById('c_or_amount').value='';
    }
}
