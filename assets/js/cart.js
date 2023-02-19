// import {redirect} from "./functions.js";


$(function () {
    $('.add-to-cart').on('click', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let type = $(this).data('type');
        let parent = $(this).closest('.course');





        $.ajax({
            url: '/assets/includes/cart-objects.php',
            type: 'GET',
            data: {cart: 'add', id: id, type: type},
            dataType: 'json',
            success: function (result) {
                if (result.code == 'ok') {
                    $('#cart-num').text(result.total_count);

                    $('#count-'+id).text(result.count);
                    let cost = $(parent).find('.cost_container');

                    if (parent != undefined) {
                        $('.count').text('Всего товаров: ' + result.total_count);
                        $('.sum').text('Общая сумма: ' + result.total_sum + ' ₽');
                        $(cost).text('Цена: ' + result.product.price * result.count + ' ₽');
                    }

                    if (cost != undefined) {
                        let total_count = $(parent).find('.count');

                    }


                } else {
                    alert(result.product);
                }

            },
            error: function () {
                console.log('Error!');
            }
        });
    });

    $('.del-from-cart').on('click', function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let type = $(this).data('type');
        let parent = $(this).closest('.course');

        $.ajax({
            url: '/assets/includes/cart-objects.php',
            type: 'GET',
            data: {cart: 'delete', id: id, type: type},
            dataType: 'json',
            success: function (result) {
                if (result.code == 'ok') {
                    $('#cart-num').text(result.total_count);
                    $('#count-'+id).text(result.count);

                    let cost = $(parent).find('.cost_container');

                    if (parent != undefined) {
                        $('.count').text('Всего товаров: ' + result.total_count);
                        $('.sum').text('Общая сумма: ' + result.total_sum + ' ₽');
                        $(cost).text('Цена: ' + result.product.price * result.count + ' ₽');
                    }

                    if (cost != undefined) {
                        $(cost).text('Цена: ' + result.product.price * result.count + ' ₽')
                        if (result.count == 0) {
                            $(parent).remove();
                        }
                    }

                } else {
                    alert(result.answer);
                }

            },
            error: function () {
                console.log('Error!');
            }
        });
    });

    $('.checkout').on('click', function (e) {
        e.preventDefault();

        let parent = $(this).closest('.order');

        $.ajax({
            url: '/assets/includes/cart-objects.php',
            type: 'POST',
            data: {cart: 'checkout'},
            dataType: 'json',
            success: function (result) {
                if (result.code == 'ok') {
                    $(parent).remove();
                    $('#cart-num').text('0');
                    $('<div>Заказ №' + result.answer + ' добавлен на обработку!</div>').appendTo('.workspace')

                } else {
                    alert(result.answer);
                }

            },
            error: function () {
                console.log('Error!');
            }
        });
    });
});