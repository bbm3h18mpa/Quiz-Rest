<?php
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/PasswordManager.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm


if ($method == 'POST') {
	$user_id = $_GET["user_id"];
	if (!isset($user_id)) {
		http_response_code(400);
		echo json_encode(['message' => '"user_id" not set']);
		return;
	}

	$verification_id = base64_encode(random_bytes(16));
	Database::execute("insert into CHANGE_PASSWORD_VERIFICATION values(
        :user_id,
        :verification_id,
        :creation_time
    )", array(
		":verification_id" => $verification_id,
		"creation_time" => MyDateTimeConverter::toString(new DateTime())
	));
	http_response_code(200);
	json_encode(array("message" => "Successful"));

} elseif ($method == 'GET') {
	extract($_GET);

	if (!isset($new_password) || !isset($user_id)) {
		http_response_code(400);
		echo json_encode(['message' => '"new_password" or/and "user_id" not found in url']);
		return;
	}

	$result = Database::execute("select player, CREATION_TIME from CHANGE_PASSWORD_VERIFICATION where VERIFICATION_ID = :id", array(":id" => $_GET["verification_id"]));

	if (sizeof($result) !== 1) {
		http_response_code(404);
		echo json_encode(array("message" => "Verification id not found"));
		return;
	}
	$result = $result[0];
	$user_id = $result["USER"];

	Database::execute('delete from CHANGE_PASSWORD_VERIFICATION where player = :user_id', array(":user_id" => $user_id));

	$minutes_since_creation_time = abs((new DateTime())->diff($creation_time)->format("H")) / 60;
	if ($minutes_since_creation_time > 60) {
		http_response_code(408);
		echo json_encode(array("message" => "Verification timed out"));
		return;
	}

	$salt = Database::execute('select salt from player where id = :id', array(":id" => $user_id))[0]["SALT"];

	$new_password = PasswordManager::hashFromString($new_password, $salt);
	Database::execute('update player set password = :password where id = :id', array(':password' => $new_Password, ':id' => $user_id));

	http_response_code(201);
	echo json_encode(array('message' => 'successful'));
}