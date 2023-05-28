/**
 * @fileoverview JavaScript file to show password in register page
 * @version 1.0.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-26.
 */

document.addEventListener('DOMContentLoaded', function() {
    var passwordField = document.getElementById('register_password');
    var showPasswordCheckbox = document.getElementById('register_showPassword');

    showPasswordCheckbox.addEventListener('change', function() {
        var isChecked = this.checked;
        passwordField.type = isChecked ? 'text' : 'password';
    });
});