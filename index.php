<?php
    session_start();
    require_once "assets/includes/config.php";
    require_once "assets/includes/functions.php";
    require "assets/includes/cookie.php";


?>

<!DOCTYPE html>

<html>
    <head>
        <title><?php echo $config['title']; ?></title>

        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/header.css">
        <link rel="stylesheet" href="assets/css/product.css">

        <meta charset="UTF-8">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>
        <script type="text/javascript" src="/assets/js/cart.js"></script>
    </head>
    <body>
    <?php
        require_once "assets/includes/header.php";
    ?>
    <?php
        $courses = get_objects('courses');
        $consultations = get_objects('consultations');
    ?>


    <div class="workspace">
        <div class="products">

            <?php if (!empty($courses)): ?>
                <div class="name">
                    Курсы
                </div>
                <div style="display: flex; flex-wrap: wrap; flex-direction: row">
                    <?php foreach ($courses as $product) {
                        $price_name = '₽/мес';
                        $is_course = true;
                        require 'assets/includes/product_card.php';
                    } ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($consultations)): ?>
                <div class="name">
                    Дополнительные услуги
                </div>
                <div style="display: flex; flex-wrap: wrap; flex-direction: row">
                    <?php foreach ($consultations as $product) {
                        $price_name = '₽';
                        $is_course = false;
                        require 'assets/includes/product_card.php';
                    } ?>
                </div>

            <?php endif; ?>


        </div>
    </div>



    </body>
</html>
<?php
    mysqli_close($connection);
?>
