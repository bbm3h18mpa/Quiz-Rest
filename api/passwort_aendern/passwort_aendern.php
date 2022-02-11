<?php

if ($method == 'PUT') {
            extract(json_decode(file_get_contents('php://input'), true));
            // Update the Post in the Database
            Database::query("UPDATE $apiPath SET password=:password WHERE username = :username and email = :email", array(':password' => $password, ':username' => $username, ':email' => $email));
            $data = Database::query("SELECT * FROM $apiPath WHERE username=:username", array(':username' => $username));
            echo json_encode(['post' => $data[0], 'message' => 'Passwort Updated successfully', 'success' => true]);
        }