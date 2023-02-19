<?php
    session_start();

    require_once "../includes/config.php";
    require_once "../includes/functions.php";
    require_once "../includes/cookie.php";

    if (empty($_SESSION['user']['role_name']) || $_SESSION['user']['role_name'] != 'student')
    {
        header('Location: /assets/pages/cabinet.php');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>История заказов</title>

        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <link rel="stylesheet" href="../css/cabinet.css">
        <link rel="stylesheet" href="../css/orders.css">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>


    </head>
    <body>
    <?php
        require_once "../includes/header.php";
    ?>

    <div class="workspace">

        <?php
            $name = 'История заказов';
            $classname = 'show-orders-form';
            require_once "../includes/date-form.php";
        ?>

        <div class="grociers">

        </div>

    </div>
    <script type="module" src="../js/cabinet/student_cabinet.js"></script>

    </body>
</html>
<?php
    mysqli_close($connection);
?>
