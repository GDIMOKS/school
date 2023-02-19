export let clearErrors = function (form) {
    let errors = form.querySelectorAll('.error');

    for (let i = 0; i < errors.length; i++)
        errors[i].remove();
}

export let redirect = function(reference) {
    location.href = reference;
}

export let checkName = function (firstname, errors) {
    let nameErrors = [];

    if (firstname.value.length == 0) {
        nameErrors.push('Не указано имя');
    } else {
        let regExp = /^[А-Яа-яЁё' .(),-]+$/g;
        if (!regExp.exec(firstname.value)) {

            nameErrors.push('Имя может содержать только кириллицу, пробел и следующие символы: \' , \( \) \. -');
        }
    }

    errors = changeColor(firstname, nameErrors, errors);

    return errors;
}

export let changeColor = function (element, elementErrors, errors) {
    if (elementErrors.length == 0) {
        element.style.borderColor = '#e3e3e3';
        element.style.backgroundColor = '#fcfcfc';
    } else {
        for (let i = 0; i < elementErrors.length; i++)
            errors.push(elementErrors[i]);
        element.style.borderColor = 'red';
        element.style.backgroundColor = '#FF000009';
    }

    return errors;
}

export let checkEmail = function (email, errors) {
    let emailErrors = [];

    if (email.value.length == 0) {
        emailErrors.push('Не указана электронная почта');
    }

    errors = changeColor(email, emailErrors, errors);

    return errors;
}



export let generateErrors = function (errorsBlock, errors) {
    errorsBlock.innerHTML = '';

    for (let i = 0; i < errors.length; i++)
    {
        errorsBlock.innerHTML += '<p style="color:red">' + errors[i] + '</p>';
    }
}

export let checkCaptcha = function (captcha, errors) {
    if (!captcha.length) {
        errors.push('Вы не прошли проверку "Я не робот"');
    }

}

export let formEvent = function (form, errorsBlock, urlRequest, urlRedirect) {
    let captcha = grecaptcha.getResponse();

    if (errorsBlock.innerHTML == ''){
        let formData = new FormData(form);
        let request = new XMLHttpRequest();

        if (captcha.length) {
            formData.append('g-recaptcha-response', captcha);
        }

        request.open('POST', urlRequest);
        request.responseType = 'json';
        request.onload = () => {
            if (request.status !== 200) {
                return;
            }

            let response = request.response;

            if (response.status == 'ERROR') {
                grecaptcha.reset();
                errorsBlock.innerHTML += '<p class="errors_block_bad">' + response.message + '</p>';
            }
            else
            {

                errorsBlock.innerHTML += '<p class="errors_block_good">' + response.message + '</p>';

                setTimeout(redirect, 2000, urlRedirect);
            }

        }

        request.send(formData);
    }
}

export function hide_areas(areas, current_area) {
    for (let i = 0; i < areas.length; i++) {
        if (areas[i] != current_area) {
            if (!areas[i].hasClass('none')) {
                areas[i].addClass('none');
            }
        }
    }
}

export function checkDate(begin, end, errors) {
    let beginDateErrors = [];
    let endDateErrors = [];

    if (begin.value != '' && end.value != '') {
        if (begin.value > end.value) {
            endDateErrors.push('Конечная дата должна быть больше начальной');
            beginDateErrors.push('Начальная дата должна быть меньше конечной');
        }
    }


    errors = changeColor(begin, beginDateErrors, errors);
    errors = changeColor(end, endDateErrors, errors);

    return errors;
}

export function checkDateInputs(begin_date, end_date, showErrorsBlock) {
    let errors = [];

    checkDate(begin_date, end_date, errors);

    generateErrors(showErrorsBlock, errors);

}







