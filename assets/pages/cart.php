<?php
    session_start();
    require_once "../includes/config.php";
    require_once "../includes/functions.php";
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/orders.css">
        <link rel="stylesheet" href="../css/product.css">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>

        <title>Корзина</title>
    </head>
    <body>
        <?php
            include "../includes/header.php";
        ?>

        <div class="workspace">

            <h1>Оформление заказа</h1>
            <div class="order">
                <?php if (isset($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $course): ?>
                        <?php if ($course['type'] == 'courses'): ?>
                            <a class="course" href="#">
                                <div class="name_container">
                                    <img src="/media/<?=$course['image']?>" alt="Курс">
                                    <div class="text title"><?=$course['name']?></div>
                                </div>
                                <div class="cart_container">
                                    <div class="">Длительность: </div>
                                    <div class="cart_buttons">

                                        <div class="product_button card-btn del-from-cart" data-type="<?=$course['type']?>" data-id="<?= $course['id'] ?>">
                                            −
                                        </div>

                                        <div class="product_count" id="count-<?=$course['id']?>" style="font-weight: normal;">
                                            <?= $_SESSION['cart'][$course['id']]['count'] ?? 0 ?>
                                        </div>

                                        <div class="product_button card-btn add-to-cart"  data-type="<?=$course['type']?>" data-id="<?= $course['id'] ?>">
                                            +
                                        </div>
                                    </div>
                                    <div class=""> месяц(-ев)</div>

                                </div>
                                <div class="cost_container">Цена: <?=$course['price'] * $course['count']?> ₽</div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif;?>

                <?php if (isset($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $consult): ?>
                        <?php if ($consult['type'] == 'consultations'): ?>
                            <a class="course" href="#">
                                <div class="name_container">
                                    <div class="consult_text title"><?=$consult['name']?></div>
                                </div>
                                <div class="cart_container">
                                    <div>Количество: </div>
                                    <div class="cart_buttons">

                                        <div class="product_button card-btn del-from-cart" data-type="<?=$consult['type']?>" data-id="<?= $consult['id'] ?>">
                                            −
                                        </div>

                                        <div class="product_count" id="count-<?=$consult['id']?>" style="font-weight: normal;">
                                            <?= $_SESSION['cart'][$consult['id']]['count'] ?? 0 ?>
                                        </div>

                                        <div class="product_button card-btn add-to-cart"  data-type="<?=$consult['type']?>" data-id="<?= $consult['id'] ?>">
                                            +
                                        </div>
                                    </div>
                                    <div> единиц</div>

                                </div>
                                <div class="cost_container cost">Цена: <?=$consult['price'] * $consult['count']?> ₽</div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="count" style="justify-content: flex-end">Всего товаров: <?=$_SESSION['cart.count'] ?? 0?></div>
                <div class="sum" style="justify-content: flex-end">Общая сумма: <?=$_SESSION['cart.sum'] ?? 0?> ₽</div>
                <button class="button checkout">Оформить заказ</button>
            </div>
        </div>
        <script type="module" src="/assets/js/cart.js"></script>

    </body>
</html>

<?php
    mysqli_close($connection);
?>