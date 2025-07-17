<?php

require_once BASE_PATH . 'app/Core/Model.php';

class Validator extends Model {

	const MATCHING = 1;
	const NOT_MATCHING = 0;

	public function __construct($table) {
		parent::__construct();
		$this->table = $table;
	}

	public function validateUserInput($input, $checkUsername, $checkEmail, $id = null) {

		if (isset($input['username'])) {
			$results['username'] = $this->isValidUsername($input['username'], $checkUsername);
		}

		if (isset($input['email'])) {
			$results['email'] = $this->isValidEmail($input['email'], $checkEmail);
		}

		if (isset($input['password'])) {
			$results['password'] = $this->isValidPassword($input['password'], $id);
		}
		
		if (isset($input['notifications_activated'])) {
			$results['notifications_activated'] = $this->isValidNotifications($input['notifications_activated']);
		}
		
		foreach ($results as $result) {
			if ($result['status'] == NOT_SUCCESSFUL) {
				return $result;
			}
		}
		
		return [
			'status' => SUCCESSFUL
		];
	}
	
	protected function isValidUsername($username, $checkIfExist = true) {
		$pattern = '/^[A-Za-z0-9_.]*$/';
		$usernameLen = strlen($username);
		if (empty($username) || preg_match($pattern, $username) === self::NOT_MATCHING
		|| $usernameLen < 6 || $usernameLen > 30) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => ERROR_INVALID_USERNAME
			];
		}
		
		$reference = [
			'username' => $username
		];
		
		if ($checkIfExist) {
			$userFound = $this->getByReference($reference);

			if (!empty($userFound)) {
				return [
					'status' => NOT_SUCCESSFUL,
					'error' => ERROR_USERNAME_IN_USE
				];
			}
		}
		return ['status' => SUCCESSFUL];
	}

	protected function isValidEmail($email, $checkIfExists) {
		$pattern = '/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';
		$emailLen = strlen($email);
		if (empty($email) || preg_match($pattern, $email) === self::NOT_MATCHING
			|| $emailLen < 6 || $emailLen > 50) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => ERROR_INVALID_EMAIL
			];
		}

		$reference = [
			'email' => $email
		];

		if ($checkIfExists) {
			$userFound = $this->getByReference($reference);

			if (!empty($userFound)) {
				return [
					'status' => NOT_SUCCESSFUL,
					'error' => ERROR_EMAIL_IN_USE
				];
			}
		}
		return ['status' => SUCCESSFUL];
	}

	protected function isValidPassword($password, $id = null) {
		$pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[+.\-_*$@!?%&])(?=.*\d)[A-Za-z\d+.\-_*$@!?%&]+$/';
		$passwordLen = strlen($password);
		if (empty($password) || preg_match($pattern, $password) === self::NOT_MATCHING
		|| $passwordLen < 8 || $passwordLen > 16) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => ERROR_INVALID_PASSWORD
			];
		} 
		
		if (isset($id)) {
			$fieldsToGet = ['password'];

			$user = $this->getFieldsById($fieldsToGet, $id);
			if (password_verify($password, $user->password)) {
				return [
					'status' => NOT_SUCCESSFUL,
					'error' => ERROR_SAME_PASS_AS_CURRENT
				];
			}
		}

		return [
			'status' => SUCCESSFUL
		];
	}

	protected function isValidNotifications($notifications) {
		if ($notifications !== 1 && $notifications !== 0
			&& $notifications !== true && $notifications !== false) {
			return [
				'status' => NOT_SUCCESSFUL,
				'error' => ERROR_INVALID_USER_INPUT
			];
		} else {
			return [
				'status' => SUCCESSFUL
			];
		}
	}
}
