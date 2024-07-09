$(document).ready(function() {
    $.ajax({
        url: '../../../admin/settings/users/fetch_user_details.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                alert(data.error);
            } else {
                $('#id').val(data.id);
                $('#c_employee_code').val(data.c_employee_code);
                $('#c_realname').val(data.c_realname);
                $('#c_department').val(data.c_department);
                $('#c_password').val('');
                /* $('#password').val(data.c_password); */
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
            alert('Error fetching user details');
        }
    });

    $('#user_details').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
    
        start_loader();
    
        $.ajax({
            url: "../../../classes/Master.php?f=save_my_account",
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
                        location.reload();
                    }, 2000);
                } else if (resp && resp.status === 'failed' && resp.err) {
                    alert_toast("An error occurred: " + resp.err, 'error');
                } else {
                    alert_toast("An unexpected error occurred", 'error');
                }
    
                if (resp && resp.logout) {
                    alert('Your password has been updated. You are now logged out.');
                    window.location.href = '../../../auth/auto_logout.php';
                }
    
                end_loader();
            }
        });
    });    
});