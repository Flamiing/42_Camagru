<?php

require_once BASE_PATH . 'app/Core/Controller.php';

class ErrorController extends Controller {
	
	public function notFound() {
		$userLogged = isset($_SESSION['logged_in']);

		$content = BASE_PATH . 'app/Views/errors/error.php';
		http_response_code(404);
		$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'error' => ERROR_PAGE_NOT_FOUND,
				'error_code' => 404,
				'error_gif' => 'not-found.gif'
			]);
	}

	public function serverError() {
		$userLogged = isset($_SESSION['logged_in']);

		$content = BASE_PATH . 'app/Views/errors/error.php';
		http_response_code(500);
		$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'error' => ERROR_INTERNAL_SERVER,
				'error_code' => 500,
				'error_gif' => 'cat-falling.gif'
			]);
	}
}