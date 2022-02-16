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
        Database::execute('insert into "USER" (
            email, username, password, salt, session_key, session_creation_time, institution, verified
		) values(
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

        $data = Database::execute('select * from "USER" where EMAIL = :email', array(":email" => $email))[0];
	    Database::execute("insert into EMAIL_VERIFICATION values (
        	${data['id']},
            :verification_id,
   			:creation_time
    	)", array(
			":verification_id" => base64_encode(random_bytes(16)),
		    "creation_time" => MyDateTimeConverter::toString(new DateTime())
	    ));

        $to      = $email;
        $subject = "Verifying your registration as $username for Quiz-App ";
        $message = 'Please Click "<a href=verify.php>here</a>" to verify your registration  ';
        $headers = "From: NoReplyQuizzApp@quizapp.com" . "\r\n" .
            "Reply-To: $email ". "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        mail($to, $subject, $message, $headers);

        http_response_code(201);
        echo json_encode(['message' => 'User registrierung erfolgreich.', 'success' => true, 'post' => $data[0]]);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'User registrierung nicht erfolgreich', 'success' => false]);
    }
}