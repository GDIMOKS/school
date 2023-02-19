<?php
    $connection = mysqli_connect(
        $config['db']['server'],
        $config['db']['username'],
        $config['db']['password'],
        $config['db']['name']
    );

    if (!$connection) {
        die('Не удалось подключиться к базе данных!');
    }
?>
