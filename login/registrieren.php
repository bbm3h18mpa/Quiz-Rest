<?php

if(isset($_POST['sub'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    
    require("../api/registrieren/registrieren.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren</title>
</head>
<body>
    <h1>Registrieren</h1>
    
    <form method="POST">
        Username: <br>
        <input type="text" name="username"> <br>
        Password: <br>
        <input type="password" name="password"> <br>
        Email: <br>
        <input type="email" name="email"> <br>
        <hr>
        <input type="submit" value="Submit" name="sub">
    </form>
</body>
</html>