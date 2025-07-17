<?php

require_once BASE_PATH . 'app/Core/Controller.php';
require_once BASE_PATH . 'app/Models/Image.php';
require_once BASE_PATH . 'app/Models/Comment.php';
require_once BASE_PATH . 'app/Models/Like.php';

class Media extends Controller {

	const CAMERA_FILTERS = [
		'shrek' => 'public/img/filters/shrek.png',
		'smiley' => 'public/img/filters/smiley.png',
		'wanted' => 'public/img/filters/wanted.png',
		'monster' => 'public/img/filters/monster.png',
		'devil-horns' => 'public/img/filters/devil-horns.png',
		'mars' => 'public/img/filters/mars.png',
		'ufo' => 'public/img/filters/ufo.png',
		'royal-frame' => 'public/img/filters/royal-frame.png',
	];
	
	public function gallery($params) {
		$userLogged = isset($_SESSION['logged_in']);

		$content = BASE_PATH . 'app/Views/media/gallery.php';
		
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			if (count($params) > 0) {
				return $this->getComment($userLogged, $params);
			}
			$this->handleGalleryGet($userLogged, $content);
		} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->handleGalleryPost($userLogged, $content);
		} else {
			return $this->methodNotAllowed();
		}
	}

	public function camera() {
		$userLogged = isset($_SESSION['logged_in']);

		if (!$userLogged) {
			header('Location: /account/login');
			exit();
		}

		$content = BASE_PATH . 'app/Views/media/camera.php';
		
		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->handleCameraGet($userLogged, $content);
		} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->handleCameraPost($userLogged, $content);
		} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
			$this->handleCameraDelete($userLogged, $content);
		} else {
			return $this->methodNotAllowed();
		}
		
	}

	private function handleCameraGet($userLogged, $content) {
		if (!$userLogged) {
			header('Location: /account/login');
			exit();
		}

		$image = new Image();
		$images = $image->getAllUserImages($_SESSION['user_id']);
		if ($images['status'] == NOT_SUCCESSFUL) {
			$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'error' => ERROR_DATABASE,
			]);
		}
		
		$this->view([
			'content' => $content,
			'userLogged' => $userLogged,
			'filters' => self::CAMERA_FILTERS,
			'images' => $images['result'],
			'scripts' => [
				['script' => 'handleCamera.js', 'isModule'=> NOT_MODULE],
				['script' => 'deleteImage.js', 'isModule'=> NOT_MODULE]
			]
		]);
	}

	private function handleCameraPost($userLogged, $content) {
		if (!$userLogged) {
			header('Location: /account/login');
			exit();
		}

		// Gets the data from the input:
		$postData = file_get_contents('php://input');
		$data = json_decode($postData, true);

		if (!array_key_exists('image', $data) || !array_key_exists('filters_ids', $data)
			|| !$this->validateFilters($data['filters_ids'])) {
			http_response_code(422);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_INVALID_USER_INPUT,
				'error_code' => 422,
				'error_gif' => 'media-error.gif',
			]);
			return;
		}

		if (array_key_exists('csrf_token', $data)) {
			$this->checkCsrfToken(htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'));
		} else {
			$this->checkCsrfToken('WRONG_TOKEN');
		}

		// Process Image:
		$result = $this->processAndSaveImage(
			htmlspecialchars($data['image'], ENT_QUOTES, 'UTF-8'),
			$data['filters_ids']
		);
		if ($result['status'] == NOT_SUCCESSFUL) {
			http_response_code($result['error_code']);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => $result['error'],
				'error_code' => $result['error_code'],
				'error_gif' => 'media-error.gif',
			]);
		}

		$image = new Image();
		$images = $image->getAllUserImages($_SESSION['user_id']);
		if ($images['status'] == NOT_SUCCESSFUL) {
			http_response_code($result['error_code']);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => $result['error'],
				'error_code' => $result['error_code'],
				'error_gif' => 'media-error.gif'
			]);
		}

		$this->view([
			'content' => $content,
			'userLogged' => $userLogged,
			'filters' => self::CAMERA_FILTERS,
			'images' => $images['result'],
			'scripts' => [
				['script' => 'handleCamera.js', 'isModule'=> NOT_MODULE],
				['script' => 'deleteImage.js', 'isModule'=> NOT_MODULE]
			],
		]);
	}

	private function handleCameraDelete($userLogged, $content) {
		if (!$userLogged) {
			header('Location: /account/login');
			exit();
		}

		$postData = file_get_contents('php://input');
		$data = json_decode($postData, true);

		if (!array_key_exists('image_id', $data)) {
			http_response_code(422);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_INVALID_USER_INPUT,
				'error_code' => 422,
				'error_gif' => 'media-error.gif',
			]);
			return;
		}

		if (array_key_exists('csrf_token', $data)) {
			$this->checkCsrfToken(htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'));
		} else {
			$this->checkCsrfToken('WRONG_TOKEN');
		}

		$imageObj = new Image();
		$commentObj = new Comment();
		$likeObj = new Like();

		$id = ['image_id' => htmlspecialchars($data['image_id'], ENT_QUOTES, 'UTF-8')];
		$commentObj->delete($id);
		$likeObj->delete($id);
		$imageResult = $imageObj->delete($id);
		
		$images = $imageObj->getAllUserImages($_SESSION['user_id']);
		if ($images['status'] == NOT_SUCCESSFUL || !$imageResult) {
			$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'error' => ERROR_DATABASE
			]);
		}
		
		$this->view([
			'content' => $content,
			'userLogged' => $userLogged,
			'filters' => self::CAMERA_FILTERS,
			'images' => $images['result'],
			'scripts' => [
				['script' => 'handleCamera.js', 'isModule'=> NOT_MODULE],
				['script' => 'deleteImage.js', 'isModule'=> NOT_MODULE]
			]
		]);
	}

	private function processAndSaveImage($imageData, $filters) {
		// Remove the 'data:image/png;base64,' part if it exists
		$imageData = str_replace('data:image/png;base64,', '', $imageData);
		// Replace possble spaces with '+' for it to be correctly decoded
		$imageData = str_replace(' ', '+', $imageData);

		// Decode the base64 data
		$decodedImageData = base64_decode($imageData);

		$dstImage = @imagecreatefromstring($decodedImageData);
		if ($dstImage === true) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => ERROR_WRONG_IMAGE_FORMAT,
				'error_code' => 415,
			];
		}

		foreach ($filters as $filter){
			$srcImage = imagecreatefrompng(BASE_PATH . self::CAMERA_FILTERS[$filter]);
			$srcImageSize = getimagesize(BASE_PATH . self::CAMERA_FILTERS[$filter]);
			$status = imagecopy(
				$dstImage,
				$srcImage,
				0, 0,
				0, 0,
				$srcImageSize[0], $srcImageSize[1]
			);
			if (!$status) {
				imagedestroy($dstImage);
				imagedestroy($srcImage);
				return [
					'status' => NOT_SUCCESSFUL,
					'error' => ERROR_PROCESSING_IMAGE,
					'error_code' => 415
				];
			}
			imagedestroy($srcImage);
		}


		$image = new Image();
		if (!$image->saveImage($dstImage)) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => ERROR_DATABASE,
				'error_code' => 503

			];
		}

		imagedestroy($dstImage);
		imagedestroy($srcImage);

		return [
			'status' => SUCCESSFUL
		];
	}

	private function handleGalleryGet($userLogged, $content, $currentPage=null) {
		$imageObj = new Image();
		$commentObj = new Comment();
		$likeObj = new Like();

		if ($currentPage === null) {
			$page = isset($_GET['page']) ? $_GET['page'] - 1 : 0;
		} else {
			$page = $currentPage - 1;
		}

		$imagesResult = $imageObj->getImagesPaginated($page);
		if (!isset($imagesResult['noImages'])) {
			$commentsResult = $imagesResult['status'] == SUCCESSFUL ? $commentObj->getAssociatedComments($imagesResult['result']) : null;
			if ($userLogged) {
				$likesResult = $imagesResult['status'] == SUCCESSFUL ? $likeObj->getAssociatedLikes($imagesResult['result']) : null;
			}
			if ($imagesResult['status'] == NOT_SUCCESSFUL && !isset($imagesResult['error'])) {
				http_response_code(503);
				$this->view([
					'content' => BASE_PATH . 'app/Views/errors/error.php',
					'userLogged' => $userLogged,
					'error' => ERROR_DATABASE,
					'error_code' => 503,
					'error_gif' => 'media-error.gif'
				]);
			} else if (isset($imagesResult['error']) && !isset($imagesResult['result'])) {
				http_response_code($imagesResult['error_code']);
				$this->view([
					'content' => BASE_PATH . 'app/Views/errors/error.php',
					'userLogged' => $userLogged,
					'error' => $imagesResult['error'],
					'error_code' => $imagesResult['error_code'],
					'error_gif' => $imagesResult['error_gif']
				]);
			} else {
				$images = $imagesResult['result'];
				$comments = $commentsResult['result'];
				$likedImages = isset($likesResult) ? $likesResult['result'] : null;
				$this->view([
					'content' => $content,
					'userLogged' => $userLogged,
					'currentPage' => $page + 1,
					'numPages' => $imagesResult['numPages'],
					'images' => $images,
					'comments' => $comments,
					'likes' => $likedImages,
					'scripts' => [
						['script' => 'postCommentOrLike.js', 'isModule'=> NOT_MODULE]
					]
				]);
			}
		} else {
			return $this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_NO_CONTENT,
				'error_code' => 204,
				'error_gif' => 'no-content.gif'
			]);
		}

	}

	private function handleGalleryPost($userLogged, $content) {
		// Gets the data from the input:
		$postData = file_get_contents('php://input');
		$data = json_decode($postData, true);

		if (!array_key_exists('image_id', $data) || (!array_key_exists('comment', $data) && !array_key_exists('like', $data))
			|| !array_key_exists('page', $data) || (array_key_exists('comment', $data) && strlen($data['comment']) > 280)) {
			http_response_code(422);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_INVALID_USER_INPUT,
				'error_code' => 422,
				'error_gif' => 'media-error.gif',
			]);
			return;
		}

		if (array_key_exists('csrf_token', $data)) {
			$this->checkCsrfToken(htmlspecialchars($data['csrf_token'], ENT_QUOTES, 'UTF-8'));
		} else {
			$this->checkCsrfToken('WRONG_TOKEN');
		}

		if (isset($data['comment'])) {
			$commentObj = new Comment();
			
			$commentData = [
				'user_id' => htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'),
				'image_id' => htmlspecialchars($data['image_id'], ENT_QUOTES, 'UTF-8'),
				'posted_by' => htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'),
				'comment' => htmlspecialchars($data['comment'], ENT_QUOTES, 'UTF-8')
			];

			$result = $commentObj->create($commentData);
			if ($result) {
				$commentId = $commentObj->db->lastInsertId();
				$imageObj = new Image();
				$user = $imageObj->getUserOfImage(['image_id' => htmlspecialchars($data['image_id'], ENT_QUOTES, 'UTF-8')]);
				if (!$user) {
					return $this->databaseError();
				}
				if ($user->notifications_activated) {
					$this->sendCommentNotificationEmail($commentId, $user);
				}
			}
		} else if (isset($data['like'])) {
			$likeObj = new Like();

			$likeData = [
				'user_id' => $_SESSION['user_id'],
				'image_id' => htmlspecialchars($data['image_id'], ENT_QUOTES, 'UTF-8'),
			];
			if ($data['like'] === 'yes') {
				$result = $likeObj->create($likeData);
			} else if ($data['like'] === 'no') {
				$result = $likeObj->deleteLike($likeData);
			}
		}
		if (!$result) {
			return $this->databaseError();
		}
		return $this->handleGalleryGet($userLogged, $content, htmlspecialchars($data['page'], ENT_QUOTES, 'UTF-8'));
	}

	private function getComment($userLogged, $params) {
		if (count($params) != 2 || $params[0] != 'comment' || !is_numeric($params[1])) {
			http_response_code(404);
			return $this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_PAGE_NOT_FOUND,
				'error_code' => 404,
				'error_gif' => 'not-found.gif',
				'params' => $params
			]);
		}

		$commentObj = new Comment();
		$imageObj = new Image();

		$commentId = ['comment_id' => $params[1]];
		$commentResult = $commentObj->getByReference($commentId);
		if ($commentResult === null) {
			http_response_code(503);
			return $this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_DATABASE,
				'error_code' => 503,
				'error_gif' => 'media-error.gif'
			]);
		}

		if (!isset($commentResult->comment)) {
			http_response_code(404);
			return $this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_PAGE_NOT_FOUND,
				'error_code' => 404,
				'error_gif' => 'not-found.gif',
				'params' => $params
			]);
		}

		$imageId = ['image_id' => $commentResult->image_id];
		$imageResult = $imageObj->getByReference($imageId);
		if ($imageResult === null) {
			http_response_code(503);
			return $this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_DATABASE,
				'error_code' => 503,
				'error_gif' => 'media-error.gif'
			]);
		}

		return $this->view([
			'content' => BASE_PATH . 'app/Views/media/comment.php',
			'userLogged' => $userLogged,
			'comment' => $commentResult->comment,
			'posted_by' => $commentResult->posted_by,
			'image' => $imageResult->image
		]);
	}

	private function sendCommentNotificationEmail($commentId, $user) {
		$sendTo = $user->email;
		$commentLink = APP_URL . "/media/gallery/comment/$commentId";
		$subject = 'Camagru Comment Notification';
		$message = <<<MESSAGE
				Hello $user->username,

				Somebody posted a comment on your picture, click on the link to check it 😉:
				$commentLink

				Thank you,
				Camagru Team.
				MESSAGE;
		$header = "From: " . SENDER_EMAIL_ADDRESS;
		mail($sendTo, $subject, $message, $header);
	}

	private function validateFilters($filters) {
		foreach ($filters as $filter) {
			if (in_array($filter, array_keys(self::CAMERA_FILTERS))) {
				return true;
			}
		}
		return false;
	}
}