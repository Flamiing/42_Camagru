<?php

require_once BASE_PATH . 'app/Core/Model.php';
require_once BASE_PATH . 'app/Models/User.php';

class Image extends Model {

	const TABLE_NAME = 'images';
	const NO_IMAGES = -2;

	public function __construct() {
		parent::__construct();
		$this->table = self::TABLE_NAME;
	}

	public function getAllUserImages() {
		$images = $this->getAllOrderedByDate($_SESSION['user_id']);

		return $images;
	}

	public function saveImage($image) {
		ob_start();
		imagepng($image);
		$imageData = ob_get_clean();

		$thumbnailData = $this->createThumbnail($image);

		$data = [
			'user_id' => $_SESSION['user_id'],
			'upload_by' => $_SESSION['username'],
			'image' => $imageData,
			'thumbnail' => $thumbnailData
		];
		
		if (!$this->create($data)) {
			return false;
		}

		return true;
	}

	public function getUserOfImage($id) {
		$image = $this->getByReference($id);
		if (!$image) {
			return null;
		}

		$userObj = new User();
		$fieldsToGet = [
			'username',
			'email',
			'notifications_activated'
		];
		$user = $userObj->getFieldsById($fieldsToGet, ['user_id' => $image->user_id]);
		if (!$user) {
			return null;
		} else {
			return $user;
		}
	}

	private function createThumbnail($originalImage) {
		// Load the image
		$originalWidth = imagesx($originalImage);
		$originalHeight = imagesy($originalImage);

		$thumbnailWidth = intval($originalWidth / 5); // Width of the thumbnail
		$thumbnailHeight = intval($originalHeight / 5); // Height of the thumbnail

		// Create a new true color image with the specified dimensions
		$thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

		// Copy and resize the image
		imagecopyresampled($thumbnail, $originalImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

		ob_start();
		imagepng($thumbnail);
		$thumbnailData = ob_get_clean();

		return $thumbnailData;

	}

	public function getImagesPaginated($page) {
		$imagesPerPage = 5;
		
		$start = $page === 0 ? 0 : $page * $imagesPerPage;
		$numPages = $this->getNumberOfPages($imagesPerPage);
		if ($numPages === -1) {
			return ['status' => NOT_SUCCESSFUL];
		} else if ($numPages === self::NO_IMAGES) {
			return [
				'status' => SUCCESSFUL,
				'noImages' => true
			];
		} else if ($page < 0 || ($page + 1) > $numPages) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => ERROR_PAGE_NOT_FOUND,
				'error_code' => 404,
				'error_gif' => 'not-found.gif',
			];
		}

		try {
			$stmt = $this->db->prepare(
				"SELECT * FROM $this->table ORDER BY date_of_creation DESC LIMIT $start, $imagesPerPage"
			);
			$stmt->execute();
			$value = $stmt->fetchAll();

			return [
				'status' => SUCCESSFUL,
				'result' => $value,
				'numPages' => $numPages
			];
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return ['status' => NOT_SUCCESSFUL];
		}
	}

	private function getNumberOfPages($imagesPerPage) {
		try {
			$stmt = $this->db->prepare("SELECT * FROM $this->table");
			$stmt->execute();
			$rowCount = $stmt->rowCount();

			$numPages = ceil($rowCount / $imagesPerPage);

			if ($numPages == 0) {
				return -2;
			}
			return $numPages;
		} catch (PDOException $e) {
			$message = date(DATE_FORMAT) . " - Error: " . $e->getMessage() . PHP_EOL;
			error_log($message, 3, ERROR_LOGS); // Logs the error message to the error log file
			return -1;
		}
	}
}