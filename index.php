<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");

$method = $_SERVER["REQUEST_METHOD"];

$request_uri = $_SERVER["REQUEST_URI"];

$apiPaths = ["posts"];
$url = rtrim($request_uri, "/");
$url = filter_var($request_uri, FILTER_SANITIZE_URL);
$url = explode("/", $url);

$path = (string) $url[3];

if ($url[4] != null) {
	$id = (int) $url[4];
} else {
	$id = null;
}

if (in_array($path, $apiPaths)) {
	include_once "./classes/Database.php";
	include_once "./api/posts.php";
} else {
	echo json_encode(["message" => "Table does not exists"]);
}