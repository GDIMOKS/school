<?php
    session_start();
    require_once "../includes/config.php";
    require_once "../includes/functions.php";
    require_once "../includes/cookie.php";

    if (empty($_SESSION['auth']) || $_SESSION['auth'] != true)
    {
        header('Location: /assets/pages/login.php');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Личный кабинет</title>

        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/product.css">

        <meta charset="UTF-8">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>
        <script type="text/javascript" src="/assets/js/cart.js"></script>
        <script>
            let now = new Date();
            setInterval(get_current_time, 1000);

            function get_current_time()  {
                let date = new Date();
                let options = {
                    weekday: "long",
                    year: "numeric",
                    month: "numeric",
                    day: "numeric",
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric'
                };

                $('.current-time').text(date.toLocaleDateString("ru", options));
            }
        </script>
    </head>
    <body>
        <?php
            require_once "../includes/header.php";
        ?>
        <div class="workspace">
            <div class="profile_info">
                <div style="display: flex; flex-direction: column">
                    <div class="profile_status">
                        Личный кабинет
                        <?php
                        switch ($_SESSION['user']['role_name']) {
                            case 'owner':
                                echo ' Владельца ';
                                break;
                            case 'student':
                                echo ' Студента ';
                                break;
                            case 'seller':
                                echo ' Продавца ';
                                break;
                            case 'curator':
                                echo ' Куратора ';
                                break;
                        }
                        ?>
                        <p style="color: #ff8c39; margin-left: 10px"> <?= $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['second_name'] . ' '. $_SESSION['user']['patronymic_name']?></p>

                    </div>
                    <div class="time_div">
                        Текущая дата:
                        <div class="current-time">
                            <?php
                                $now = time();
                                $days = array(
                                    'воскресенье', 'понедельник', 'вторник', 'среда',
                                    'четверг', 'пятница', 'суббота'
                                );

                                setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');
                                $date = date(', d.m.Y г., H:i:s');

                                $dnum = date('w',strtotime($now));

                                echo $days[$dnum];
                                echo $date;
                            ?>
                        </div>
                    </div>

                </div>


                <a class="button cabinet_button" href="../includes/logout.php">
                    <div class="cabinet_button_text">Выйти из аккаунта</div>
                </a>
            </div>
            <div class="profile_settings">
                <div class="profile_buttons">
                    <?php switch ($_SESSION['user']['role_name']): ?><?php case 'owner': ?>
                        <a class="button check-profit" href="owner_cabinet/check-profit.php">Просмотр прибыли</a>
                        <a class="button check-rating" href="owner_cabinet/check-rating.php">Просмотр рейтинга</a>


                        <?php break; ?>

                    <?php case 'student': ?>
<!--                        <a class="button edit-info">Редактирование данных</a>-->
                        <a class="button show-orders" href="orders.php">Просмотр заказов</a>

                        <?php break; ?>

                    <?php case 'seller': ?>
                        <a class="button add-product" href="seller_cabinet/add-product.php">Добавить товар</a>
                        <a class="button update-product" href="seller_cabinet/update-product.php">Изменить товар</a>
                        <a class="button delete-product" href="seller_cabinet/delete-product.php">Удалить товар</a>
                        <a class="button update-status" href="seller_cabinet/change-order-status.php">Изменить статус заказа</a>

                        <?php break; ?>

                    <?php endswitch ?>
                </div>
        </div>

    </body>
</html>

<?php
    mysqli_close($connection);
?>