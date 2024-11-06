<?php
session_start();
$user = json_decode(file_get_contents("php://input"));
$admin_jsons = json_decode(file_get_contents("../files/admin.json"), true);

if (
    isset($user) &&
    isset($admin_jsons)
) {
    foreach ($admin_jsons as &$admin_json) {
        if (
            $admin_json["username"] == $_SESSION["un"] &&
            sha1($admin_json["password"]) == $_SESSION["pw"]
        ) {
            if (isset($user->action) && $user->action == "delete-profile-image") {
                print_r(json_encode(["profile_image" => "../images/default-avatar.svg"]));
                $admin_json["profile_image"] = "../images/default-avatar.svg";
                break;
            }
        }
    }
    file_put_contents("../files/admin.json", json_encode($admin_jsons, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    exit;
}