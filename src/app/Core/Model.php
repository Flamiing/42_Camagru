<?php

class Model {

	public $db;
	protected $table;

	const ASCENDING = 'ASC';
    const DESCENDING = 'DESC';

	public function __construct() {

		// Gets the password from the environment variable:
		$rootPassword = getenv('MYSQL_ROOT_PASSWORD');
		$dbName = getenv('MYSQL_DATABASE');

		// Connects to the database:
		try {
			$this->db = new PDO("mysql:host=mysql;dbname=$dbName", 'root', $rootPassword); // Connect to the database
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode
			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);  // Set default fetch mode
		
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
		}
	}

	public function create($data) {
		try {
			$fields = implode(', ', array_keys($data));
			$placeholders = implode(', ', array_fill(0, count($data), '?'));
			$stmt = $this->db->prepare("INSERT INTO $this->table ($fields) VALUES ($placeholders)");
			$stmt->execute(array_values($data));
			return true;
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return null;
		}
	}
	
	public function delete($id) {
		try {
			$recordId = array_keys($id)[0];
			$stmt = $this->db->prepare("DELETE FROM $this->table WHERE $recordId = :id");
			$stmt->execute(['id' => array_values($id)[0]]);
			return true;
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return null;
		}
	}

	public function update($id, $data) {
		try {
			$recordId = array_keys($id)[0];
			$fields = array_keys($data);
			$fieldsToUpdate = implode(', ', array_map(function($field) { return "$field = ?"; }, $fields));
			$stmt = $this->db->prepare("UPDATE $this->table SET $fieldsToUpdate WHERE $recordId = ?");
			$data['id'] = array_values($id)[0];
			$stmt->execute(array_values($data));
			return true;
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return null;
		}
	}

	public function getByReference($reference) {
		try {
			$recordReference = array_keys($reference)[0];
			$stmt = $this->db->prepare("SELECT * FROM $this->table WHERE $recordReference = :reference");
			$stmt->execute(['reference' => array_values($reference)[0]]);
			$value = $stmt->fetch();

			return $value;
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return null;
		}
	}

	public function getFieldsById($fieldsToGet, $id) {
		try {
			$recordId = array_keys($id)[0];
			$fields = implode(', ', $fieldsToGet);
			$stmt = $this->db->prepare("SELECT $fields FROM $this->table WHERE $recordId = :id");
			$stmt->execute(['id' => array_values($id)[0]]);
			$value = $stmt->fetch();

			return $value;
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return null;
		}
	}

	public function getAllOrderedByDate($id, $order = self::DESCENDING) {
		try {
			$stmt = $this->db->prepare("SELECT * FROM $this->table WHERE user_id = :user_id ORDER BY date_of_creation $order");
			$stmt->execute(['user_id' => $id]);
			$value = $stmt->fetchAll();

			return [
				'status' => SUCCESSFUL,
				'result' => $value
			];
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return ['status' => NOT_SUCCESSFUL];
		}
	}
}