<?php
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm

if ($method == 'GET') {
    extract($_POST);
    $salt = Database::execute('select salt from player where id = :id', array(":id" => $user_id))[0]["SALT"];
    PasswordManager::hashFromString($new_password, $salt);
    $data = Database::execute('SELECT * FROM player WHERE username=:username and password=:password', array(':username' => $username, ':password' => $password));
    if ($data != null) {
        echo json_encode($data[0]);
    } else {
        echo json_encode(['message' => 'User Not Found.']);
    }

}