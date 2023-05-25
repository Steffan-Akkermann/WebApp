$(document).ready(function() {
    $('#loginForm').submit(function(event) {
        event.preventDefault();

        var username = $('#username').val();
        var password = $('#password').val();

        $.ajax({
            url: '/login',
            method: 'POST',
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '/profile';
                } else {
                    alert('Login failed. ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred during login.');
            }
        });
    });
});
