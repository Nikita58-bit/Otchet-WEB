<?php
// ВСЕ настройки сессии должны быть ДО session_start()
ini_set("session.use_trans_sid", "1");
// Другие настройки сессии
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// Проверяем, не запущена ли уже сессия
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Функция для запоминания времени последних действий пользователя
function lastAct($id, $link) {
    if (!$link) {
        die("Ошибка подключения к БД");
    }
    $tm = time();
    $id = mysqli_real_escape_string($link, $id);
    $query = "UPDATE users SET online='$tm', last_act='$tm' WHERE id='$id'";
    mysqli_query($link, $query);
}

// Функция проверки авторизации пользователя
function login($link) {
    if (!isset($_SESSION['id'])) {
        return false;
    }

    $user_id = $_SESSION['id'];
    
    // Проверяем существование пользователя в БД
    $user_id = mysqli_real_escape_string($link, $user_id);
    $query = "SELECT * FROM users WHERE id='$user_id'";
    $rez = mysqli_query($link, $query);
    
    if (!$rez) {
        return false;
    }
    
    if (mysqli_num_rows($rez) == 1) {
        $row = mysqli_fetch_assoc($rez);
        
        // Обновляем время активности
        lastAct($user_id, $link);
        return true;
    }
    
    return false;
}

// Функция входа пользователя
function enter($link) {
    $errors = [];
    
    // Проверяем, были ли отправлены данные формы
    if (!isset($_POST['login']) || trim($_POST['login']) == '' || 
        !isset($_POST['password']) || trim($_POST['password']) == '') {
        $errors[] = "empty_fields";
        return $errors;
    }
    
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    
    // Ищем пользователя в БД
    $query = "SELECT * FROM users WHERE login='$login' AND password='$password'";
    $result = mysqli_query($link, $query);
    
    if (!$result) {
        $errors[] = "db_error";
        return $errors;
    }
    
    if (mysqli_num_rows($result) == 0) {
        $errors[] = "wrong_credentials";
        return $errors;
    }
    
    $user = mysqli_fetch_assoc($result);
    
    // Устанавливаем сессию
    $_SESSION['id'] = $user['id'];
    $_SESSION['login'] = $user['login'];
    $_SESSION['role'] = $user['role'];
    
    // Обновляем время активности
    lastAct($user['id'], $link);
    
    return $errors; // Пустой массив - ошибок нет
}

// Функция выхода
function out() {
    // Очищаем сессию
    $_SESSION = array();
    
    // Удаляем куки сессии
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Уничтожаем сессию
    session_destroy();
    
    // Перенаправляем на главную
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Функция проверки администратора
function is_admin($UID, $link) {
    if (!$link || !$UID) {
        return false;
    }
    
    $UID = mysqli_real_escape_string($link, $UID);
    $query = "SELECT role FROM users WHERE id='$UID'";
    $result = mysqli_query($link, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Проверяем, является ли пользователь администратором
        return ($row['role'] == 'admin' || $row['role'] == 1 || $row['role'] == 'administrator');
    }
    
    return false;
}
?>