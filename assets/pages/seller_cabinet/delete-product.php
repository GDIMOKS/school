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
        <title>Удалить товар</title>

        <link rel="stylesheet" href="../../css/style.css">
        <link rel="stylesheet" href="../../css/header.css">
        <link rel="stylesheet" href="../../css/delete_item.css">

        <script type="text/javascript" src="/assets/js/jquery-3.6.1.min.js"></script>


    </head>
    <body>
        <?php
            require_once "../../includes/header.php";
        ?>

        <div class="workspace">
            <div class="list_courses">
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
                <?php foreach ($courses as $course): ?>
                <div class="item_courses" id="del-prod-<?=$course['id']?>">
                    <img src="<?=$config['uploads'].$course['image']?>" alt="<?=$course['name']?>" class="img_courses">
                    <div class="description_courses">
                        <h1><?=$course['name']?></h1>
                        <p><?=$course['description']?></p>

                        <p>Куратор курса: <?=$course['curator_name']?></p>
                        <p>Категория: <?=$course['category_name']?></p>

                    </div>
                    <div class="button delete-from-db" style="width: 20%" data-id="<?=$course['id']?>">Удалить</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script type="module" src="../../js/cabinet/seller_cabinet.js"></script>

    </body>
</html>
<?php
    mysqli_close($connection);
?>
