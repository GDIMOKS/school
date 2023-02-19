
<div class="grid-container">
    <div class="grid-item file">
        <label>Обложка курса</label>
        <input class="input" name="image" type="file">
    </div>
    <div class="grid-item photo">
        <img class="picture" src="" alt="Обложка курса">
    </div>
    <div class="grid-item name">
        <label>Название курса</label>
        <input type="text" name="name" placeholder="Введите название">
    </div>

    <div class="grid-item description">
        <label>Описание курса</label>
        <input type="text" name="description" placeholder="Введите описание">
    </div>

    <div class="grid-item tutor">
        <?php
            $query = "SELECT `users`.`id`, CONCAT(COALESCE(`users`.`last_name`, ''), ' ', `users`.`first_name`, ' ', COALESCE(`users`.`patronymic_name`, '')) as `curator_name` FROM `users` INNER JOIN `roles` ON `roles`.`id` = `users`.`role_id` WHERE `roles`.`name` = 'curator'";
            $result = mysqli_query($connection, $query);
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        ?>
        <label>Куратор</label>
        <select class="curator">
            <?php foreach ($users as $user): ?>
                <option data-id="<?=$user['id']?>"><?=trim($user['curator_name'])?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="grid-item category">
        <?php
            $query = "SELECT `categories`.`id`, `categories`.`name` FROM `categories`";
            $result = mysqli_query($connection, $query);
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        ?>
        <label>Категория</label>
        <select class="category_sel">
            <?php foreach ($categories as $category): ?>
                <option data-id="<?=$category['id']?>"><?=$category['name']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="grid-item price">
        <label>Стоимость курса</label>
        <input type="text" name="price" placeholder="Введите стоимость">
    </div>
</div>
