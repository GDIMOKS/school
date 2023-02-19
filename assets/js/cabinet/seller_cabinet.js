import {
    clearErrors,
    changeColor,
    generateErrors,
    redirect
} from "../functions.js";
let image = false;

$(function () {


    $('.delete-from-db').on('click', function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        $.ajax({
            url: '/assets/includes/cabinet/seller_cabinet.php',
            type: 'POST',
            data: {seller_action: 'delete', id: id},
            dataType: 'json',
            success: function (result) {
                if (result.code == 'ok') {
                    $('#del-prod-'+id).remove();
                } else {
                    alert(result.answer);
                }

            },
            error: function () {
                console.log('Error!');
            }
        });
    });


    $('input[name="image"]').change(function (e) {
        image = e.target.files[0];
    });

    addAndUpdate('.add-course-form', 'add', image);
    addAndUpdate('.update-course-form', 'update', image);

    $('.upd_select').on('change', function (e) {
        e.preventDefault();

        let course_sel = this;
        let course_id = course_sel.options[course_sel.selectedIndex].dataset.id;

        $.ajax({
            url: '/assets/includes/cabinet/seller_cabinet.php',
            type: 'POST',
            data: {seller_action: 'choose', id: course_id},
            dataType: 'json',
            success: function (result) {
                if (result.code == 'ok') {
                    $('.update-course-form').data('id', result.course.id);
                    $('.update-course-form').find('img')[0].src = result.course.image;
                    $('.update-course-form').find('input[name="name"]')[0].value = result.course.name;
                    $('.update-course-form').find('input[name="description"]')[0].value = result.course.description;
                    $('.update-course-form').find('input[name="price"]')[0].value = result.course.price;
                    $('.update-course-form').find('.category_sel')[0].value = result.course.category_name;
                    $('.update-course-form').find('.curator')[0].value = result.course.curator_name;
                } else {
                    console.log(result.answer);
                }

            },
            error: function () {
                console.log('Error!');
            }
        });

    });

    $('.payday').on('click', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let parent = $(this).closest('.order');

        $.ajax({
            url: '/assets/includes/cabinet/seller_cabinet.php',
            type: 'POST',
            data: {seller_action: 'change_status', id: id},
            dataType: 'json',
            success: function (result) {
                if (result.code == 'ok') {
                    console.log(1);
                    $(parent).empty();
                    $(parent).text('Статус заказа № ' + id + ' обновлен на "Оплачен"!');
                } else {
                    console.log();
                }

            },
            error: function () {
                console.log('Error!');
            }
        });
    });

});



function checkPrice(price, errors){
    let priceErrors = [];

    if (price.value.length == 0) {
        priceErrors.push('Не указана цена');
    } else {
        if (Number.parseFloat(+price.value) < 0 ){
            priceErrors.push('Отрицательная цена');
        }
        if (!Number.isInteger(+price.value) && !Number.parseFloat(+price.value)){
            priceErrors.push('Некорректный ввод цены');
        }
    }

    errors = changeColor(price, priceErrors, errors);

    return errors;
}

let check_inputs = function (addForm, addErrorsBlock) {
    let errors = [];

    let name = addForm.querySelector('[name=name]');
    let price = addForm.querySelector('[name=price]');

    checkName(name, errors);
    checkPrice(price, errors);

    generateErrors(addErrorsBlock, errors);

}

function checkName(name, errors) {
    let nameErrors = [];

    if (name.value.length == 0) {
        nameErrors.push('Не указано название');
    } else {
        let regExp = /^[А-Яа-яЁёA-Za-z0-9' .(),-]+$/g;
        if (!regExp.exec(name.value)) {
            nameErrors.push('Название может содержать только кириллицу, латиницу, цифры, пробел и следующие символы: \' , \( \) \. -');
        }
    }

    errors = changeColor(name, nameErrors, errors);

    return errors;
}

function addAndUpdate(formName, action) {
    $(formName).on('submit', function (e) {
        e.preventDefault();
        console.log(image)
        let addErrorsBlock = this.querySelector('.errors_block');

        let form = this;
        clearErrors(form);
        check_inputs(form, addErrorsBlock);

        if (addErrorsBlock.innerHTML == ''){
            let cat_sel = form.querySelector('.category_sel');
            let curator_sel = form.querySelector('.curator');
            let category = cat_sel.options[cat_sel.selectedIndex].dataset.id;
            let curator = curator_sel.options[curator_sel.selectedIndex].dataset.id;

            let title = form.querySelector('input[name="name"]').value;
            let price = form.querySelector('input[name="price"]').value;
            let description = form.querySelector('input[name="description"]').value;



            let formData = new FormData();
            formData.append('title', title);
            if (image == false) {
                image = $('.update-course-form').find('img')[0].src;
                if (image != "") {
                    formData.append('image', image);
                }
            } else {
                formData.append('image', image);
            }
            if (action == 'update') {
                formData.append('id', $(this).data('id'));
            }
            formData.append('price', price);
            formData.append('description', description);
            formData.append('category', category);
            formData.append('curator', curator);
            formData.append('seller_action', action);

            $.ajax({
                url: '/assets/includes/cabinet/seller_cabinet.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                cache: false,
                success: function (result) {
                    if (result.code == 'ok') {
                        addErrorsBlock.innerHTML += '<p class="errors_block_good">' + result.message + '</p>';
                        $(form).find('img')[0].src = result.image;
                        // setTimeout(redirect, 1000, '../pages/profile.php');

                    } else {
                        addErrorsBlock.innerHTML += '<p class="errors_block_bad">' + result.message + '</p>';
                    }

                },
                error: function () {
                    console.log('Error!');
                }
            });
        }
    });
}