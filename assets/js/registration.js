import {
    checkEmail,
    checkName,
    generateErrors,
    changeColor,
    checkCaptcha,
    formEvent,
    clearErrors
} from "./functions.js";

let regForm = document.querySelector('[name=reg_form]');
let regErrorsBlock = regForm.querySelector('.errors_block');

let checkPassword = function (password, confirmPassword, errors) {
    let passwordErrors = [];
    let confirmPasswordErrors = [];

    if (password.value.length == 0) {
        passwordErrors.push('Не указан пароль');
    } else if (password.value.length < 8) {
        passwordErrors.push('Пароль не должен быть короче 8 символов');
    } else {
        if (!/(?=.*[0-9])/g.exec(password.value)) {
            passwordErrors.push('Пароль должен содержать минимум одну цифру');
        }
        if (!/(?=.*[!@#$%^&*])/g.exec(password.value)) {
            passwordErrors.push('Пароль должен содержать один из следующих спецсимволов: !@#$%^&*');
        }
        if (!/(?=.*[a-z])(?=.*[A-Z])/g.exec(password.value)) {
            passwordErrors.push('Пароль должен содержать только латинские буквы');
        }
        if (!/(?=.*[a-z])/g.exec(password.value)) {
            passwordErrors.push('Пароль должен содержать как минимум одну строчную букву');
        }
        if (!/(?=.*[A-Z])/g.exec(password.value)) {
            passwordErrors.push('Пароль должен содержать как минимум одну прописную букву');
        }
    }

    if (password.value != confirmPassword.value || password.value == '' && confirmPassword.value == '') {
        confirmPasswordErrors.push('Повторный ввод пароля неверный');
    }

    errors = changeColor(password, passwordErrors, errors);
    errors = changeColor(confirmPassword, confirmPasswordErrors, errors);


    return errors;
}

let checkRegInputs = function () {
    let errors = [];

    let firstname = regForm.querySelector('[name=first_name]');
    let email = regForm.querySelector('[name=email]');
    let password = regForm.querySelector('[name=password]');
    let confirmPassword = regForm.querySelector('[name=password_2]');
    let captcha = grecaptcha.getResponse();

    checkName(firstname, errors);
    checkEmail(email, errors);
    checkPassword(password, confirmPassword, errors);
    checkCaptcha(captcha, errors);

    generateErrors(regErrorsBlock, errors);

}



regForm.addEventListener('submit', (e) => {
    e.preventDefault();

    clearErrors(regForm);
    checkRegInputs();
    formEvent(regForm, regErrorsBlock, '../includes/register.php', 'signin.php');
});


