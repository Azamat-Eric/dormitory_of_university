<?php
session_start();

// Путь к файлу с данными
$json_url = '../../files/dormitory-data.json';

// Проверяем, есть ли данные в сессии
if (isset($_SESSION['post'])) {
    $postData = $_SESSION['post'];
    // print_r($_SESSION["post"]);
    unset($_SESSION['post']); // Очищаем данные из сессии после отображения
} else {
    $postData = null;
}

// Обработка поиска по номеру телефона
$previousOrders = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_phone'])) {
    $searchPhone = $_POST['search_phone'];

    // Загрузка данных из JSON
    $data = json_decode(file_get_contents($json_url), true);

    // Поиск предыдущих заказов
    foreach ($data as $dormitory) {
        if (isset($dormitory['floors'])) {
            foreach ($dormitory['floors'] as $floorNumber => $floor) {
                if (isset($floor['rooms'])) {
                    foreach ($floor['rooms'] as $roomNumber => $room) {
                        // Проверяем, существует ли массив студентов
                        if (isset($room['students']) && is_array($room['students'])) {
                            foreach ($room['students'] as $student) {
                                if ($student['phone'] === $searchPhone) {
                                    // Добавляем студента вместе с этажом и номером комнаты
                                    $student['floor'] = $floorNumber; // Добавляем этаж
                                    $student['room-number'] = $roomNumber; // Добавляем номер комнаты
                                    $student["dormitory"] = $dormitory["dormitory-name"];
                                    $previousOrders[] = $student; // Добавляем найденного студента в массив
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="translateable">Заявка принята</title>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="success_page.css">
</head>

<body>
    <div class="header">
        <div class="header-inner">
            <div class="logo">
                <img src="../../images/logo-ideal2.svg" alt="" class="logo-img">
                <span class="logo-text translateable">КАРАГАНДИНСКИЙ ИНДУСТРИАЛЬНЫЙ УНИВЕРСИТЕТ</span>
            </div>
            <div class="navs">
                <a href="/index.html" class="nav translateable">Главная</a>
                <a href="../booking.php" class="nav translateable">Бронирование</a>
                <a href="" class="nav nav-changed translateable">Мои заказы</a>
                <a href="../../login/login.php" class="nav translateable">Вход</a>
            </div>
            <div class="nav" id="language">
                <span onclick="language_changed('kk')" id="kk" class="lang kk">ҚАЗ</span>
                <span onclick="language_changed('ru')" id="ru" class="lang ru changed_language">РУС</span>
                <span onclick="language_changed('en')" id="en" class="lang en">ENG</span>
            </div>
        </div>
    </div>
    <div class="container">
        <?php if ($postData): ?>
            <h2>Данные о заявке:</h2>
            <table>
                <tr>
                    <th>Поле</th>
                    <th>Значение</th>
                </tr>
                <tr>
                    <td>Имя студента</td>
                    <td><?php echo htmlspecialchars($postData['student_name']); ?></td>
                </tr>
                <tr>
                    <td>Телефон</td>
                    <td><?php echo htmlspecialchars($postData['phone']); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo htmlspecialchars($postData['email']); ?></td>
                </tr>
                <tr>
                    <td>Группа</td>
                    <td><?php echo htmlspecialchars($postData['group']); ?></td>
                </tr>
                <tr>
                    <td>Пол</td>
                    <td><?php echo htmlspecialchars(($postData['gen'] == "male") ? "Мужской" : "Женский"); ?></td>
                </tr>
                <tr>
                    <td>Курс</td>
                    <td><?php echo htmlspecialchars($postData['kurs']); ?></td>
                </tr>
                <tr>
                    <td>Общежитие</td>
                    <td><?php echo htmlspecialchars(($postData['dormitory'] == "first") ? "№1 Общежития" : "№2 общежития"); ?></td>
                </tr>
                <tr>
                    <td>Этаж</td>
                    <td><?php echo htmlspecialchars($postData['floor']); ?></td>
                </tr>
                <tr>
                    <td>Номер комнаты</td>
                    <td><?php echo htmlspecialchars($postData['room-number']); ?></td>
                </tr>
            </table>
        <?php endif; ?>
        <div class="search-form">
            <h2>Поиск предыдущих заказов</h2>
            <form method="POST" action="">
                <label for="search_phone">Введите номер телефона:</label>
                <input type="text" name="search_phone" id="search_phone" required>
                <button class="button" type="submit">Поиск</button>
            </form>

            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($previousOrders)): ?>
                <h3>Найденные заказы:</h3>
                <table>
                    <tr>
                        <th>Имя</th>
                        <th>Телефон</th>
                        <th>Группа</th>
                        <th>Общежития</th>
                        <th>Этаж</th>
                        <th>Номер комнаты</th>
                    </tr>
                    <?php foreach ($previousOrders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($order['phone']); ?></td>
                            <td><?php echo htmlspecialchars($order['group']); ?></td>
                            <td><?php echo ($order['dormitory'] == "first")?"№1":"№2"; ?></td>
                            <td><?php echo htmlspecialchars($order['floor']); ?></td>
                            <td><?php echo htmlspecialchars($order['room-number']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <p>Нет заказов по этому номеру.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="footer-inner">
            <p>&copy; <span class="translateable">2024 КАРАГАНДИНСКИЙ ИНДУСТРИАЛЬНЫЙ УНИВЕРСИТЕТ. Все права
                    защищены.</span></p>
        </div>
    </footer>

    <script src="success_page.js"></script>
    <script src="/script.js"></script>
</body>

</html>