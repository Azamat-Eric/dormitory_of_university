<?php
session_start(); // Убедитесь, что сессия начата

// Загружаем данные из JSON файла
$dormitory_datas = json_decode(file_get_contents("../files/dormitory-data.json"), true);

// Проверяем наличие данных
if (!$dormitory_datas) {
    $_SESSION['error'] = 'Ошибка загрузки данных.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Получаем данные из POST-запроса
$selected_floor = $_POST['floor'] ?? '';
$selected_room = $_POST['room'] ?? '';

// Вывод отладочной информации
error_log("Полученные данные: Этаж - $selected_floor, Комната - $selected_room");

// Удаляем студентов из выбранной комнаты
$student_found = false; // Для отслеживания, были ли найдены студенты для удаления

foreach ($dormitory_datas as &$dormitory_data) {
    foreach ($dormitory_data['floors'] as &$floor_data) {
        if ($floor_data['floor-number'] == $selected_floor) {
            foreach ($floor_data['rooms'] as &$room_data) {
                if ($room_data['room-number'] == $selected_room) {
                    if (isset($room_data['students']) && !empty($room_data['students'])) {
                        // Удаляем всех студентов из комнаты
                        unset($room_data['students']);
                        $room_data['free-places'] = (int)$room_data['capacity']; // Устанавливаем все места свободными
                        $student_found = true; // Установим флаг, что студенты были найдены
                        break 3; // Прерываем все три цикла
                    }
                }
            }
        }
    }
}

// Сохраняем обновленные данные обратно в JSON файл
if (file_put_contents("../files/dormitory-data.json", json_encode($dormitory_datas))) {
    // Установим сообщение об успешном удалении в сессию и перенаправим обратно
    if ($student_found) {
        $_SESSION['success'] = 'Все студенты успешно удалены из комнаты.';
    } else {
        $_SESSION['error'] = 'В комнате не найдено студентов для удаления.';
    }
} else {
    $_SESSION['error'] = 'Ошибка при сохранении данных.';
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
