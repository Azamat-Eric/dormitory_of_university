<?php
ob_start(); // Включаем буферизацию вывода
session_start();

$full_json = "../files/dormitory-data.json";
$fast_json = "../files/dormitory-fast.json";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем существование и читаем содержимое файла
    if (file_exists($full_json)) {
        // Декодируем содержимое JSON-файла в массив
        $json_contents = file_get_contents($full_json);
        $datas = json_decode($json_contents, true);

        if (!is_array($datas)) {
            $datas = []; // Если файл пуст или не является массивом, инициализируем пустой массив
        }

        // Ищем, существует ли запись с нужной ролью
        $found = false;
        foreach ($datas as &$data) {
            if (isset($data["dormitory-name"]) && $data["dormitory-name"] === $_POST["dormitory-name"]) {
                $data = $_POST; // Обновляем запись
                $found = true;
                break;
            }
        }

        if (!$found) {
            // Если запись с нужной ролью не найдена, добавляем новую
            $datas[] = $_POST;
        }

        // Сохраняем обновленный массив обратно в файл
        file_put_contents($full_json, json_encode($datas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $_SESSION["message"] = "Сохранено успешно";
    } else {
        // Если файл не существует, создаем его с содержимым POST-запроса
        file_put_contents($full_json, json_encode([$_POST], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $_SESSION["message"] = "Сохранено успешно";
    }
}

// Перенаправление после обработки
header("Location: ../admin_panel/admin.php");
ob_end_flush(); // Отправляем вывод в браузер
exit();
?>
