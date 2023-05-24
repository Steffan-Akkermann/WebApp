$(document).ready(function() {
    var username = $('#username').text();

    $('#logoutButton').click(function() {
        $.ajax({
            url: '/logout',
            method: 'GET',
            success: function() {
                window.location.href = '/login';
            },
            error: function() {
                alert('An error occurred during logout.');
            }
        });
    });
});
