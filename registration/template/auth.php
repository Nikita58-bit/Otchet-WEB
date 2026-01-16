<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>MySite - Авторизация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .auth-form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .error {
            color: red;
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            background-color: #e6ffe6;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
        .admin-message {
            margin-top: 30px;
            padding: 15px;
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            border-radius: 3px;
        }
        .welcome-message {
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }
        .error-field {
            border-color: #f44336 !important;
            border-width: 2px !important;
            background-color: #fff5f5;
        }
    </style>
</head>
<body>
    <div class="auth-form">
        <?php if (!isset($UID) || !$UID): ?>
            <!-- Если пользователь НЕ авторизован -->
            <h2 style="text-align: center;">Вход в систему</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error">
                    <?php 
                    if (in_array("empty_fields", $error)) {
                        echo "<p>Пожалуйста, заполните все поля</p>";
                    } elseif (in_array("wrong_credentials", $error)) {
                        echo "<p>Неверный логин или пароль</p>";
                    } elseif (in_array("db_error", $error)) {
                        echo "<p>Ошибка подключения к базе данных</p>";
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div>
                    <label for="login">Логин:</label>
                    <input type="text" name="login" id="login" 
                           class="<?php echo (!empty($error) && (in_array('empty_fields', $error) || in_array('wrong_credentials', $error))) ? 'error-field' : ''; ?>" 
                           value="<?php echo isset($_POST['login']) ? htmlspecialchars($_POST['login']) : ''; ?>" />
                </div>
                
                <div>
                    <label for="password">Пароль:</label>
                    <input type="password" name="password" id="password" 
                           class="<?php echo (!empty($error) && (in_array('empty_fields', $error) || in_array('wrong_credentials', $error))) ? 'error-field' : ''; ?>" />
                </div>
                
                <div>
                    <input type="submit" value="Войти" name="log_in" />
                </div>
            </form>
            
            <p style="margin-top: 20px; text-align: center;">
                Нет аккаунта? <a href="/registration/">Зарегистрироваться</a>
            </p>
        <?php else: ?>
            <!-- Если пользователь авторизован -->
            <div class="welcome-message">
                <?php if ($admin): ?>
                    <h2 style="color: #2196F3;">Привет, Admin!</h2>
                    <p>Вы вошли как администратор: <strong><?php echo htmlspecialchars($_SESSION['login']); ?></strong></p>
                <?php else: ?>
                    <h2 style="color: #4CAF50;">Привет, User!</h2>
                    <p>Вы вошли как пользователь: <strong><?php echo htmlspecialchars($_SESSION['login']); ?></strong></p>
                <?php endif; ?>
                
                <a href="?action=out" class="logout-btn">Выйти</a>
            </div>
            
            <?php if ($admin): ?>
                <div class="admin-message">
                    <h3>Секретная информация для администратора:</h3>
                    <p>Это сообщение видно только администраторам системы.</p>
                    <p>Здесь вы можете управлять пользователями, настройками и другими функциями сайта.</p>
                    <p>Текущее время сервера: <?php echo date('H:i:s'); ?></p>
                    <p>Ваш ID: <?php echo $UID; ?></p>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px; padding: 15px; background-color: #f9f9f9; border-radius: 3px;">
                <h3>Основной контент</h3>
                <p>Добро пожаловать в личный кабинет!</p>
                <p>Здесь может быть ваша информация, статистика или другие данные.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        // JavaScript для сброса класса ошибки при фокусе на поле
        document.addEventListener('DOMContentLoaded', function() {
            var errorFields = document.querySelectorAll('.error-field');
            errorFields.forEach(function(field) {
                field.addEventListener('focus', function() {
                    this.classList.remove('error-field');
                });
            });
        });
    </script>
</body>
</html>