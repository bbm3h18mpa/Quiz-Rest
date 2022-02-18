<?php
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/Database.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/classes/MyDateTimeConverter.php";
require "${_SERVER["DOCUMENT_ROOT"]}/api/rest_init.php";
/** @var string $method */ //for better code highlighting in PhpStorm

if ($method == "POST") {
	extract(json_decode(file_get_contents('php://input'), true));
	if (!isset($email, $username, $password)) {
		http_response_code(400);
		echo json_encode(['message' => 'Not all required attributes are set']);
		return;
	}
    $explodeArray = explode("@", $email);
    $domain = $explodeArray[1];
    $institution = Database::execute("select ID from INSTITUTION WHERE DOMAIN = :domain", array(":domain" => $domain))[0];
    if ($institution === null) {
        http_response_code(404);
        echo json_encode(array("message" => "No institution found for that domain"));
        return;
    }
	$institution = $institution["ID"];

    Database::execute('insert into player (
        email, username, password, institution
	) values(
        :email,
        :username,
        :password,
        :institution
    )', array(
		':email' => $email,
		':username' => $username,
		':password' => password_hash($password, PASSWORD_DEFAULT),
		':institution' => $institution
	));

	$player = Database::execute('select * from player where email = :email', array(":email" => $email))[0];

	$verification_id = sha1(mt_rand(10000, 99999) . time() . $email);
	Database::execute("insert into EMAIL_VERIFICATION values (
        :id,
        :verification_id,
        CURRENT_TIMESTAMP
    )", array(
		":id" => $player['ID'],
		":verification_id" => $verification_id
	));

	//mail not working for now
    $to = $email;
    $subject = "Verifying your registration as $username for Quiz-App ";
    $message = "Please Click <html><a href=https://quiz.florianten.de/verify.php?verification_id=$verification_id>here</a></html> to verify your registration  ";
    $headers = "From: NoReplyQuizzApp@gmail.com" . "\r\n" .
        "Reply-To: $email " . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    mail($to, $subject, $message, $headers);

    http_response_code(201);
    echo json_encode([
		'message' => 'User registrierung erfolgreich.',
	    "verification_id" => $verification_id
    ]);
}