<?php
session_start();

$user = json_decode(file_get_contents('php://input'));

if(isset($user) && isset($user->action)){
    if($user->action == "session_end"){
        session_unset();
        session_destroy();
        echo json_encode(["content"=>"deleted"]);
    }
}

