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
    
        $('#create_new_of').click(function() {
            loadModal('Create New OR', '../other_fees/manage_of.php', '#createCarModal');
        });
    
        $('#create_other_new').click(function() {
            loadModal('Create New OR', '../other_fees/manage_other_of.php', '#createCarModal');
        });
    
        $(document).on('click', '.view_or', function() {
            var accountId = $(this).data('id');
            loadModal('OR Details', '../other_fees/view_or.php?id=' + accountId, '#viewModal');
        });
    
        // $(document).on('click', '.view_atap', function() {
        //     var atapId = $(this).data('id');
        //     var atapNo = $(this).data('no');
        //     loadModal('ATAP Details', '../atap/view_atap.php?id=' + atapId + '&no=' + atapNo, '#viewModal');
        // });
    
        $(document).on('click', '.delete_or', function() {
            var orId = $(this).data('id');
            var orNo = $(this).data('or-no');
            _conf("Are you sure you want to cancel this transaction permanently?", delete_or, [orId, orNo]);
        });
    
        $(document).on('click', '.edit_or', function() {
            var accountId = $(this).data('id');
            var accountNo = $(this).data('account-no');
        
            if (!accountNo) {
                loadModal('Edit OR Details', '../other_fees/manage_other_of.php?id=' + accountId, '#createCarModal');
            } else {
                loadModal('Edit OR Details', '../other_fees/manage_of.php?id=' + accountId, '#createCarModal');
            }
        });
    
        // $(document).on('click', '.edit_atap_spec', function() {
        //     var atapId = $(this).data('id');
        //     var atapNo = $(this).data('no');
        //     var accountNo = $(this).data('acc-no');
        //     var modalTitle = 'Edit ATAP Details ';
        //     var modalSelector = '#createCarModal';
        //     var url;
        
        //     url = '../atap/manage_atap.php?id=' + atapId + '&no=' + atapNo + '&acc-no=' + accountNo;
            
        //     loadModal(modalTitle, url, modalSelector);
        // });
        
        
    
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
    