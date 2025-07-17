<?php

require_once BASE_PATH . 'app/Core/Model.php';

class Like extends Model {

	const TABLE_NAME = 'likes';

	public function __construct() {
		parent::__construct();
		$this->table = self::TABLE_NAME;
	}

	public function getAssociatedLikes($images) {
		$imageIds = array_column($images, 'image_id');
		$userId = $_SESSION['user_id'];

		foreach ($imageIds as $imageId) {
			try {
				$stmt = $this->db->prepare("SELECT * FROM $this->table WHERE image_id = :image_id AND user_id = :user_id");
				$stmt->execute([
					'image_id' => $imageId,
					'user_id' => $userId
				]);
				$currentLike = $stmt->fetchAll();
				
				$likes[$imageId] = !empty($currentLike[0]) ? true : false;
			} catch (PDOException $e) {
				$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
				error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
				return ['status' => NOT_SUCCESSFUL];
			}
		}

		return [
			'status' => SUCCESSFUL,
			'result' => $likes
		];
	}

	public function deleteLike($ids) {
		try {

			$stmt = $this->db->prepare("DELETE FROM $this->table WHERE image_id = :image_id AND user_id = :user_id");
			$stmt->execute([
				'image_id' => $ids['image_id'],
				'user_id' => $ids['user_id']
			]);
			return true;
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return null;
		}
	}

}