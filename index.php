<?php
if (!isset($_POST["session_key"])) {
	header("Location: login.php");
	return;
}
