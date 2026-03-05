<?php
if (!defined('DB_HOST')) {
    if (file_exists(__DIR__ . '/../config.php')) {
        require_once(__DIR__ . '/../config.php');
    } else {
        die('Ошибка: config.php не найден.');
    }
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
    die('Ошибка подключения к базе данных (' . $mysqli->connect_errno . '): ' . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");
?>