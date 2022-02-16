<?php
require "../../classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/MyDateTimeConverter.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm


if($method == "POST"){
    if ($_POST != null) {
        extract($_POST);
        $explodeArray = explode("@", $email);
        $domain = $explodeArray[1];
        $institution = Database::execute("select ID from INSTITUTION WHERE DOMAIN = :domain", [":domain" => $domain])[0];
        if($institution === null){
            http_response_code(404);
            echo json_encode(array("message" => "No institution found for that domain"));
            return;
        }
        $salt = base64_encode(random_bytes(16));
        Database::execute('insert into "USER" values(
            null,
            :email,
            :username,
            :password,
            :salt,
            :session_key,
            :session_creation_time,
            :instiution,
            :verified
        )', array(
			':email' => $email,
			':username' => $username,
			':password' => $password,
			':salt' => $salt,
			':session_key' => null,
			':session_creation_time' => null,
			':institution' => $institution,
			':verified' => 0
		));
		mail();
        $data = Database::execute('select * from "USER" order by id desc fetch first 1 rows only')[0];
	    Database::execute("insert into EMAIL_VERIFICATION values (
        	${date['id']},
        	{base64_encode(random_bytes(16))},
        	{MyDateTimeConverter::toString(new DateTime())}
    	)");
        http_response_code(201);
        echo json_encode(['message' => 'User registrierung erfolgreich.', 'success' => true, 'post' => $data[0]]);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'User registrierung nicht erfolgreich', 'success' => false]);
    }
}