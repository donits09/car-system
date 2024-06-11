document.addEventListener("DOMContentLoaded", function() {
    var myTab = document.getElementById('myTab');
    var tabLinks = myTab.querySelectorAll('a.nav-link');
    var activeTab = localStorage.getItem('activeTab') || 'user-tab';

    document.getElementById(activeTab).classList.add('active');
    document.getElementById(activeTab.replace('-tab', '')).classList.add('show', 'active');

    if (activeTab === 'user-tab') {
        $('#user-data-table').DataTable();
    } else if (activeTab === 'logs-tab') {
        $('#logs-data-table').DataTable({
            "pageLength": 25
        });
    }

    tabLinks.forEach(function(tabLink) {
        tabLink.addEventListener('click', function() {
            localStorage.setItem('activeTab', tabLink.id);
            if (tabLink.id === 'user-tab') {
                if (!$.fn.DataTable.isDataTable('#user-data-table')) {
                    $('#user-data-table').DataTable();
                }
            } else if (tabLink.id === 'logs-tab') {
                if (!$.fn.DataTable.isDataTable('#logs-data-table')) {
                    $('#logs-data-table').DataTable({
                        "pageLength": 25
                    });
                }
            }
        });
    });
});