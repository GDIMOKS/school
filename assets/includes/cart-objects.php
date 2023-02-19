<?php
    session_start();
    require_once './config.php';
    require_once './functions.php';

    error_reporting(-1);

    if (isset($_GET['cart'])) {
        switch ($_GET['cart']) {
            case 'add':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $type = $_GET['type'];
                $product = get_object($id, $type);

                if (!$product) {
                    echo json_encode(['code' => 'error', 'answer' => 'Error product']);

                } else {
                    add_to_cart($product, $type);

                    echo json_encode(['code' => 'ok', 'product' => $product, 'total_count' => $_SESSION['cart.count'], 'count' => $_SESSION['cart'][$product['id']]['count'], 'total_sum' => $_SESSION['cart.sum']]);

                }

                break;

            case 'delete':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $type = $_GET['type'];
                $product = get_object($id,  $type);

                if (!$product) {
                    echo json_encode(['code' => 'error', 'answer' => 'Error product']);

                } else {
                    del_from_cart($product);
                    $count = $_SESSION['cart'][$product['id']]['count'] ?? 0;
                    echo json_encode(['code' => 'ok', 'product' => $product, 'total_count' => $_SESSION['cart.count'] ?? 0, 'count' => $count, 'total_sum' => $_SESSION['cart.sum']]);
                }
                break;
        }
    }

    if (isset($_POST['cart'])) {
        switch ($_POST['cart']) {
            case 'checkout':
                if (isset($_SESSION['cart'])) {
                    if( isset($_SESSION['user'])) {

                        $query = "INSERT INTO `orders` (`student_id`) VALUES ('" . $_SESSION['user']['id']. "')";
                        $result = mysqli_query($connection, $query);

                        $order_id = mysqli_insert_id($connection);

                        foreach ($_SESSION['cart'] as $product) {
                            switch ($product['type']) {
                                case 'courses':
                                    $query = "INSERT INTO `course_order` (`order_id`, `course_id`, `period`) VALUES ('$order_id', '".$product['id']."', '".$product['count']."')";
                                    break;

                                case 'consultations':
                                    $query = "INSERT INTO `consultation_order` (`order_id`, `consultation_id`, `count`) VALUES ('$order_id', '".$product['id']."', '".$product['count']."')";
                                    break;
                            }
                            $result = mysqli_query($connection, $query);
                        }

                        if (!$result) {
                            echo json_encode(['code' => 'error', 'answer' => 'Error product']);

                        } else {
                            clear_cart();
                            echo json_encode(['code' => 'ok', 'answer' => $order_id]);
                        }

                    }

                }
                break;
        }
    }

//    debug($_SESSION['cart']);
?>

