<?php
session_start();

if (isset($_SESSION["status"]) && $_SESSION["status"] == 1) {
    if (isset($_SESSION['un']) && isset($_SESSION["pw"])) {
        $admin_json_url = "../files/admin.json";
        $json_datas = json_decode(file_get_contents($admin_json_url), true);

        if ($json_datas === null) {
            die("Ошибка декодирования JSON файла.");
        }

        $updated = false; // Флаг для отслеживания успешного обновления данных

        foreach ($json_datas as &$json_data) { // Используем ссылку для изменения массива
            if (
                $json_data["username"] == $_SESSION["un"] &&
                sha1($json_data["password"]) == $_SESSION["pw"]
            ) {
                // Обновление данных
                $json_data["initials"] = $_POST["fullName"] ?? $json_data["initials"];
                $json_data["email"] = $_POST["email"] ?? $json_data["email"];
                $json_data["phone_number"] = $_POST["phone_number"] ?? $json_data["phone_number"];
                $json_data["username"] = $_POST["username"] ?? $json_data["username"];
                $json_data["password"] = $_POST["password"] ?? $json_data["password"];

                // Обработка файла
                if (!empty($_FILES["profileImage"]["name"])) {
                    $profile_img = $_FILES["profileImage"]["name"];
                    $filepath = "../images/" . basename($profile_img);
                    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $filepath)) {
                        $json_data["profile_image"] = $profile_img; // Обновление пути к изображению
                    } else {
                        echo "Ошибка загрузки изображения.";
                    }
                } else {
                    // Если файл не загружен, оставляем старое значение
                    $json_data["profile_image"] = $_POST["profile-photo-name"] ?? $json_data["profile_image"];
                }

                $_SESSION["un"] = $json_data["username"];
                $_SESSION["pw"] = sha1($json_data["password"]);
                $updated = true; // Обновление данных успешно
                break;
            }
        }

        if ($updated) {
            // Перезапись JSON файла
            if (file_put_contents($admin_json_url, json_encode($json_datas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
                echo "Ошибка записи в файл JSON.";
            } else {
                header("Location: ../admin_panel/admin.php");
                exit(); // Не забывайте завершить выполнение скрипта после перенаправления
            }
        } else {
            echo "Пользователь не найден или неправильный пароль.";
        }
    } else {
        echo "Не установлены данные сессии.";
    }
} else {
    echo "Не выполнен вход в систему.";
}
?>
