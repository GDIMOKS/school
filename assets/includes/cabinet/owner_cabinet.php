<?php
    session_start();
    require_once '../config.php';
    require_once '../functions.php';

    error_reporting(-1);

    if (empty($_SESSION['user']['role_name']) || $_SESSION['user']['role_name'] != 'owner') {
        header('Location: /assets/pages/profile.php');
    }

    if (isset($_POST['owner_action'])) {
        switch ($_POST['owner_action']) {
            case 'rating':
                $begin_date = $_POST['begin-date'];
                $end_date = $_POST['end-date'];

                if ($begin_date == null) {
                    $begin_date = date("Y.m.d H:i:s", null);
                }
                if ($end_date == null) {
                    $end_date = date("Y.m.d H:i:s");
                }

                $query = "SELECT
                                `courses`.`id`,
                                `courses`.`name`,
                                `courses`.`image`,
                                SUM(`courses`.`price` * `course_order`.`period`) as `total_price`
                            FROM  `courses`
                            INNER JOIN `course_order`
                            ON `course_order`.`course_id` = `courses`.`id`
                            INNER JOIN `orders`
                            ON `course_order`.`order_id` = `orders`.`id`
                            WHERE `orders`.`purchase_time`
                            BETWEEN '$begin_date' AND '$end_date'
                            GROUP BY `courses`.`id`
                            ORDER BY `total_price` DESC
                            LIMIT 3";

                $courses = get_objects_query($query);

                $query = "SELECT
                            `consultations`.`id`,
                            `consultations`.`name`,
                            SUM(`consultations`.`price` * `consultation_order`.`count`) as `total_price`
                        FROM  `consultations`
                        INNER JOIN `consultation_order`
                        ON `consultation_order`.`consultation_id` = `consultations`.`id`
                        INNER JOIN `orders`
                        ON `consultation_order`.`order_id` = `orders`.`id`
                        WHERE `orders`.`purchase_time`
                        BETWEEN '$begin_date' AND '$end_date'
                        GROUP BY `consultations`.`id`
                        ORDER BY `total_price` DESC
                        LIMIT 3";
                $consults = get_objects_query($query);

                echo json_encode(['code' => 'ok', 'courses' => $courses, 'consults' => $consults]);

                break;

            case 'profit':
                $begin_date = $_POST['begin-date'];
                $end_date = $_POST['end-date'];

                if ($begin_date == null) {
                    $begin_date = date("Y.m.d H:i:s", null);
                }
                if ($end_date == null) {
                    $end_date = date("Y.m.d H:i:s");
                }

                $query = "SELECT
                                SUM(`course_order`.`period` * `courses`.`price`) as `total_price`
                            FROM  `courses`
                            INNER JOIN `course_order`
                            ON `course_order`.`course_id` = `courses`.`id`
                            INNER JOIN `orders`
                            ON `course_order`.`order_id` = `orders`.`id`
                            WHERE `orders`.`purchase_time`
                            BETWEEN '$begin_date' AND '$end_date'";

                $courses = get_objects_query($query);

                $query = "SELECT
                                SUM(`consultation_order`.`count` * `consultations`.`price`) as `total_price`
                            FROM  `consultations`
                            INNER JOIN `consultation_order`
                            ON `consultation_order`.`consultation_id` = `consultations`.`id`
                            INNER JOIN `orders`
                            ON `consultation_order`.`order_id` = `orders`.`id`
                            WHERE `orders`.`purchase_time`
                            BETWEEN '$begin_date' AND '$end_date'";

                $consults = get_objects_query($query);
                $total_price = $consults[0]['total_price'] + $courses[0]['total_price'];

                echo json_encode(['code' => 'ok', 'total_price' => $total_price]);

                break;

        }
    }
?>