function start_loader() {
    $('body').append('<div id="preloader"><div class="loader-holder"><div></div><div></div><div></div><div></div></div></div>');
}

function end_loader() {
    $('#preloader').fadeOut('fast', function() {
        $('#preloader').remove();
    });
}
window.alert_toast = function($msg = 'TEST', $bg = 'success', $pos = '') {
    var Toast = Swal.mixin({
        toast: true,
        position: $pos || 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
    Toast.fire({
        icon: $bg,
        title: $msg
    });
};