<?php
// Проверяем, авторизован ли пользователь
if (!isset($UID) || !$UID) {
    die("Доступ запрещен. Пожалуйста, авторизуйтесь.");
}

// Проверяем подключение к БД
if (!$link) {
    die("Ошибка подключения к базе данных");
}

// Запрос информации о пользователе
$UID_escaped = mysqli_real_escape_string($link, $UID);
$query = "SELECT * FROM users WHERE id='$UID_escaped'";
$rez = mysqli_query($link, $query);

if (!$rez) {
    die("Ошибка запроса: " . mysqli_error($link));
}

$ans = mysqli_fetch_assoc($rez);

if (!$ans) {
    die("Пользователь не найден");
}

// Выводим приветствие с именем пользователя
echo "<h1>Привет, " . htmlspecialchars($ans['login']) . "!</h1>";
echo "<p>Ваш email: " . htmlspecialchars($ans['email']) . "</p>";
echo "<p>Дата регистрации: " . date('d.m.Y', strtotime($ans['regdate'])) . "</p>";

// Выводим ссылку для выхода
echo "<p><a href='/?action=out'>Выход</a></p>";

// Проверяем, является ли пользователь администратором
if ($admin) {
    echo '<div style="background-color: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">';
    echo '<h3>Панель администратора</h3>';
    echo '<p>Этот раздел виден только администраторам</p>';
    echo '<ul>';
    echo '<li><a href="/admin/users.php">Управление пользователями</a></li>';
    echo '<li><a href="/admin/settings.php">Настройки сайта</a></li>';
    echo '</ul>';
    echo '</div>';
}
?>