document.addEventListener('DOMContentLoaded', function() {
    var passwordField = document.getElementById('login_password');
    var showPasswordCheckbox = document.getElementById('login_showPassword');

    showPasswordCheckbox.addEventListener('change', function() {
        var isChecked = this.checked;
        passwordField.type = isChecked ? 'text' : 'password';
    });
});