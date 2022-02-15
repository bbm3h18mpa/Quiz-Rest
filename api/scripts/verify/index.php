<?php
require "{$_SERVER["DOCUMENT_ROOT"]}/api/classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/MyDateTimeConverter.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm

if ($method != "GET") {
	http_response_code(400);
	echo json_encode(array("message" => "Only GET Requests allowed"));
	return;
}

if (!isset($_GET["verification_id"])) {
	http_response_code(400);
	echo json_encode(array("message" => "Verification id not specified in url"));
	return;
}
$result = Database::execute("select \"USER\", CREATION_TIME from EMAIL_VERIFICATION where VERIFICATION_ID = :id", array(":id" => $_GET["verification_id"]));

if (sizeof($result) !== 1) {
	http_response_code(404);
	echo json_encode(array("message" => "Verification id not found"));
	return;
}
$result = $result[0];

$user_id = $result["USER"];
$creation_time = MyDateTimeConverter::createFromString($result["CREATION_TIME"]);

Database::execute('delete from EMAIL_VERIFICATION where "USER" = :user_id', array(":user_id" => $user_id));

$minutes_since_creation_time = abs((new DateTime())->diff($creation_time)->format("H")) / 60;
if ($minutes_since_creation_time > 60) {
	http_response_code(408);
	echo json_encode(array("message" => "Verification timed out"));
} else {
	Database::execute('update "USER" set VERIFIED = 1 where ID = 1');
	http_response_code(200);
	echo json_encode(array("message" => "Verification successful"));
}
