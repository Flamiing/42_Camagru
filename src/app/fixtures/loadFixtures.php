<?php

require_once '/var/www/html/app/fixtures/dbConnect.php';  // Script to connect to the database

define('FIXTURES', [
	'users' => require_once 'data/users.php',
	// HINT: Add more fixtures here...
]);

function loadFixtures() {
	global $db;

	try {
		echo 'Loading fixtures...' . PHP_EOL;
		foreach (FIXTURES as $table => $records) {
			$db->exec("DELETE FROM $table"); // Delete all records from the table
			$db->exec("ALTER TABLE $table AUTO_INCREMENT = 1"); // Reset the auto-increment counter
			processFixtureRecords($table, $records);
		}
		echo "Fixtures loaded successfully!" . PHP_EOL;
	} catch (PDOException $e) {
		$errorMessage = $e->getMessage();
		$message = date(DATE_FORMAT) . " - Error: " . $errorMessage . PHP_EOL;
		error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
		echo 'Error cleaning the database: ' . $errorMessage;
		exit(1);
	}
}

function processFixtureRecords($table, $records) {
	echo 'Processing ' . $table . '...' . PHP_EOL;
	foreach ($records as $record => $fields) {
		insertFixtures($table, $fields);
	}
}

function insertFixtures($table, $fields) {
	global $db;

	try {
		echo 'Inserting fixtures into ' . $table . '...' . PHP_EOL;
		$sql = match ($table) {
			'users' => "INSERT INTO users (user_id, username, email, password, account_activated) VALUES (?, ?, ?, ?, ?)",
			// HINT: Add more SQL statements here...
		};
		$stmt = $db->prepare($sql);
		match ($table) {
			'users' => $stmt->execute([$fields['user_id'], $fields['username'], $fields['email'], $fields['password'], $fields['account_activated']]),
			// HINT: Add more execute statements here...
		};
	} catch (PDOException $e) {
		$errorMessage = $e->getMessage();
		$message = date(DATE_FORMAT) . " - Error: " . $errorMessage . PHP_EOL;
		error_log($message, 3, ERROR_LOGS);
		echo 'Error inserting fixtures into ' . $table . ': ' . $e->getMessage();
		exit(1);
	}
}

loadFixtures();