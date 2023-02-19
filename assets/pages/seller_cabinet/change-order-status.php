<?php
    session_start();

    require_once "../../includes/config.php";
    require_once "../../includes/functions.php";
    require_once "../../includes/cookie.php";

    if (empty($_SESSION['user']['role_name']) || $_SESSION['user']['role_name'] != 'seller')
    {
        header('Location: /assets/pages/cabinet.php');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Обновить статус заказа</title>

        <link rel="stylesheet" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/header.css">
        <link rel="stylesheet" href="../../css/orders.css">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>

    </head>
    <body>
        <?php
            require_once "../../includes/header.php";
        ?>

        <div class="workspace">
            <h1>Заказы</h1>
            <?php
                $query = "SELECT * FROM `orders` WHERE `status` = 'Оформлен'";
            ?>
            <?php if ($result = mysqli_query($connection, $query)): ?>
                <?php $orders = get_orders($query); ?>
                <?php foreach ($orders as $order): ?>
                    <?php
                        $sum = 0;
                    ?>
                    <div class="order">
                        <div class="date_order">
                            Заказ № <?=$order['id']?> от <?=$order['ordering_time']?>
                        </div>

                        <?php if (isset($order['courses'])) : ?>
                            <?php foreach ($order['courses'] as $course) :?>
                            <a class="course" href="#">
                                <div class="container">
                                    <img src="<?=$course['image']?>" alt="Курс">
                                    <div class="text title"><?=$course['name']?></div>
                                </div>
                                <div class="container">
                                    <div class="text">Длительность: <?=$course['period']?> месяца(-ев)</div>
                                    <div class="text">Цена: <?=$course['price'] * $course['period']?> ₽</div>
                                    <?php
                                        $sum += $course['price'] * $course['period'];
                                    ?>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (isset($order['consultations'])) : ?>
                            <?php foreach ($order['consultations'] as $consults) :?>
                                <a class="course" href="#">
                                    <div class="container">
                                        <div class="consult_text title"><?=$consults['name']?></div>
                                    </div>
                                    <div class="container">
                                        <div class="text">Количество: <?=$consults['count']?></div>
                                        <div class="text">Цена: <?=$consults['price'] * $consults['count']?> ₽</div>
                                        <?php
                                            $sum += $consults['price'] * $consults['count'];
                                        ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div class="status_sum">
                            <div class="status">Статус: <?=$order['status']?>  </div>
                            <div class="sum">Общая сумма: <?=$sum?> ₽</div>
                        </div>
                        <button class="button payday" data-id="<?=$order['id']?>">Подтвердить оплату</button>
                    </div>

                <?php endforeach; ?>
            <?php endif;?>
        </div>
        <script type="module" src="../../js/cabinet/seller_cabinet.js"></script>

    </body>
</html>
<?php
    mysqli_close($connection);
?>