<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $server = "127.0.0.1";
    $user = "root";
    $password = "root";
    $db = "test_db";

    $connection = new mysqli($server, $user, $password, $db);

    if ($connection -> connect_error) {
        printf('Не удалось подключиться к базе данных!<br>', $connection -> connect_error);
        exit();
    }
?>