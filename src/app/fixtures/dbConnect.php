<?php

define('ERROR_LOGS', '/var/www/html/logs/error.log');
define('DATE_FORMAT', 'Y-m-d H:i:s');

$rootPassword = getenv('MYSQL_ROOT_PASSWORD'); // Get root password from environment variable

try {
	echo 'Connecting to the database...' . PHP_EOL;
	$db = new PDO('mysql:host=mysql;dbname=camagru', 'root', $rootPassword); // Connect to database
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);  // Set default fetch mode
	echo 'Connected to the database successfully!' . PHP_EOL;

} catch (PDOException $e) {
	$errorMessage = $e->getMessage();
	$message = date(DATE_FORMAT) . " - Error: " . $errorMessage . PHP_EOL;
	error_log($message, 3, ERROR_LOGS);
	echo 'Error connecting to the database: ' . $e->getMessage();
	exit(1);
}