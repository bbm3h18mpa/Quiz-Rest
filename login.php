<?php
if(isset($_POST['sub'])){


}
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<h1>Login</h1>
	<form method="POST">
		Username: <br>
		<input type="text" name="username"> <br>
		Password: <br>
		<input type="password" name="password"> <br>
		<hr>
		<input type="submit" value="Submit" name="sub">
	</form>
</body>
</html>