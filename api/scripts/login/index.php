<?php
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm

if ($method == 'POST') {
    extract(json_decode(file_get_contents('php://input'), true));
    if (!isset($email, $username, $password)) {
        http_response_code(400);
        echo json_encode(['message' => 'Not all required attributes are set']);
        return;
    }

    //$salt = Database::execute('select salt from player where id = :id', array(":id" => $user_id))[0]["SALT"];
    //PasswordManager::hashFromString($new_password, $salt);
    $player_data = Database::execute('SELECT * FROM player WHERE username = :username or email = :username and password = :password', array(':username' => $username, ':password' => $password));
    if ($player_data != null) {
        echo json_encode($player_data[0]);
    } else {
        echo json_encode(['message' => 'User Not Found.']);
    }

} elseif($method == 'GET') {
//	extract($_GET);
	if (!isset($username, $password)) {
		http_response_code(400);
		echo json_encode(array("message" => "(Username or email) and password must be given in the url"));
		return;
	}
	$player_data = Database::execute('SELECT ID, EMAIL, PASSWORD FROM player WHERE username = :username or email = :username and password = :password', array(':username' => $username, ':password' => $password))[0];

	if ($player_data === null) {
		http_response_code(404);
		echo json_encode(array("message" => "User not found"));
		return;
	}

    if (!password_verify($password, $player_data["PASSWORD"])) {
	    http_response_code(401);
	    echo json_encode(array("message" => "Wrong password for that user"));
		return;
    }

	if (isset($remember_login) && $remember_login === 1) {
		$login_token = sha1(mt_rand(10000, 99999) . time() . $player_data["EMAIL"]);
		Database::execute("UPDATE PLAYER SET LOGIN_TOKEN = :login_token WHERE ID = :id", array(
			":id" => $player_data["ID"],
			"login_token" => $login_token
		));
	}

	http_response_code(200);
	$json_response = array("message" => "Login successful");
	if (isset($login_token))
		$json_response["token"] = $login_token;

	json_encode($json_response);
}