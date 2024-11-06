<?php
session_start();

if (isset($_SESSION["status"]) && $_SESSION["status"] == 1) {
    if (isset($_SESSION['un']) && isset($_SESSION["pw"])) {
        $admin_json_url = "../files/admin.json";
        $json_datas = json_decode(file_get_contents($admin_json_url), true);
        foreach ($json_datas as $json_data) {
            if (isset($json_data)) {
                if (
                    $json_data["username"] == $_SESSION["un"] &&
                    sha1($json_data["password"]) == $_SESSION["pw"]
                ) {
?>
                    <!DOCTYPE html>
                    <html lang="en">

                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Админ Общежития</title>
                        <link rel="stylesheet" href="admin.css">
                        <link rel="stylesheet" href="../style.css">
                        <link rel="stylesheet" href="dashboard/new.css">
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
                                    <a href="" class="nav nav-changed translateable">Вход</a>
                                </div>
                                <div class="nav" id="language">
                                    <span onclick="language_changed('kk')" id="kk" class="lang kk">ҚАЗ</span>
                                    <span onclick="language_changed('ru')" id="ru" class="lang ru changed_language">РУС</span>
                                    <span onclick="language_changed('en')" id="en" class="lang en">ENG</span>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div class="admin-panel">
                                <div class="admin-panel-top">
                                    <div class="admin-initials">
                                        <details class="admin-top-btns">
                                            <summary>
                                                <img src="../images/<?php echo $json_data["profile_image"] ?>" alt="" class="logo-small">
                                                <div class="summary-inner">
                                                    <span class="admin-initials-name translateable"><?php echo $json_data["initials"]; ?></span>
                                                    <span class="admin-initials-text">
                                                        <?php
                                                        echo ($_SESSION["role"] == "first") ? "<span class='translateable dormitory_num'>№1 общежитие</span>" : "<span class='translateable dormitory_num'>№2 общежитие</span>";
                                                        ?>
                                                    </span>
                                                </div>
                                            </summary>
                                            <ul class="admin-ul">
                                                <li class="admin-top-button translateable" onclick="change_div('dormitory-set')">Настройки общежития</li>
                                                <hr>
                                                <li id="admin-account" class="admin-top-button translateable" onclick="change_div('admin-data')">Аккаунт</li>
                                                <li class="admin-top-button translateable" id="logout-btn">Выйти</li>
                                            </ul>
                                        </details>
                                    </div>
                                </div>
                                <div class="admin-panel-container">
                                    <div class="admin-panel-container-inner">
                                        <?php
                                        // Установка страницы по умолчанию
                                        // $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard/dashboard';
                                        $page = isset($_GET['page']) ? $_GET['page'] : 'dormitory-set';
                                        require_once "$page.php";
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer>
                            <div class="footer-inner">
                                <p>&copy; <span class="translateable">2024 КАРАГАНДИНСКИЙ ИНДУСТРИАЛЬНЫЙ УНИВЕРСИТЕТ. Все права защищены.</span></p>
                            </div>
                        </footer>
                        <script src="admin.js"></script>
                        <script type="module" src="../script.js"></script>
                    </body>

                    </html>
<?php
                    break;
                }
            }
        }
    } else {
        header("Location: ../login/login.php");
        exit;
    }
} else {
    header("Location: ../login/login.php");
    exit;
}
?>
