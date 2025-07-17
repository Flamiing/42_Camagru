<?php

const METHODS = [
	'GET',
	'POST',
	'DELETE'
];

const HEADERS = [
	'X-API-KEY',
	'Origin',
	'X-Requested-With',
	'Content-Type',
	'Accept',
	'Access-Control-Request-Method'
];

const ALLOWED_ORIGINS = [
	'*'
];

function corsMiddleware() {
	header("Access-Control-Allow-Origin: " . implode (', ', ALLOWED_ORIGINS));
	header("Access-Control-Allow-Headers: " . implode(', ', HEADERS));
	header("Access-Control-Allow-Methods: " . implode(', ', METHODS));
	header("Allow: " . implode(', ', METHODS));
	$method = $_SERVER['REQUEST_METHOD'];
	if($method == "OPTIONS") {
		die();
	}
}