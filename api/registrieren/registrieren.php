<?php

if($method == 'POST'){
    if ($_POST != null) {
        extract($_POST);
        Database::execute("INSERT INTO $apiPaths VALUES(null, :email, :username, :password, null, null, 1)", array(':email' => $email, ':username' => $username, ':password' => $password));
        $data = Database::execute("SELECT * FROM $apiPaths ORDER BY id DESC LIMIT 1");
        echo json_encode(['message' => 'Registrierung erfolgreich.', 'success' => true, 'user' => $data[0]]);
    } else {
        echo json_encode(['message' => 'Registrierung nicht erfolgreich', 'success' => false]);
    }
}




?>