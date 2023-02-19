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
        <title>Добавить товар</title>

        <link rel="stylesheet" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/header.css">
        <link rel="stylesheet" href="../../css/cabinet.css">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>


    </head>
    <body>
        <?php
            require_once "../../includes/header.php";
        ?>

        <div class="workspace">
            <form class="add-course-form">
                <?php
                    require_once "./add-form.php";
                ?>

                <input class="button" type="submit" value="Добавить курс">

                <div class="errors_block">

                </div>
            </form>

        </div>
        <script type="module" src="../../js/cabinet/seller_cabinet.js"></script>

    </body>
</html>
<?php
    mysqli_close($connection);
?>