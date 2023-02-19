<?php
    session_start();
    require_once '../config.php';
    require_once '../functions.php';

    error_reporting(-1);

    if (empty($_SESSION['user']['role_name']) || $_SESSION['user']['role_name'] != 'student') {
        header('Location: /assets/pages/profile.php');
    //            debug($_SESSION['user']['role_id']);
    }

    if (isset($_POST['student_action'])) {
        switch ($_POST['student_action']) {
            case 'show':
                $begin_date =$_POST['begin-date'];
                $end_date =$_POST['end-date'];
                $query = "";

                if ($begin_date == null) {
                    $begin_date = date("Y.m.d H:i:s", null);
                }
                if ($end_date == null) {
                    $end_date = date("Y.m.d H:i:s");
                }
                $query = "SELECT * FROM `orders` WHERE `student_id` = ". $_SESSION['user']['id']." AND `ordering_time` BETWEEN '$begin_date' AND '$end_date'";


                if ($result = mysqli_query($connection, $query)) {
                    $orders = get_orders($query);

                    echo json_encode(['code' => 'ok', 'orders' => $orders]);
                } else {
                    echo json_encode(['code' => 'error', 'message' => 'Ошибка во время выполнения запроса!']);
                }
                break;

            case 'change':



                break;


        }
    }
    mysqli_close($connection);
?>