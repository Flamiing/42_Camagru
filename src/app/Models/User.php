<?php

require_once BASE_PATH . 'app/Core/Model.php';
require_once BASE_PATH . 'app/Models/Validator.php';

class User extends Model {

	const TABLE_NAME = 'users';
	const ACTIVATED = 1;
	const NOT_ACTIVATED = 0;

	public function __construct() {
		parent::__construct();
		$this->table = self::TABLE_NAME;
	}

	public function processLogin($username, $password) {
		$validator = new Validator('users');

		$input = [
			'username' => $username,
			'password' => $password
		];
		$validationResult = $validator->validateUserInput($input, NOT_CHECK, NOT_CHECK);
		if ($validationResult['status'] == NOT_SUCCESSFUL) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => $validationResult['error'],
			];
		}

		$user = $this->getByReference([
			'username' => $username
		]);
		
		if ($user && password_verify($password, $user->password) &&
			strtolower($user->username) == strtolower($username)) {
			if ($user->account_activated == self::NOT_ACTIVATED) {
				return ['error' => ERROR_ACCOUNT_NOT_ACTIVE];
			}
			return ['user' => $user];
		} else {
			return ['error' => ERROR_INVALID_USER_PASS];
		}
	}

	public function processSignup($email, $username, $password) {
		$validator = new Validator('users');

		$input = [
			'username' => $username,
			'email' => $email,
			'password' => $password
		];
		$validationResult = $validator->validateUserInput($input, CHECK, CHECK);
		if ($validationResult['status'] == NOT_SUCCESSFUL) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => $validationResult['error'],
			];
		}

		$userId = $username . bin2hex(random_bytes(10));
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$activationToken = bin2hex(random_bytes(16));
		
		$user = [
			'user_id' => $userId,
			'username' => $username,
			'email' => $email,
			'password' => $hashedPassword,
			'activation_token' => $activationToken,
			'account_activated' => self::NOT_ACTIVATED
		];
		
		$userCreated = $this->create($user);
		
		if (!$userCreated) {
			return ['error' => ERROR_DATABASE];
		} else {
			return $user;
		}
	}

	public function processVerify($email, $activationToken) {
		$user = $this->getByReference([
			'email' => $email
		]);
		
		if ($user && $user->activation_token == $activationToken) {

			if ($user->account_activated == self::NOT_ACTIVATED) {
				$this->update([
					'user_id' => $user->user_id
				], [
					'account_activated' => self::ACTIVATED
				]);
				return [
					'user_id' => $user->user_id,
					'username' => $user->username
				];
			} else {
				return ['error' => ERROR_ACCOUNT_ACTIVE];
			}
		} else {
			return ['error' => ERROR_INVALID_ACTIVATION_LINK];
		}
	}

	public function processRequestResetForgottenPassword($email) {
		$validator = new Validator('users');

		$input = [
			'email' => $email
		];
		$validationResult = $validator->validateUserInput($input, NOT_CHECK, NOT_CHECK);
		if ($validationResult['status'] == NOT_SUCCESSFUL) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => $validationResult['error'],
			];
		}

		$user = $this->getByReference([
			'email' => $email
		]);
		
		if ($user) {

			if ($user->account_activated == self::NOT_ACTIVATED) {
				return ['error' => ERROR_VALIDATE_ACC_TO_RESET];
			}

			$id = [
				'user_id' => $user->user_id
			];
			
			$resetToken = $this->createResetPasswordToken($id);

			if (!$resetToken) {
				return ['error' => ERROR_DATABASE];
			} else {
				return [
					'valid' => true,
					'reset_password_token' => $resetToken
				];
			}
		} else {
			return ['error' => ERROR_ACC_NOT_FOUND];
		}
	}

	public function processResetForgottenPasswordGet($email, $resetToken) {
		$user = $this->getByReference([
			'email' => $email
		]);

		if ($user) {
			if ($user->account_activated == self::NOT_ACTIVATED) {
				return ['error' => ERROR_VALIDATE_ACC_TO_RESET];
			}

			if ($user->reset_password_token == $resetToken) {
				$expiry = strtotime($user->reset_password_expiry);
				$now = strtotime(date(DATE_FORMAT));

				if ($now > $expiry) {
					return [
						'error' => ERROR_RESET_TOKEN_EXPIRED,
						'error_code' => 403,
						'error_gif' => 'expired-token.gif'
					];
				} else {
					return [
						'valid' => true,
						'user_id' => $user->user_id
					];
				}
			} else {
				return [
					'error' => ERROR_INVALID_RESET_TOKEN,
					'error_code' => 403,
					'error_gif' => 'invalid-token.gif'
				];
			}
		} else {
			return [
				'error' => ERROR_ACC_NOT_FOUND,
				'error_code' => 403,
				'error_gif' => 'account-not-found.gif'
			];
		}
	}

	private function createResetPasswordToken($id) {
		$expiry = date(DATE_FORMAT, strtotime('+1 hour'));

		$data = [
			'reset_password_token' => bin2hex(random_bytes(16)),
			'reset_password_expiry' => $expiry
		];;

		if (!$this->update($id, $data)) {
			return null;
		} else {
			return $data['reset_password_token'];
		}
	}

	public function resetPassword($id, $newPassword, $resetToken = null, $currentPassword = null) {
		$validator = new Validator('users');

		$input = ['password' => $newPassword];
		$validationResult = $validator->validateUserInput($input, NOT_CHECK, NOT_CHECK, $id);
		if ($validationResult['status'] == NOT_SUCCESSFUL) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => $validationResult['error'],
			];
		}


		$user = $this->getByReference($id);

		if ($user) {
			if (isset($resetToken) && !isset($currentPassword)) {
				if ($user->reset_password_token != $resetToken) {
					return [
						'error' => ERROR_INVALID_RESET_TOKEN,
						'error_code' => 403,
						'error_gif' => 'invalid-token.gif'
					];
				}
	
				$expiry = strtotime($user->reset_password_expiry);
				$now = strtotime(date(DATE_FORMAT));
	
				if ($now > $expiry) {
					return [
						'error' => ERROR_RESET_TOKEN_EXPIRED,
						'error_code' => 403,
						'error_gif' => 'expired-token.gif'
					];
				}
			}

			if (isset($currentPassword)
				&& !password_verify($currentPassword, $user->password)) {
				return [
					'error' => ERROR_INVALID_CURRENT_PASS,
					'error_code' => 401,
				];
			}

			$data = [
				'password' => password_hash($newPassword, PASSWORD_DEFAULT),
				'reset_password_token' => null,
				'reset_password_expiry' => null
			];

			$result = $this->update($id, $data);
			if (!$result) {
				return ['error' => ERROR_DATABASE];
			}
			return [
				'success_msg' => 'Password reset successfully!',
				'user_id' => $user->user_id,
				'username' => $user->username,
				'logged_in' => true
			];
		} else {
			return [
				'error' => ERROR_ACC_NOT_FOUND,
				'error_code' => 403,
				'error_gif' => 'account-not-found.gif'
			];
		}		
	}

	public function updateAccountDetails($id, $newUsername, $newEmail, $notifications) {
		if (isset($newUsername) && !empty($newUsername)) {
			$data['username'] = $newUsername;
		}
		if (isset($newEmail) && !empty($newEmail)) {
			$data['email'] = $newEmail;
		}
		if (isset($notifications) || $notifications === self::NOT_ACTIVATED) {
			$data['notifications_activated'] = $notifications;
		}

		$validator = new Validator('users');

		$validationResult = $validator->validateUserInput($data, CHECK, CHECK);
		if ($validationResult['status'] == NOT_SUCCESSFUL) {
			$accountDetails = [
				'username',
				'email',
				'notifications_activated'
			];

			$result = $this->getFieldsById($accountDetails, $id);
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => $validationResult['error'],
				'username' => isset($result) && isset($result->username) ? $result->username : 'N/A',
				'email' => isset($result) && isset($result->email) ? $result->email : 'N/A',
				'notifications_activated' => isset($result) && isset($result->notifications_activated) ? $result->notifications_activated : false
			];
		}

		$currentDetails = $this->checkIfAccountUpdateNeeded($id, $data);
		if ($currentDetails['status'] == NOTHING_TO_UPDATE) {
			return $currentDetails['accountDetails'];
		}

		$result = $this->update($id, $data);
		if (!$result) {
			return [
				'error' => ERROR_DATABASE,
				'username' => 'N/A',
				'email' => 'N/A',
				'notifications_activated' => SELF::NOT_ACTIVATED,
			];
		}

		return [
			'username' => $newUsername
		];
	}

	private function checkIfAccountUpdateNeeded($id, $data) {
		$fieldsToGet = [
			'username',
			'email',
			'notifications_activated'
		];

		$accountDetails = $this->getFieldsById($fieldsToGet, $id);

		if ($accountDetails->notifications_activated == $data['notifications_activated']
			&& empty($data['username']) && empty($data['email'])) {
			return [
				'status' => NOTHING_TO_UPDATE,
				'accountDetails' => [
					NOTHING_TO_UPDATE => NOTHING_TO_UPDATE,
					'username'=> $accountDetails->username,
					'email'=> $accountDetails->email,
					'notifications_activated'=> $accountDetails->notifications_activated
				]
			];
		} else {
			return ['status' => 'NEED_UPDATE'];
		}
	}
}
