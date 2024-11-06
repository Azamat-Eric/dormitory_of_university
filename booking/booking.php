<?php
$json_url = "../files/dormitory-data.json";
$encoded_datas = (file_get_contents($json_url));
$decoded_datas = json_decode($encoded_datas);
echo "<div class='code' hidden>";
print_r($encoded_datas);
echo "</div>";

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="translateable">Бронирование комнат</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="booking.css">
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
                <a href="booking.php" class="nav nav-changed translateable">Бронирование</a>
                <a href="success_page/success_page.php" class="nav translateable">Мои заказы</a>
                <a href="../login/login.php" class="nav translateable">Вход</a>
            </div>
            <div class="nav" id="language">
                <span onclick="language_changed('kk')" id="kk" class="lang kk">ҚАЗ</span>
                <span onclick="language_changed('ru')" id="ru" class="lang ru changed_language">РУС</span>
                <span onclick="language_changed('en')" id="en" class="lang en">ENG</span>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Информационный блок -->
        <div class="infos">
            <div class="info-block block-1">
                <h3 class="translateable">Важная информация</h3>
                <div>
                    <p class="translateable">Пожалуйста, убедитесь, что вы вводите точные данные, так как они будут
                        использованы для создания вашего сертификата о бронировании.</p>
                    <p class="translateable">Бронирование может быть отменено в случае неправильного ввода данных.</p>
                    <p class="translateable">Каждый студент может забронировать только одну комнату.</p>
                    <p class="translateable">Если у вас возникли вопросы, свяжитесь с администрацией через раздел
                        "Контакты".</p>
                </div>
            </div>

            <div class="info-block block-2">
                <h3 class="translateable">Предупреждения!</h3>
                <div>
                    <p class="translateable">При бронировании убедитесь, что комната доступна. Данные о доступности
                        обновляются в режиме
                        реального времени.</p>
                    <p class="translateable">Если вы обнаружите ошибку в своих данных после отправки формы, ничего
                        страшного, просто заполните ее правильно еще раз и отправьте повторно.</p>
                    <p class="translateable">Подделка данных или изменение информации в сертификате бронирования
                        карается в
                        соответствии с внутренними правилами университета.</p>
                </div>
            </div>
        </div>

        <section class="booking-form">
            <div class="booking-container">
                <h2 class="translateable">Форма бронирования</h2>
                <form action="../server/process_booking.php" method="POST">

                    <div class="form-group">
                        <label for="student_name" class="translateable">Ваш ФИО:</label>
                        <input type="text" id="student_name" name="student_name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="translateable">Телефон:</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="translateable">Электронная почта:</label>
                        <input type="email" id="email" name="email">
                    </div>
                    
                    <div class="form-group">
                        <label for="group" class="translateable">Группа:</label>
                        <input type="text" id="group" name="group">
                    </div>

                    <div class="form-group">
                        <label for="gen" class="translateable">Ваш пол:</label>
                        <select name="gen" id="gen" required>
                            <option value="male" class="translateable">Мужской</option>
                            <option value="female" class="translateable">Женский</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="course" class="translateable">Какой курс:</label>
                        <select name="kurs" id="kurs" required>
                            <option value="default" selected disabled>...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dormitory" class="translateable">Общежития:</label>
                        <select name="dormitory" id="dormitory" required>
                            <option value="default" disabled selected class="translateable">Выберите</option>
                            <option value="first" class="translateable" disabled>№1 (улица Тищенко, 41)</option>
                            <option value="second" class="translateable" disabled>№2 (улица Тищенко, 35)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="floor" class="translateable">Номер этажа:</label>
                        <select name="floor" id="floor" required>
                            <option value="1" disabled>1</option>
                            <option value="2" disabled>2</option>
                            <option value="3" disabled>3</option>
                            <option value="4" disabled>4</option>
                            <option value="5" disabled>5</option>
                        </select>
                    </div>
                    
                    <div class="group-rooms">
                        <label for="room" class="translateable">Выберите комнату:</label>
                        <div id="room" name="room" required>
                            <!-- Комнаты будут загружены через PHP -->
                        </div>
                    </div>

                    <div class="form-group-last">
                        <button type="submit" class="btn translateable">Отправить</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <footer>
        <div class="footer-inner">
            <p>&copy; <span class="translateable">2024 КАРАГАНДИНСКИЙ ИНДУСТРИАЛЬНЫЙ УНИВЕРСИТЕТ. Все права
                    защищены.</span></p>
        </div>
    </footer>

    <div hidden></div>


    <script src="/script.js"></script>
    <script src="booking.js"></script>
</body>

</html>