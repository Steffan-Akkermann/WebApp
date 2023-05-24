$(document).ready(function() {
    $('#registrationForm').submit(function(event) {
        event.preventDefault();

        var username = $('#username').val();
        var password = $('#password').val();
        var email = $('#email').val();

        $.ajax({
            url: '/registration',
            method: 'POST',
            data: {
                username: username,
                password: password,
                email: email
            },
            success: function(response) {
                if (response.success) {
                    alert('Registration successful. Please login to proceed.');
                    window.location.href = '/login';
                } else {
                    alert('Registration failed. ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred during registration.');
            }
        });
    });
});
