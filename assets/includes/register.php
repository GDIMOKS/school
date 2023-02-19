<?php
    require_once "./config.php";

    $firstname = trim($_POST['first_name']);
    $email =  htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $password = password_hash($password, PASSWORD_BCRYPT);

    $query = "SELECT COUNT(`id`) AS `total_count` 
        FROM `users` 
        WHERE (`email` = '$email')";

    $userExist = mysqli_query($connection, $query);
    $userCount = mysqli_fetch_assoc($userExist);

    $message = '';
    $output = [];

    if ($userCount['total_count'] == 0) {
        $query = "INSERT INTO `users` (`first_name`, `role_id`, `email`, `password`) 
            VALUES ('$firstname', '2', '$email', '$password')";

        $result = mysqli_query($connection, $query);

        if (!empty($result)) {
            $message = 'Вы успешно зарегистрировались!';
            $output = ['status' => 'OK', 'message' => $message];
        }

    } else {
        $message = 'Пользователь с таким email уже существует!';
        $output = ['status' => 'ERROR', 'message' => $message];
    }

    exit(json_encode($output));
?>
