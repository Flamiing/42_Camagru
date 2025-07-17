<?php

class App {

	protected $controller = '/';

	protected $method = '';

	protected $params = [];

	public function __construct() {
		
		// Parse the URL into an array
		$url = $this->parseURL();
		
		if (!empty($url)) {
			$urlLowercase = strtolower($url[0]);

			if ($urlLowercase == 'mainpage' || $urlLowercase == 'errorcontroller') {
				$url[0] = 'notFound';
			}
			$this->router($url);
		} else {
			require_once BASE_PATH . 'app/Controllers/MainPage.php';

			$mainPage = new MainPage;
			call_user_func([$mainPage, 'selectMainPage'], []);
		}
		
	}

	protected function router($url) {
		$url[0] = ucfirst($url[0]);

		// If a controller file exists for the first segment of the URL, set it as the current controller
		if (file_exists(BASE_PATH . 'app/Controllers/' . $url[0] . '.php')) {
			$this->controller = $url[0];
			unset($url[0]);
		} else {
			require_once BASE_PATH . 'app/Controllers/ErrorController.php';
			$this->controller = new ErrorController;
			call_user_func([$this->controller, 'notFound'], []);
			return;
		}
		
		// Require the controller file
		require_once BASE_PATH . 'app/Controllers/' . $this->controller . '.php';
		
		// Instantiate the controller class
		$this->controller = new $this->controller;
		
		// If a method exists in the controller for the second segment of the URL, set it as the current method
		if (isset($url[1])) {
			if (method_exists($this->controller, $url[1])) {
				$this->method = $url[1];
				unset($url[1]);
			}
		}

		// If no method was set, go to the main page
		if (empty($this->method)) {
            header('Location: /');
            exit();
		}
		
		// Set the remaining segments of the URL as the parameters
		$this->params = $url ? array_values($url) : [];
		
		// Call the current method of the current controller, passing the parameters
		call_user_func([$this->controller, $this->method], $this->params);
	}

	protected function parseURL() {
		if (isset($_GET['url'])) {
			$url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
			
			return array_map('strtolower', $url);
		}

		return null;
	}
}