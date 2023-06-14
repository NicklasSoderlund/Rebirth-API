<?php

require_once 'config.php';

function connect($host, $db, $user, $password)
{
	$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

	try {
		$options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false];

		return new PDO($dsn, $user, $password, $options);
	}
	 catch (PDOException $e) {
		die($e->getMessage());
	}
}

return connect($host, $db, $user, $password);