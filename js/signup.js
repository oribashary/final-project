$(document).ready(function() {
    var signupForm = $('#signupForm');
    var emailInput = $('#email');
    var emailError = $('#emailError');
    var passwordInput = $('#password');
    var confirmPasswordInput = $('#confirmPassword');
    var passwordError = $('#passwordError');
    var roleInput = $('#role');

    signupForm.on('submit', function(e) {
        e.preventDefault();

        emailError.text('');
        passwordError.text('');

        var email = emailInput.val();
        var password = passwordInput.val();
        var confirmPassword = confirmPasswordInput.val();

        if (password !== confirmPassword) {
            passwordError.text('Password and Confirm Password do not match.');
            return;
        }

        this.submit();
    });
});