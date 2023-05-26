document.addEventListener('DOMContentLoaded', function() {
    var passwordField = document.getElementById('register_password');
    var showPasswordCheckbox = document.getElementById('register_showPassword');

    showPasswordCheckbox.addEventListener('change', function() {
        var isChecked = this.checked;
        passwordField.type = isChecked ? 'text' : 'password';
    });
});