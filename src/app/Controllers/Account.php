<?php
require_once BASE_PATH . 'app/Core/Controller.php';
require_once BASE_PATH . 'app/Models/User.php';

class Account extends Controller {
	
	public function settings($params) {
		$this->checkCsrfToken();
		$userLogged = isset($_SESSION['logged_in']);

		if (!$userLogged) {
			header('Location: /account/login');
			exit();
		}

		$numParams = count($params);

		if ($_SERVER['REQUEST_METHOD'] == 'GET' && $numParams == 0) {
			$user = new User();

			$id = ['user_id' => $_SESSION['user_id']];
			$accountDetails = [
				'username',
				'email',
				'notifications_activated'
			];

			$result = $user->getFieldsById($accountDetails, $id);

			$content = BASE_PATH . 'app/Views/account/accountDetails.php';
			$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'edit_mode' => false,
				'username' => isset($result) && isset($result->username) ? $result->username : 'N/A',
				'email' => isset($result) && isset($result->email) ? $result->email : 'N/A',
				'notifications_activated' => isset($result) && isset($result->notifications_activated) ? $result->notifications_activated : false
			]);
		} else if ($numParams == 1 && $params[0] == 'edit') {
			$this->editAccountInformation($userLogged);
		} else {
			http_response_code(404);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_PAGE_NOT_FOUND,
				'error_code' => 404,
				'error_gif' => 'not-found.gif'
			]);
			return;
		}
	}

	public function login() {
		$this->checkCsrfToken();
		$userLogged = isset($_SESSION['logged_in']);
		$content = BASE_PATH . 'app/Views/account/login.php';

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$userLogged) {
			$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
			$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

			$user = new User();

			$result = $user->processLogin($username, $password);
			if (isset($result['user'])) {
				$_SESSION['user_id'] = $result['user']->user_id;
				$_SESSION['username'] = $result['user']->username;
				$_SESSION['logged_in'] = true;

				header('Location: /');
				die();
			} else {
				$this->view([
					'content' => $content,
					'userLogged' => $userLogged,
					'error' => $result['error']
				]);
			}	
		} else {
			if ($userLogged) {
				header('Location: /');
				exit();
			}

			$this->view([
				'content' => $content,
				'userLogged' => $userLogged
			]);
		}
	}
	
	public function signup() {
		$this->checkCsrfToken();
		$userLogged = isset($_SESSION['logged_in']);

		if ($userLogged) {
			header('Location: /');
			die();
		}
		
		$content = BASE_PATH . 'app/Views/account/signup.php';

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$userLogged) {
			$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
			$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
			$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

			$user = new User();

			$result = $user->processSignup($email, $username, $password);
			if (isset($result['user_id'])) {
				$this->sendVerificationEmail($email, $username, $result['activation_token']);
				header('Location: /account/verify?pending=true');
				die();
			} else {
				$this->view([
					'content' => $content,
					'userLogged' => $userLogged,
					'error' => $result['error']
				]);
			}
		} else {
			$this->view([
				'content' => $content,
				'userLogged' => $userLogged
			]);
		}
	}

	public function verify($params) {
		$userLogged = isset($_SESSION['logged_in']);
		$content = BASE_PATH . 'app/Views/account/verify.php';

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {
			if (isset($_GET['pending'])) {
				$this->view([
					'content' => $content,
					'userLogged' => $userLogged,
					'pending' => true
				]);
				return;
			}

			$email = $_GET['email'];
			$activationToken = $_GET['token'];

			$user = new User();

			$result = $user->processVerify($email, $activationToken);
			if (isset($result['user_id']) && !$userLogged) {
				$_SESSION['user_id'] = $result['user_id'];
				$_SESSION['username'] = $result['username'];
				$_SESSION['logged_in'] = true;

				$this->view([
					'content' => $content,
					'userLogged' => $userLogged,
					'success' => true,
					'scripts' => [
						['script' => 'redirectToMainPage.js', 'isModule'=> NOT_MODULE]
					]
				]);
			} else {
				http_response_code(400);
				$this->view([
					'content' => $content,
					'userLogged' => $userLogged,
					'error' => $result['error'],
					'success' => false
				]);
			}
		} else {
			header('Location: /');
			die();
		}
	}

	public function password($params) {
		$this->checkCsrfToken();
		$userLogged = isset($_SESSION['logged_in']);

		$numParams = count($params);

		if ($numParams == 0 || $numParams > 2 || ($numParams == 2 && $params[1] != 'forgotten')) {
			http_response_code(404);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error' => ERROR_PAGE_NOT_FOUND,
				'error_code' => 404,
				'error_gif' => 'not-found.gif'
			]);
			die();
		} else if (!$userLogged && $numParams == 2 && $params[1] == 'forgotten') {
			$this->requestResetForgottenPassword($userLogged);
		} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['email']) || isset($_GET['token']))) {
			$this->resetForgottenPasswordGet($userLogged);
		} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['current_password'])) {
			$this->resetForgottenPasswordPost($userLogged);
		} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && $userLogged && $params[0] == 'reset') {
			$this->resetPasswordFromSettingsGet($userLogged);
		} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $userLogged && isset($_POST['current_password'])) {
			$this->resetPasswordFromSettingsPost($userLogged);
		} else {
			header('Location: /');
			die();
		}
	}
	
	public function logout() {
		$userLogged = isset($_SESSION['logged_in']);
		if (!$userLogged) {
			header('Location: /');
			exit();
		}

		session_destroy();
		header('Location: /');
		die();
	}

	private function sendVerificationEmail($sendTo, $username, $activationToken) {
		$activationLink = APP_URL . "/account/verify?email=$sendTo&token=$activationToken";
		$subject = 'Camagru Account Verification';
		$message = <<<MESSAGE
				Hello $username,

				Please click the link below to activate your account:
				$activationLink

				Thank you,
				Camagru Team.
				MESSAGE;
		$header = 'From: "' . SENDER_NAME . '" <' . SENDER_EMAIL_ADDRESS . '>' . "\r\n";
		mail($sendTo, $subject, $message, $header);
	}

	private function requestResetForgottenPassword($userLogged) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');

			$user = new User();
			$result = $user->processRequestResetForgottenPassword($email);
			if (isset($result['reset_password_token'])) {
				$this->sendResetForgottenPasswordEmail($email, $result['reset_password_token']);
				$this->view([
					'content' => BASE_PATH . 'app/Views/account/forgotten.php',
					'userLogged' => false,
					'email_sent' => true
				]);
			} else {
				$this->view([
					'content' => BASE_PATH . 'app/Views/account/forgotten.php',
					'userLogged' => false,
					'error' => $result['error']
				]);
			}
		} else {
			$this->view([
				'content' => BASE_PATH . 'app/Views/account/forgotten.php',
				'userLogged' => $userLogged
			]);
		}
	}

	private function sendResetForgottenPasswordEmail($sendTo, $resetToken) {
		$resetLink = APP_URL . "/account/password/reset?email=$sendTo&token=$resetToken";
		$subject = 'Camagru Password Reset';
		$message = <<<MESSAGE
				Hello,

				Please click the link below to reset your password:
				$resetLink

				Thank you,
				Camagru Team.
				MESSAGE;
		$header = 'From: "' . SENDER_NAME . '" <' . SENDER_EMAIL_ADDRESS . '>' . "\r\n";
		mail($sendTo, $subject, $message, $header);
	}

	private function resetForgottenPasswordGet($userLogged) {

		if ($userLogged){
			header('Location: /');
			die();
		}

		$email = $_GET['email'];
		$resetToken = $_GET['token'];

		$user = new User();

		$result = $user->processResetForgottenPasswordGet($email, $resetToken);
		if (isset($result['valid'])) {
			$_SESSION['reset_password_token'] = $resetToken;
			$_SESSION['user_id'] = $result['user_id'];

			$content = BASE_PATH . 'app/Views/account/reset.php';

			$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'forgotten' => true,
				'scripts' => [
					['script' => 'checkConfirmationPassword.js', 'isModule'=> NOT_MODULE]
				]
			]);
		} else {
			http_response_code($result['error_code']);
			$this->view([
				'content' => BASE_PATH . 'app/Views/errors/error.php',
				'userLogged' => $userLogged,
				'error_gif' => $result['error_gif'],
				'error_code' => $result['error_code'],
				'error' => $result['error']
			]);
		}
	}

	private function resetForgottenPasswordPost($userLogged) {
		if ($userLogged){
			header('Location: /');
			die();
		}

		$user = new User();

		$content = BASE_PATH . 'app/Views/account/reset.php';

		$result = $user->resetPassword(
			['user_id' => $_SESSION['user_id']],
			htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8'),
			$_SESSION['reset_password_token']
		);
		if (isset($result['error'])) {
			if (isset($result['error_gif'])) {
				$this->view([
					'content' => BASE_PATH . 'app/Views/errors/error.php',
					'userLogged' => false,
					'error' => $result['error'],
					'error_code' => $result['error_code'],
					'error_gif' => $result['error_gif']
				]);
			} else {
				$this->view([
					'content' => $content,
					'userLogged' => false,
					'error' => $result['error'],
					'forgotten' => true,
					'success' => false,
					'scripts' => [
						['script' => 'checkConfirmationPassword.js', 'isModule'=> NOT_MODULE]
					]

				]);
			}
		} else {
			$_SESSION['reset_password_token'] = null;
			$_SESSION['user_id'] = $result['user_id'];
			$_SESSION['username'] = $result['username'];
			$_SESSION['logged_in'] = true;


			$this->view([
				'content' => $content,
				'userLogged' => $result['logged_in'],
				'success' => true,
				'scripts' => [
					['script' => 'redirectToMainPage.js', 'isModule'=> NOT_MODULE]
				]
			]);
		}
	}

	private function resetPasswordFromSettingsGet($userLogged) {
		$content = BASE_PATH . 'app/Views/account/reset.php';

		$this->view([
			'content' => $content,
			'userLogged' => $userLogged,
			'success' => false,
			'scripts' => [
				['script' => 'checkConfirmationPassword.js', 'isModule'=> NOT_MODULE]
			]
		]);
	}

	private function resetPasswordFromSettingsPost($userLogged) {
		$user = new User();

		$content = BASE_PATH . 'app/Views/account/reset.php';

		$result = $user->resetPassword(
			['user_id' => $_SESSION['user_id']],
			htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8'),
			null,
			htmlspecialchars($_POST['current_password'], ENT_QUOTES, 'UTF-8')
		);
		if (isset($result['error'])) {
			$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'error' => $result['error'],
				'success' => false,
				'scripts' => [
					['script' => 'checkConfirmationPassword.js', 'isModule'=> NOT_MODULE]
				]
			]);
		} else {
			$this->view([
				'content' => $content,
				'userLogged' => $result['logged_in'],
				'success' => true,
				'scripts' => [
					['script' => 'redirectToMainPage.js', 'isModule'=> NOT_MODULE]
				]
			]);
		}
	}

	private function editAccountInformation($userLogged) {
		if (!$userLogged) {
			header('Location: /');
			die();
		}

		$content = BASE_PATH . 'app/Views/account/accountDetails.php';

		if ($_SERVER['REQUEST_METHOD'] == 'GET') {

			$user = new User();

			$id = ['user_id' => $_SESSION['user_id']];
			$accountDetails = [
				'username',
				'email',
				'notifications_activated'
			];

			$result = $user->getFieldsById($accountDetails, $id);

			$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'edit_mode' => true,
				'username' => isset($result) && isset($result->username) ? $result->username : 'N/A',
				'email' => isset($result) && isset($result->email) ? $result->email : 'N/A',
				'notifications_activated' => isset($result) && isset($result->notifications_activated) ? $result->notifications_activated : false
			]);
		} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$newUsername = htmlspecialchars($_POST['new_username'], ENT_QUOTES, 'UTF-8');
			$newEmail = htmlspecialchars($_POST['new_email'], ENT_QUOTES, 'UTF-8');
			$notifications = isset($_POST['notifications_activated']) && $_POST['notifications_activated'] ? User::ACTIVATED : User::NOT_ACTIVATED;

			$user = new User();

			$id = [
				'user_id' => $_SESSION['user_id']
			];

			$result = $user->updateAccountDetails($id, $newUsername, $newEmail, $notifications);
			if (isset($result['error']) || isset($result[NOTHING_TO_UPDATE])) {
				$this->view([
					'content' => $content,
					'userLogged' => $userLogged,
					'edit_mode' => true,
					'username' => $result['username'],
					'email' => $result['email'],
					'notifications_activated' => $result['notifications_activated'],
					'error' => isset($result['error']) ? $result['error'] : null
				]);
			}

			$_SESSION['username'] = strlen($newUsername) > 0 ? $newUsername : $_SESSION['username'];

			$this->view([
				'content' => $content,
				'userLogged' => $userLogged,
				'success' => true,
				'scripts' => [
					['script' => 'redirectToMainPage.js', 'isModule'=> NOT_MODULE]
				]
			]);
		}
	}
}