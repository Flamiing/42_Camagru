<?php

require_once BASE_PATH . 'app/Core/Model.php';

class Comment extends Model {

	const TABLE_NAME = 'comments';

	public function __construct() {
		parent::__construct();
		$this->table = self::TABLE_NAME;
	}

	public function getAssociatedComments($images) {
		$imageIds = array_column($images, 'image_id');

		foreach ($imageIds as $imageId) {
			try {
				$stmt = $this->db->prepare("SELECT * FROM $this->table WHERE image_id = :image_id");
				$stmt->execute(['image_id' => $imageId]);
				$currentComments = $stmt->fetchAll();

				$comments[$imageId] = $currentComments;
			} catch (PDOException $e) {
				$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
				error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
				return ['status' => NOT_SUCCESSFUL];
			}
		}

		return [
			'status' => SUCCESSFUL,
			'result' => $comments
		];
	}

}