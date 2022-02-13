<?php
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm

if ($method == "GET") {
	try {
		echo json_encode(array(
			"message" => "Request successful",
			"value" => base64_encode(random_bytes(16)),
			"error" => null
		));
		http_response_code(200);
	} catch (Exception $e) {
		echo json_encode(array(
			"message" => "Could not provide random salt",
			"value" => null,
			"error" => $e->getMessage()
		));
		http_response_code(404);
	}
} else {
	echo json_encode(array("message" => "Invalid request method: \"$method\". Only \"GET\" is allowed."));
}