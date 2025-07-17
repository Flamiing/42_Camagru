<?php

// Global Variables:
define('ERROR_LOGS', '/var/www/html/logs/error.log');
define('DATE_FORMAT', 'Y-m-d H:i:s');
define('SUCCESSFUL', 1);
define('NOT_SUCCESSFUL', 0);
define('CHECK', true);
define('NOT_CHECK', false);
define('NOTHING_TO_UPDATE', 'NOTHING_TO_UPDATE');
define('MODULE', '1');
define('NOT_MODULE', '0');
define('SENDER_EMAIL_ADDRESS', getenv('SENDER_EMAIL_ADDRESS'));

// Error Global Variables
define('ERROR_PAGE_NOT_FOUND', 'Page Not Found');
define('ERROR_CSRF_TOKEN', 'CSRF Token Mismatch');
define('ERROR_INTERNAL_SERVER', 'Internal Server Error');
define('ERROR_ACCOUNT_NOT_ACTIVE', 'Please validate your account before logging in');
define('ERROR_INVALID_USER_PASS', 'Invalid username or password');
define('ERROR_EMAIL_IN_USE', 'Email already in use');
define('ERROR_USERNAME_IN_USE', 'Username already in use');
define('ERROR_DATABASE', 'An error occurred. Please try again.');
define('ERROR_ACCOUNT_ACTIVE', 'Account already activated');
define('ERROR_INVALID_ACTIVATION_LINK', 'Invalid activation link');
define('ERROR_VALIDATE_ACC_TO_RESET', 'Please validate your account before reseting your password');
define('ERROR_ACC_NOT_FOUND', 'Account not found');
define('ERROR_RESET_TOKEN_EXPIRED', 'Reset password token expired');
define('ERROR_INVALID_RESET_TOKEN', 'Invalid reset password token');
define('ERROR_INVALID_CURRENT_PASS', 'Invalid current password. Please try again.');
define('ERROR_INVALID_USERNAME', 'Invalid username');
define('ERROR_INVALID_EMAIL', 'Invalid email');
define('ERROR_INVALID_PASSWORD', 'Invalid password');
define('ERROR_SAME_PASS_AS_CURRENT', 'You cannot use the same password you already have');
define('ERROR_INVALID_USER_INPUT', 'Invalid input');
define('ERROR_PROCESSING_IMAGE', 'Image could not be processed. Please try again.');
define('ERROR_WRONG_IMAGE_FORMAT', 'The image you uploaded is not valid.');
define('ERROR_METHOD_NOT_ALLOWED', 'Method Not Allowed');
define('ERROR_NO_CONTENT', 'No Content Available');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
define('APP_URL', $protocol . $domainName);

// Require all necessary files:
require_once BASE_PATH . 'app/Middlewares/cors.php';
require_once BASE_PATH . 'app/Core/App.php';

corsMiddleware();