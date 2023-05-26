document.addEventListener('DOMContentLoaded', function() {
    var passwordField = document.getElementById('security_privacy_password');
    var showPasswordCheckbox = document.getElementById('security_privacy_showPassword');

    showPasswordCheckbox.addEventListener('change', function() {
        var isChecked = this.checked;
        passwordField.type = isChecked ? 'text' : 'password';
    });
});