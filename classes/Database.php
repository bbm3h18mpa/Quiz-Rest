<?php

class Database {
	private static function connect() {
		$pdo = new PDO("oci:dbname=quizzapp_high", "quizzteam7", "QuizzApp0284");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $pdo;
	}

	public static function execute($sql, $params = array()) {
		$statement = self::connect()->prepare($sql);
		$statement->execute($params);
		if (strcasecmp(explode(' ', $sql)[0], "select"))
			return $statement->fetchAll();
		return true;
	}
	
	
}