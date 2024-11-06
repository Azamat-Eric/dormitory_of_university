<?php
session_start();

$admin_json_url = "../files/admin.json";
$json_datas = json_decode(file_get_contents($admin_json_url), true);

$username = $_POST["username"];
$password = $_POST["password"];

$found = false;

if (isset($username) && isset($password)) {
    foreach ($json_datas as $json_data) {
        if ($username == $json_data["username"] && sha1($password) == sha1($json_data["password"])) {
            $_SESSION = [
                "un" => $json_data["username"],
                "pw" => sha1($json_data["password"]),
                "status" => 1,
                "role" => $json_data["role"]
            ];
            // print_r("Testing: ". $json_data["username"] ."<br>");
            header("Location: ../admin_panel/admin.php");
            $found = true;
            break;
            // print_r($_SESSION);
        }
    }

    if (!$found) {
        $_SESSION["status"] = 0;
        // print_r($_SESSION);
        header("Location: ../login/login.php");
    }
    print_r($_SESSION);
} else {
    echo "No username and password";
}
