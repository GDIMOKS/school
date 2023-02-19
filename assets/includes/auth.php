<?php
    session_start();
    require_once "../includes/config.php";
    require_once "functions.php";

    $email =  htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM `users` WHERE `email` = '$email'";
    $user = mysqli_query($connection, $query);
    $userResult = mysqli_fetch_assoc($user);

    $dbPassword = $userResult['password'];
    $output = [];

    if (password_verify($password, $dbPassword)) {
        $query = "SELECT `roles`.`name` FROM `roles` INNER JOIN `users` ON `users`.`role_id` = `roles`.`id` WHERE `users`.`id` =". $userResult['id'];
        $role = mysqli_fetch_assoc(mysqli_query($connection, $query));

        $_SESSION['auth'] = true;
        $_SESSION['user'] =[
            'id' => $userResult['id'],
            'first_name' => $userResult['first_name'],
            'last_name' => $userResult['last_name'],
            'patronymic_name' => $userResult['patronymic_name'],
            'role_name' => $role['name'],
            'email' => $userResult['email'],
            'phone' => $userResult['phone'],
            'birthday' => $userResult['birthday']
        ];

        $_SESSION['message'] = 'Здравствуйте, ' . $_SESSION['user']['first_name'] . '!';
        $output = ['status' => 'OK', 'message' => $_SESSION['message']];

        updateCookie();

    } else {
        $_SESSION['message'] = 'Неправильные логин или пароль!';
        $output = ['status' => 'ERROR', 'message' => $_SESSION['message']];
    }

    exit(json_encode($output));

    mysqli_close($connection);

?>