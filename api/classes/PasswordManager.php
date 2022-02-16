<?php

class PasswordManager {

	private static string $pepper = "0123456789abcdef";

	static function hashFromString(string $string, string $salt) {
		return self::myHash(self::myHash($salt . $string));
	}

	private static function myHash(string $string) {
		return hash("SHA-512", $string);
	}
}