<?php
    session_start();

    require_once "../../includes/config.php";
    require_once "../../includes/functions.php";
    require_once "../../includes/cookie.php";

    if (empty($_SESSION['user']['role_name']) || $_SESSION['user']['role_name'] != 'seller')
    {
        header('Location: /assets/pages/cabinet.php');
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Обновить товар</title>

        <link rel="stylesheet" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/header.css">
        <link rel="stylesheet" href="../../css/cabinet.css">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>
    </head>
    <body>
        <?php
            require_once "../../includes/header.php";
        ?>

        <div class="workspace">

                <form>
                    <?php
                        $query = "SELECT `courses`.*, 
                                            (SELECT CONCAT(COALESCE(`users`.`last_name`, ''), ' ', `users`.`first_name`, ' ', COALESCE(`users`.`patronymic_name`, '')) 
                                             FROM `users` 
                                             WHERE `courses`.`curator_id` = `users`.`id`) as `curator_name`, 
                                            (SELECT `categories`.`name` 
                                             FROM `categories` 
                                             WHERE `courses`.`category_id` = `categories`.`id`) as `category_name` 
                                        FROM `courses`";
                        $courses = get_objects_query($query);
                    ?>
                    <select class="upd_select" style="width: 100%">
                        <?php foreach ($courses as $course): ?>
                            <option data-id="<?=$course['id']?>"><?=$course['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </form>

            <form class="update-course-form" data-id="">
                <?php
                    require_once "./add-form.php";
                ?>

                <input class="button" type="submit" value="Изменить данные">

                <div class="errors_block">

                </div>
            </form>
        </div>
        <script type="module" src="../../js/cabinet/seller_cabinet.js"></script>


    </body>
</html>
<?php
    mysqli_close($connection);
?>