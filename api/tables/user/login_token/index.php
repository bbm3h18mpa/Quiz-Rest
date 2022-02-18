<?php
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/MyDateTimeConverter.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm

if ($method != "GET") {
	http_response_code(400);
	echo json_encode(array("message" => "Only GET requests are allowed"));
	return;
}

$result = Database::execute("select LOGIN_TOKEN from player where email = :email", array(":email" => $_GET["email"]))[0];

if ($result == null) {
	http_response_code(200);
	echo json_encode(array("message" => "There is no login token set for this user"));
	return;
}