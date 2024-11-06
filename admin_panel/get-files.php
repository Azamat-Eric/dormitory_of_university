<?php

$user = json_decode(file_get_contents("php://input"));
print_r($user);
$empty_file = 'empty.php';  // Здесь должно быть имя файла, а не его содержимое
if (isset($user) && isset($user->action)) {
    if ($user->action == "close") {
        $content = file_get_contents("dashboard/dashboard.php");
        file_put_contents($empty_file, $content);
    }else{
        $content = file_get_contents($user->action . ".php");  // Обращаемся к имени файла
        file_put_contents($empty_file, $content);  // Записываем содержимое в файл
        print_r(json_encode(["status" => 1]));
    }
} else {
    print_r(json_encode(["status" => 0, "error" => "Invalid action"]));
}
