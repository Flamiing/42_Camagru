<?php

require_once BASE_PATH . 'app/Core/Controller.php';

class MainPage extends Controller {
	
	public function selectMainPage() {
		$userLogged = isset($_SESSION['logged_in']);

		if ($userLogged) {
			header('Location: /media/gallery');
		} else {
			header('Location: /account/login');
		}
		
	}
}