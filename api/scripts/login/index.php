<?php
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm

if ($method == 'POST') {
    extract($_POST);
    //$salt = Database::execute('select salt from player where id = :id', array(":id" => $user_id))[0]["SALT"];
    //PasswordManager::hashFromString($new_password, $salt);
    $data = Database::execute('SELECT * FROM player WHERE username = :username or email = :username and password = :password', array(':username' => $username, ':password' => $password));
    if ($data != null) {
        echo json_encode($data[0]);
    } else {
        echo json_encode(['message' => 'User Not Found.']);
    }

} elseif($method == 'GET') {
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    //$salt = Database::execute('select salt from player where id = :id', array(":id" => $user_id))[0]["SALT"];
    //PasswordManager::hashFromString($new_password, $salt);
    $array1 = explode("?", $actual_link);
    $array2 = explode("&", $array1[1]);
    $array3 = explode("=", $array2[0]);
    $array4 = explode("=", $array2[1]);
    $username = $array3[1];
    $password = $array4[1];
    $data = Database::execute('SELECT * FROM player WHERE username = :username or email = :username and password = :password', array(':username' => $username, ':password' => $password));
    if ($data != null) {
        echo json_encode($data[0]);
    } else {
        echo json_encode(['message' => 'User Not Found.']);
    }

}