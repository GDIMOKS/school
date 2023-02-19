import {
    checkEmail,
    clearErrors,
    generateErrors,
    changeColor,
    checkCaptcha,
    formEvent
} from "./functions.js";

let authForm = document.querySelector('[name=auth_form]');
let authErrorsBlock = authForm.querySelector('.errors_block');

function checkPassword(password, errors) {
    let passwordErrors = [];

    if (password.value.length == 0) {
        passwordErrors.push('Не указан пароль');
    }

    errors = changeColor(password, passwordErrors, errors);

    return errors;
}

function checkAuthInputs() {
    let errors = [];

    let email = authForm.querySelector('[name=email]');
    let password = authForm.querySelector('[name=password]');
    let captcha = grecaptcha.getResponse();

    checkEmail(email, errors);
    checkPassword(password, errors);
    checkCaptcha(captcha, errors);

    generateErrors(authErrorsBlock, errors);

}

authForm.addEventListener('submit', (e) => {
    e.preventDefault();

    clearErrors(authForm);
    checkAuthInputs();
    formEvent(authForm, authErrorsBlock, '../includes/auth.php', '/');

});