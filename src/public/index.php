<?php
session_start();

// BASE PATH GLOBAL VARIABLE:
define('BASE_PATH', '/var/www/html/');

require_once BASE_PATH . 'app/init.php';

$app = new App;
