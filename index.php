<?php
// Подключаем файлы в правильном порядке
include('lib/connect.php'); // Подключение к БД и старт сессии
include('lib/function_global.php'); // Функции

// Инициализируем переменные
$UID = null;
$admin = false;
$error = [];

// Обработка выхода из системы (делаем это в первую очередь)
if (isset($_GET['action']) && $_GET['action'] == "out") {
    out(); // Вызываем функцию выхода
}

// Проверяем, авторизирован ли пользователь
if (login($link)) {
    // Если пользователь авторизирован
    $UID = $_SESSION['id'];
    $admin = is_admin($UID, $link);
} else {
    // Если пользователь не авторизирован - проверяем форму входа
    if (isset($_POST['log_in'])) {
        $error = enter($link); // Пытаемся войти
        
        // Если вход успешен
        if (empty($error)) {
            $UID = $_SESSION['id'];
            $admin = is_admin($UID, $link);
            // Перенаправляем на главную страницу
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Подключаем файл с формой авторизации
include('registration/template/auth.php');
?>