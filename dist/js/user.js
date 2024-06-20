

$(document).ready(function() {
    $('#addUserForm').submit(function(e) {
        e.preventDefault();
    });
    $('#edit_data').submit(function(e) {
        e.preventDefault();
    });

    $('.edit_data').on('click', function() {
        var id = $(this).data('id');
        var employee_code = $(this).data('employee_code');
        var password = $(this).data('password'); // Get the password, but don't use it
        var realname = $(this).data('realname');
        var group = $(this).data('group');
        var department = $(this).data('department');
    
        $('#edit_user_id').val(id);
        $('#edit_employee_code').val(employee_code);
        $('#edit_password').val(''); // Always set the password field to blank
        $('#edit_realname').val(realname);
        $('#edit_group').val(group);
        $('#edit_department').val(department);
    
        $('#editUserModal').modal('show');
    });       

    $('#addUserForm').submit(function(e) {
        e.preventDefault();
        // if (confirm("Are you sure you want to save this car payment?")) {
            var _this = $(this);

            start_loader();

            $.ajax({
                url: "../../../classes/Master.php?f=save_car_users",
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
                    } else if (resp && resp.status === 'failed') {
                        alert_toast(' &#10060;' + resp.msg, 'failed');
                    } else {
                        alert_toast("An unexpected error occurred", 'error');
                    }
                    end_loader();
                }

            });
        //}
    });

    $('#editUserForm').submit(function(e) {
        e.preventDefault();
       
        //if (confirm("Are you sure you want to save this car payment?")) {
            var _this = $(this);
            start_loader();

            $.ajax({
                url: "../../../classes/Master.php?f=save_car_users",
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
                    } else if (resp && resp.status === 'failed') {
                        alert_toast(' &#10060;' + resp.msg, 'failed');
                    } else {
                        alert_toast("An unexpected error occurred", 'error');
                    }
                    end_loader();
                }

            });
            //}
        });

    
    var userIdToDelete = null;

    $(document).on('click', '.delete_data', function() {
        userIdToDelete = $(this).data('id');
        $('#confirmDeleteModal').modal('show');
    });

    $('#confirmDeleteBtn').click(function() {
        if (userIdToDelete !== null) {
            delete_user(userIdToDelete);
        }
    });

    function delete_user(userId) {
        start_loader();
        $.ajax({
            url: "../../../classes/Master.php?f=delete_user",
            method: "POST",
            data: { userId: userId },
            dataType: "json",
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
                end_loader();
            }
        });
        $('#confirmDeleteModal').modal('hide');
    }

});