<?php
session_start();
$json_url = "../files/dormitory-data.json"; // Путь к файлу с данными
$encoded_datas = file_get_contents($json_url); // Чтение файла JSON

if ($encoded_datas === false) {
    die("Ошибка при чтении файла JSON");
}

$decoded_datas = json_decode($encoded_datas, true); // Декодирование JSON в массив

// Проверка на валидность декодирования
if (!is_array($decoded_datas)) {
    die("Ошибка при декодировании JSON. Возможно, файл поврежден или содержит неверные данные.");
}

$post = $_POST; // Данные, отправленные через POST

if (isset($post)) {
    $applicant_phone = $post["phone"]; // Номер телефона заявителя
    $old_booking_found = false; // Флаг, найдено ли предыдущее бронирование

    // Ищем старое бронирование по номеру телефона во всех общежитиях, этажах и комнатах
    foreach ($decoded_datas as &$dormitory) {
        foreach ($dormitory["floors"] as &$floor_content) {
            foreach ($floor_content["rooms"] as &$room) {
                // Убедимся, что массив students существует
                if (!isset($room["students"])) {
                    $room["students"] = []; // Инициализируем как пустой массив
                }

                // Проверяем всех студентов в комнате
                foreach ($room["students"] as $key => $student) {
                    if ($student["phone"] == $applicant_phone) {
                        // Удаляем студента
                        unset($room["students"][$key]);

                        // Увеличиваем количество свободных мест
                        $room["free-places"] = strval(intval($room["free-places"]) + 1);

                        // Увеличиваем количество свободных комнат на этаже
                        $floor_content["floor-room-free"] = strval(intval($floor_content["floor-room-free"]) + 1);

                        $old_booking_found = true; // Установим флаг, что старое бронирование найдено и удалено
                        break 3; // Прерываем цикл, так как нашли и обработали старую запись
                    }
                }
            }
        }
    }

    // Теперь занимаемся новым бронированием
    $dormitory_found = false;
    foreach ($decoded_datas as &$decoded_data) { // Используем ссылку, чтобы изменить данные общежития
        if ($post["dormitory"] == $decoded_data["dormitory-name"]) {
            $dormitory_found = true; // Общежитие найдено
            
            // Поиск нужного этажа
            foreach ($decoded_data["floors"] as $floor => &$floor_content) {
                if ($post["floor"] == $floor) {
                    // Поиск комнаты
                    if (isset($floor_content["rooms"][$post["room-number"]])) {
                        $room = &$floor_content["rooms"][$post["room-number"]]; // Ссылка на комнату

                        // Убедимся, что массив students существует
                        if (!isset($room["students"])) {
                            $room["students"] = []; // Инициализируем как пустой массив
                        }

                        $free_places = intval($room["free-places"]);

                        // Если есть свободные места
                        if ($free_places > 0) {
                            // Добавление студента в комнату
                            $room["students"][] = [
                                "fullname" => $post["student_name"],
                                "kurs" => $post["kurs"],
                                "group" => $post["group"],
                                "phone" => $post["phone"],
                                "email" => $post["email"],
                                "gen" => $post["gen"],
                                "booking_date" => date("d.m.Y")
                            ];

                            // Уменьшение количества свободных мест в комнате на 1
                            $room["free-places"] = strval($free_places - 1);

                            // Уменьшение количества свободных комнат на этаже
                            $floor_content["floor-room-free"] = strval(intval($floor_content["floor-room-free"]) - 1);
                            $_SESSION["post"] = $post;
                            header("Location: ../booking/success_page/success_page.php");
                            echo "Студент успешно добавлен!";
                        } else {
                            echo "В комнате нет свободных мест!";
                        }
                    } else {
                        echo "Комната не найдена!";
                    }
                    break;
                } else {
                    echo "Этаж не найден!";
                }
            }
            break;
        }
    }

    // Если общежитие было найдено и обновлено
    if ($dormitory_found) {
        // Обновление данных в JSON файле
        $new_json_data = json_encode($decoded_datas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($json_url, $new_json_data); // Запись обновленных данных обратно в файл
        echo "Данные успешно обновлены!";
    } else {
        echo "Общежитие не найдено!";
    }

    // Сообщение если старое бронирование не найдено
    if (!$old_booking_found) {
        echo "Предыдущее бронирование не найдено, студент добавлен.";
    }
}
?>