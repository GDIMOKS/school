import {
    changeColor,
    generateErrors,
    checkDateInputs
} from "../functions.js";

$(function () {

    $('.show-orders-form').on('submit', function (e) {
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
            formData.append('student_action', 'show');

            $.ajax({
                url: '/assets/includes/cabinet/student_cabinet.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (result) {
                    if (result.code == 'ok') {
                        $('.grociers').empty();
                        for (let i = 0; i < result.orders.length; i++)
                        {
                            let sum = 0;
                            let order_area = $('<div class="order"></div>').appendTo('.grociers');

                            let order = result.orders[i];

                            $('<div class="date_order">Заказ № ' + order.id + ' от ' + order.ordering_time + '</div>').appendTo(order_area);

                            let courses = order.courses;
                            if (courses != undefined) {
                                $('<h1>Курсы</h1>').appendTo(order_area);
                                for (let j = 0; j < courses.length; j++) {
                                    let course_area = $('<a class="course" href="#"></a>').appendTo(order_area);
                                    $('<div class="container">' +
                                        '<img src="'+courses[j].image+'" alt="Курс">' +
                                        '<div class="text title">'+ courses[j].name +'</div>' +
                                        '</div>').appendTo(course_area);
                                    $('<div class="container">' +
                                        '<div class="text">Длительность: ' + courses[j].period + ' месяца(-ев)</div>' +
                                        '<div class="text">Цена: ' + courses[j].price * courses[j].period + ' ₽</div>' +
                                        '</div>').appendTo(course_area);
                                    sum += courses[j].price * courses[j].period;
                                }
                            }

                            let consults = order.consultations;
                            if (consults != undefined) {
                                $('<h1>Консультации</h1>').appendTo(order_area);
                                for (let j = 0; j < consults.length; j++) {
                                    let consult_ares = $('<a class="course" href="#"></a>').appendTo(order_area);
                                    $('<div class="container">' +
                                        '<div class="consult_text title">'+ consults[j].name +'</div>' +
                                        '</div>').appendTo(consult_ares);
                                    $('<div class="container">' +
                                        '<div class="text">Количество: ' + consults[j].count + ' единиц</div>' +
                                        '<div class="text">Цена: ' + consults[j].price * consults[j].count + ' ₽</div>' +
                                        '</div>').appendTo(consult_ares);
                                    sum += consults[j].price * consults[j].count;
                                }
                            }

                            $('<div class="status_sum">' +
                                '<div class="status">Статус: ' + order.status + '</div>' +
                                '<div class="sum">Общая сумма: ' + sum + ' ₽</div>'+
                            '</div>').appendTo(order_area);
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

