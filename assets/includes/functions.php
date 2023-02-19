<?php

    function debug($data){
        echo '<pre>' . print_r($data, 1) . '</pre>';
    }

    function get_objects($name) : array {
        global $connection;

        $result = mysqli_query($connection, "SELECT * FROM `$name`");

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function get_objects_query($query) {
        global $connection;

        $result = mysqli_query($connection, $query);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function generateSalt() {
        $salt = '';
        $saltLength = 60; //длина соли
        for($i = 0; $i < $saltLength; $i++) {
            $salt .= chr(mt_rand(33, 126)); //символ из ASCII-table
        }

        return $salt;
    }

    function updateCookie() {
        //session_start();
        global $connection;

        $key = generateSalt();
        $hash_key = hash('sha256', $key);
        setcookie('login', $_SESSION['user']['email'], time() + 86400, '/');
        setcookie('key', $hash_key,  time() + 86400, '/');

        $query = "UPDATE `users` SET `cookie`='$hash_key' WHERE `email`='".$_SESSION['user']['email']."'";
        mysqli_query($connection, $query);
    }

    function get_object($id, $name) {
        global $connection;
        $result = mysqli_query($connection,"SELECT * FROM `$name` WHERE id = '$id'");
        $object_result = mysqli_fetch_assoc($result);

        return !empty($object_result) ? $object_result : false;
    }

    function get_orders($query) {
        global $config;

        $orders = get_objects_query($query);
        for ($i = 0; $i < count($orders); $i++) {
            $query = "SELECT * FROM `courses` INNER JOIN `course_order` ON `course_order`.`course_id` = `courses`.`id` WHERE `course_order`.`order_id`=". $orders[$i]['id'];


            if ($courses = get_objects_query($query)) {
                for ($j = 0; $j < count($courses); $j++) {
                    $courses[$j]['image'] = $config['uploads'] . $courses[$j]['image'];
                }
                $orders[$i]['courses'] = $courses;
            }
            $query = "SELECT * FROM `consultations` INNER JOIN `consultation_order` ON `consultation_order`.`consultation_id` = `consultations`.`id` WHERE `consultation_order`.`order_id` =".$orders[$i]['id'];
            if ($consultations = get_objects_query($query)) {
                $orders[$i]['consultations'] = $consultations;
            }
        }

        return $orders;
    }

    function add_to_cart($product, $type) {
        if (isset($_SESSION['cart'][$product['id']])) {
            $_SESSION['cart'][$product['id']]['count'] += 1;
        } else {
            $_SESSION['cart'][$product['id']] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'image' => (isset($product['image'])) ? $product['image'] : '0',
                'price' => $product['price'],
                'type' => $type,
                'count' => 1
            ];
        }

        $_SESSION['cart.count'] = !empty($_SESSION['cart.count']) ? ++$_SESSION['cart.count'] : 1;
        $_SESSION['cart.sum'] = !empty($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $product['price'] : $product['price'];
    }

    function del_from_cart($product) {
        if (isset($_SESSION['cart'][$product['id']])) {
            $_SESSION['cart'][$product['id']]['count'] -= 1;

            $_SESSION['cart.count'] = !empty($_SESSION['cart.count']) ? --$_SESSION['cart.count'] : 0;
            $_SESSION['cart.sum'] = !empty($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] - $product['price'] : 0;

            if ($_SESSION['cart'][$product['id']]['count'] == 0) {
                unset($_SESSION['cart'][$product['id']]);
            }

            if ($_SESSION['cart'] == null) {
                unset($_SESSION['cart']);
            }
        }


    }

    function clear_cart() {
        unset($_SESSION['cart']);
        $_SESSION['cart.count'] = 0;
        $_SESSION['cart.sum'] = 0;
    }

    function add_and_update($type) {
        global  $connection;
        global $config;

        if (!empty($_FILES['image'])) {
            $file_name = time() . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], '../../../media/' . $file_name);
        } else {
            $file_name = 'no_image.jpg';
        }

        $title = $_POST['title'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $curator = $_POST['curator'];
        $description = htmlspecialchars($_POST['description']);
        if ($type == 'add') {
            $query = "INSERT INTO `courses` (`name`, `image`, `price`, `category_id`, `curator_id`, `description`) VALUES ('$title', '$file_name', '$price', '$category', '$curator', '$description')";
            $is_exist = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT('id') AS `total_count` FROM `courses` WHERE (`name` = '$title')"));
        } elseif ($type == 'update') {
            $id = $_POST['id'];
            $query = "UPDATE `courses` SET `name` = '$title', `image` = '$file_name', `price` = '$price', `description` = '$description', `category_id` = '$category', `curator_id` = '$curator' WHERE `courses`.`id` =$id";
            $check_exist = mysqli_query($connection, "SELECT COUNT('id') AS `total_count` FROM `courses` WHERE (`name` = '$title' AND `id` != '$id')");
            $is_exist = mysqli_fetch_assoc($check_exist);
        }

        if ($is_exist['total_count'] != 0) {
            echo json_encode(['code' => 'error', 'message' => 'Такой курс уже существует!']);
        } else if ($result = !mysqli_query($connection, $query)) {
            if ($type == 'update') {
                echo json_encode(['code' => 'error', 'message' => 'Ошибка во время обновления курса!']);
            } else {
                echo json_encode(['code' => 'error', 'message' => 'Ошибка во время добавления курса!']);
            }

        } else {
            if ($type == 'update') {
                $message = 'Курс успешно обновлен!';
            } else {
                $message = 'Курс успешно добавлен!';
            }
            echo json_encode(['code' => 'ok', 'message' => $message, 'image' => $config['uploads'] . $file_name]);

        }
    }

