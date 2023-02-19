<?php
    session_start();
    require_once "config.php";
    require_once "functions.php";

if (empty($_SESSION['auth']) || $_SESSION['auth'] == false) {

    //Проверяем, не пустые ли нужные нам куки...
    if (!empty($_COOKIE['login']) && !empty($_COOKIE['key'])) {
        //Пишем логин и ключ из КУК в переменные (для удобства работы):

        $login = $_COOKIE['login'];
        $key = $_COOKIE['key']; //ключ из кук

        /*
            Формируем и отсылаем SQL запрос:
            ВЫБРАТЬ ИЗ таблицы_users ГДЕ поле_логин = $login.
        */

        $query = "SELECT * FROM users WHERE `email`='$login' AND `cookie`='$key'";
        //Ответ базы запишем в переменную $result:
        $result = mysqli_query($connection, $query);
        $userResult = mysqli_fetch_assoc($result);




        //Если база данных вернула не пустой ответ - значит пара логин-ключ_к_кукам подошла...
        if (!empty($userResult)) {
            $query = "SELECT `roles`.`name` FROM `roles` INNER JOIN `users` ON `users`.`role_id` = `roles`.`id` WHERE `users`.`id` =". $userResult['id'];
            $role = mysqli_fetch_assoc(mysqli_query($connection, $query));
            //Пишем в сессию информацию о том, что мы авторизовались:
            $_SESSION['auth'] = true;

            /*
                Пишем в сессию данные пользователя.
            */
            $_SESSION['user'] = [
                "id" => $userResult['id'],
                "first_name" => $userResult['first_name'],
                "last_name" => $userResult['last_name'],
                "patronymic_name" => $userResult['patronymic_name'],
                "role_name" => $role['name'],
                "email" => $userResult['email'],
                "phone" => $userResult['phone'],
                "birthday" => $userResult['birthday']
            ];


            //Перезапись куков.

            updateCookie();

        }
    }
}

?>