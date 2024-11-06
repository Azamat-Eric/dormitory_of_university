<?
session_start();

// Отключить отображение ошибок
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="header">
        <div class="header-inner">
            <div class="logo">
                <img src="../images/logo-ideal2.svg" alt="" class="logo-img">
                <span class="logo-text translateable">КАРАГАНДИНСКИЙ ИНДУСТРИАЛЬНЫЙ УНИВЕРСИТЕТ</span>
            </div>
            <div class="navs">
                <a href="/index.html" class="nav translateable">Главная</a>
                <a href="../booking/booking.php" class="nav translateable">Бронирование</a>
                <a href="../booking/success_page/success_page.php" class="nav translateable">Мои заказы</a>
                <a href="../login/login.php" class="nav nav-changed translateable">Вход</a>
            </div>
            <div class="nav" id="language">
                <span onclick="language_changed('kk')" id="kk" class="lang kk">ҚАЗ</span>
                <span onclick="language_changed('ru')" id="ru" class="lang ru changed_language">РУС</span>
                <span onclick="language_changed('en')" id="en" class="lang en">ENG</span>
            </div>
        </div>
    </div>
    <div class="container-l">
        <div class="login-container">
            <h2 class="translateable">Вход в систему</h2>
            <form action="../server/process_login.php" method="post">
                <div class="input-group">
                    <input type="text" name="username" id="username" required>
                    <label for="username"><span class="translateable">Имя пользователя</span></label>
                </div>
                <div class="input-group password-group">
                    <input type="password" name="password" id="password" required>
                    <label for="password"><span class="translateable">Пароль</span></label>
                    <span class="toggle-password translateable" onclick="togglePasswordVisibility()">Показать</span>
                </div>
                <button type="submit" class="translateable">Войти</button>
            </form>
            <div class="forgot-password">
                <?php
                if (isset($_SESSION["status"])) {
                    print_r($_SESSION['status']);
                    if ($_SESSION['status'] == "error") {
                        echo '<span class="incorrect translateable">Неверный логин или пароль!</span>';
                        unset($_SESSION["status"]);
                    }
                } 
                // session_destroy();
                ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-inner">
            <p>&copy; <span class="translateable">2024 КАРАГАНДИНСКИЙ ИНДУСТРИАЛЬНЫЙ УНИВЕРСИТЕТ. Все права
                    защищены.</span></p>
        </div>
    </footer>

    <script src="login.js"></script>
    <script src="../script.js"></script>
</body>

</html>