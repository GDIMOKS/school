<?php
    session_start();
    require_once "../includes/config.php";
    require_once "../includes/functions.php";

    $category = mysqli_fetch_assoc(mysqli_query($connection,"SELECT * FROM categories WHERE id =" . $_GET['category']));
?>

<!DOCTYPE html>
<html>
    <head>

        <title><?= $category['name'] ?></title>

        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/product.css">

        <meta charset="UTF-8">

        <script type="text/javascript" src="../js/jquery-3.6.1.min.js"></script>
        <script type="text/javascript" src="../js/cart.js"></script>

    </head>
    <body>
        <?php
            include "../includes/header.php";
        ?>
        <div class="workspace">
            <div class="products">

                <?php
                    $query = "SELECT * FROM `courses` WHERE `category_id`=" .$_GET['category'];
                    $courses = get_objects_query($query);

                    $query = "SELECT * FROM `consultations` WHERE `category_id`=" .$_GET['category'];
                    $consultations = get_objects_query($query);

                ?>

                <?php if (!empty($courses)): ?>
                    <div class="name">
                        Курсы
                    </div>
                    <div style="display: flex; flex-wrap: wrap; flex-direction: row">
                        <?php foreach ($courses as $product) {
                            $price_name = '₽/мес';
                            $is_course = true;
                            require '../includes/product_card.php';
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
                            require '../includes/product_card.php';
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
