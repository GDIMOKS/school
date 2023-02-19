<?php
    session_start();
    require_once '../config.php';
    require_once '../functions.php';

    error_reporting(-1);

        if (empty($_SESSION['user']['role_name']) || $_SESSION['user']['role_name'] != 'seller') {
            header('Location: /assets/pages/profile.php');
//            debug($_SESSION['user']['role_id']);
        }

    if (isset($_POST['seller_action'])) {
        switch ($_POST['seller_action']) {
            case 'delete':
                $query = "DELETE FROM `courses` WHERE `id` =" . $_POST['id'];

                if ($result = !mysqli_query($connection, $query)) {
                    echo json_encode(['code' => 'error', 'answer' => $result]);

                } else {
                    echo json_encode(['code' => 'ok']);
                }
                break;

            case 'add':
                add_and_update('add');
                break;

            case 'choose':
                $query = "SELECT `courses`.*, 
                                    (SELECT CONCAT(`users`.`first_name`, ' ', COALESCE(`users`.`last_name`, ''), ' ', COALESCE(`users`.`patronymic_name`, '')) 
                                     FROM `users` 
                                     WHERE `courses`.`curator_id` = `users`.`id`) as `curator_name`, 
                                    (SELECT `categories`.`name` 
                                     FROM `categories` 
                                     WHERE `courses`.`category_id` = `categories`.`id`) as `category_name` 
                                FROM `courses`
                                WHERE `id` = ".$_POST['id'];

                $result = mysqli_fetch_assoc(mysqli_query($connection, $query));
                $result['image'] = $config['uploads'] . $result['image'];
                $result['curator_name'] = trim($result['curator_name']);
                echo json_encode(['code' => 'ok', 'course' => $result]);

                break;
            case 'update':
                add_and_update('update');
                break;

            case 'change_status':
                $id = $_SESSION['user']['id'];
                $date = date("Y.m.d H:i:s");
                $query = "UPDATE `orders` SET `status` = 'Оплачен', `seller_id` = '$id', `purchase_time` = '$date' WHERE `id` =" . $_POST['id'];


                if ($result = !mysqli_query($connection, $query)) {
                    echo json_encode(['code' => 'error', 'answer' => $result]);

                } else {
                    echo json_encode(['code' => 'ok']);
                }

                break;
        }
    }

    mysqli_close($connection);
?>





