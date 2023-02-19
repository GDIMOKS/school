<?php
    session_start();

    require_once "../includes/config.php";
    require_once "../includes/functions.php";
    require_once "../includes/cookie.php";

    if (!empty($_SESSION['auth']) || $_SESSION['auth'] == true)
    {
        header('Location: /assets/pages/cabinet.php');
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Регистрация</title>

        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/header.css">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>


    </head>
    <body>
        <?php
            require_once "../includes/header.php";
        ?>

        <div class="workspace">
            <form class="auth_reg" name="reg_form">

                <label>Имя</label>
                <input type="text" name="first_name" placeholder="Введите своё имя" autofocus>

                <label>Электронная почта</label>
                <input type="email" name="email" placeholder="Введите адрес своей электронной почты">

                <label>Пароль</label>
                <input type="password" name="password" placeholder="Введите пароль">

                <label>Подтверждение пароля</label>
                <input type="password" name="password_2" placeholder="Повторно введите пароль">
                </p>

                <div class="g-recaptcha" data-sitekey="<?php echo $config['SITE_KEY'] ?>" style="margin: auto";></div>

                <button class="button" type="submit">Зарегистрироваться</button>
                <p class="p_reg">
                    У вас уже есть аккаунт? - <a href="signin.php" class="a_reg">авторизуйтесь</a>!
                </p>

                <div class="errors_block">

                </div>
            </form>
        </div>
        <script type="module" src="../js/registration.js"></script>
    </body>
</html>
<?php
    mysqli_close($connection);
?>
