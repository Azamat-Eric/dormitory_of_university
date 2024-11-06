<?php
session_start(); // Убедитесь, что сессия начата

// Загружаем данные из JSON файла
$dormitory_datas = json_decode(file_get_contents("../files/dormitory-data.json"), true);

// Проверяем наличие данных
if (!$dormitory_datas) {
    // Установим сообщение об ошибке в сессии и перенаправим обратно
    $_SESSION['error'] = 'Ошибка загрузки данных.';
    header('Location: ' . $_SERVER['HTTP_REFERER']); // Возврат на предыдущую страницу
    exit;
}

// Получаем данные из POST-запроса
$fullname = $_POST['fullname'] ?? ''; // Полное имя
$phone = $_POST['phone'] ?? ''; // Номер телефона

if ($fullname && $phone) {
    // Здесь необходимо реализовать логику удаления студента из массива данных
    foreach ($dormitory_datas as &$dormitory_data) {
        foreach ($dormitory_data['floors'] as &$floor_data) {
            foreach ($floor_data['rooms'] as &$room_data) {
                if (isset($room_data['students'])) {
                    foreach ($room_data['students'] as $key => $student) {
                        if ($student['fullname'] === $fullname && $student['phone'] === $phone) {
                            // Удаляем студента
                            unset($room_data['students'][$key]);
                            
                            // Увеличиваем свободные места
                            $room_data['free-places'] = (int)$room_data['free-places'] + 1;
                            $floor_data['floor-room-free'] = (int)$floor_data['floor-room-free'] + 1;

                            break 3; // Прерываем все три цикла
                        }
                    }
                }
            }
        }
    }
}

// Сохраняем обновленные данные обратно в JSON файл
file_put_contents("../files/dormitory-data.json", json_encode($dormitory_datas));

// Установим сообщение об успешном удалении в сессию и перенаправим обратно
$_SESSION['success'] = 'Студент успешно удален.';
header('Location: ' . $_SERVER['HTTP_REFERER']); // Возврат на предыдущую страницу
exit;
?>
