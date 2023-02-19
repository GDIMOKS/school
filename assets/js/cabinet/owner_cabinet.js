import {
    changeColor,
    generateErrors,
    checkDateInputs
} from "../functions.js";

$(function () {
    $('.show-profit-form').on('submit', function (e) {
        e.preventDefault();

        let form = this;

        let ordersErrorsBlock = form.querySelector('.errors_block');
        let begin_date = form.querySelector('input[name="begin-date"]');
        let end_date = form.querySelector('input[name="end-date"]');

        checkDateInputs(begin_date, end_date, ordersErrorsBlock);

        if (ordersErrorsBlock.innerHTML == ''){
            let formData = new FormData();
            formData.append('begin-date', begin_date.value);
            formData.append('end-date', end_date.value);
            formData.append('owner_action', 'profit');

            $.ajax({
                url: '/assets/includes/cabinet/owner_cabinet.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (result) {
                    if (result.code == 'ok') {
                        $('.grociers').empty();
                        console.log(result.total_price)
                        ordersErrorsBlock.innerHTML += '<p class="errors_block_good"> Прибыль: ' + result.total_price + ' ₽</p>';


                    } else {
                        ordersErrorsBlock.innerHTML += '<p class="errors_block_bad">' + result.message + '</p>';
                    }

                },
                error: function () {
                    console.log('Error!');
                }
            });
        }

    });

    $('.show-rating-form').on('submit', function (e) {
        e.preventDefault();

        let form = this;

        let ordersErrorsBlock = form.querySelector('.errors_block');
        let begin_date = form.querySelector('input[name="begin-date"]');
        let end_date = form.querySelector('input[name="end-date"]');

        checkDateInputs(begin_date, end_date, ordersErrorsBlock);

        if (ordersErrorsBlock.innerHTML == ''){
            let formData = new FormData();
            formData.append('begin-date', begin_date.value);
            formData.append('end-date', end_date.value);
            formData.append('owner_action', 'rating');

            $.ajax({
                url: '/assets/includes/cabinet/owner_cabinet.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (result) {
                    if (result.code == 'ok') {
                        $('.grociers').empty();
                        let order_area = $('<div class="order"></div>').appendTo('.grociers');

                        if (result.courses != undefined) {
                            $('<h1>Рейтинг самых прибыльных курсов</h1>').appendTo(order_area);
                            for (let i = 0; i < result.courses.length; i++) {
                                let course = result.courses[i];
                                let course_area = $('<a class="course" href="#"></a>').appendTo(order_area);
                                $('<div class="container">' +
                                    '<img src="/media/' + course.image + '" alt="Курс">' +
                                    '<div class="text title">' + course.name + '</div>' +
                                    '</div>').appendTo(course_area);
                                $('<div class="container">' +
                                    '<div class="text">Прибыль: ' + course.total_price + ' ₽</div>' +
                                    '</div>').appendTo(course_area);

                            }
                        }


                        if (result.consults != undefined) {
                            $('<h1>Рейтинг самых прибыльных консультаций</h1>').appendTo(order_area);
                            for (let i = 0; i < result.consults.length; i++) {
                                let consult = result.consults[i];

                                let consult_ares = $('<a class="course" href="#"></a>').appendTo(order_area);
                                $('<div class="container">' +
                                    '<div class="consult_text title">'+ consult.name +'</div>' +
                                    '</div>').appendTo(consult_ares);
                                $('<div class="container">' +
                                    '<div class="text">Прибыль: ' + consult.total_price + ' ₽</div>' +
                                    '</div>').appendTo(consult_ares);
                            }
                        }

                    } else {
                        ordersErrorsBlock.innerHTML += '<p class="errors_block_bad">' + result.message + '</p>';
                    }

                },
                error: function () {
                    console.log('Error!');
                }
            });
        }

    });
});