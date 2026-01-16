<?php

// Устанавливаем временную зону для предотвращения предупреждений
date_default_timezone_set('Europe/Moscow');

// Отключаем вывод ошибок на продакшене (для отладки включено)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Параметры подключения
$host = 'localhost';
$user = 'mysite';
$password = 'mysite';
$database = 'products';
$charset = 'utf8mb4';

// Подключаемся к БД
$link = mysqli_connect($host, $user, $password, $database);

// Проверяем успешность подключения
if (!$link) {
    // Более дружелюбное сообщение для пользователя
    $error_msg = "Извините, временные технические неполадки. Попробуйте позже.";
    // Для отладки можно включить детальное сообщение:
    // $error_msg = "Ошибка подключения к базе данных: " . mysqli_connect_error();
    
    // Записываем ошибку в лог
    error_log("Ошибка подключения к БД: " . mysqli_connect_error());
    
    // Выводим сообщение пользователю
    die('<div style="text-align: center; padding: 50px; font-family: Arial;">
            <h2>Ошибка подключения</h2>
            <p>' . $error_msg . '</p>
            <p><a href="/">Вернуться на главную</a></p>
         </div>');
}

// Устанавливаем кодировку соединения
if (!mysqli_set_charset($link, $charset)) {
    error_log("Ошибка установки кодировки: " . mysqli_error($link));
}

// Функция для безопасного закрытия соединения
function close_connection($link) {
    if ($link) {
        mysqli_close($link);
    }
}

// Регистрируем функцию закрытия соединения при завершении скрипта
register_shutdown_function('close_connection', $link);
?>