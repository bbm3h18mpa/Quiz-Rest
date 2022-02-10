<?php

if($method == 'GET'){
    
    $data = Database::execute("SELECT * FROM $apiPath WHERE username=:username and password=:password", array(':username' => $username, ':password' => $password));
    if ($data != null) {
        echo json_encode($data[0]);
    } else {
        echo json_encode(['message' => 'User Not Found.']);
    }
    
}