<?php

class Controller {
	protected function model($model) {
		require_once BASE_PATH . 'app/Models/' . $model . '.php';
		return new $model();
	}

	public function view($data = []) {
		require_once BASE_PATH . 'app/Views/shared/base.php';
	}

	protected function checkCsrfToken($csrfToken = null) {
		$userLogged = isset($_SESSION['logged_in']);

		if ($csrfToken === null) {
			$csrfToken = isset($_POST['csrf_token']) ? htmlspecialchars($_POST['csrf_token'], ENT_QUOTES, 'UTF-8') : null;
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'DELETE') {
			if (!isset($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
				header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
				$this->view([
					'content' => BASE_PATH . 'app/Views/errors/error.php',
					'userLogged' => $userLogged,
					'error' => ERROR_CSRF_TOKEN,
					'error_code' => 403,
					'error_gif' => 'forbidden.gif'
				]);
				exit();
			}
		}
	}

	protected function methodNotAllowed() {
		$userLogged = isset($_SESSION['logged_in']);

		http_response_code(405);
		return $this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_METHOD_NOT_ALLOWED,
				'error_code' => 405,
				'error_gif' => 'method-not-allowed.gif',
			]);
	}

	protected function databaseError() {
		$userLogged = isset($_SESSION['logged_in']);

		http_response_code(503);
		return $this->view([
			'content' => BASE_PATH . 'app/Views/errors/error.php',
			'userLogged' => $userLogged,
			'error' => ERROR_DATABASE,
			'error_code' => 503,
			'error_gif' => 'media-error.gif'
		]);
	}
}