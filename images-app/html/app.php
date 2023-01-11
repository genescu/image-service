<?php

define("APP_PATH", realpath(__DIR__));

$uploads = 'uploads';
$assets = 'assets';

define("APP_IMAGE_UPLOAD_PATH", APP_PATH . DIRECTORY_SEPARATOR . $uploads . DIRECTORY_SEPARATOR);
define("APP_ASSETS_PATH", DIRECTORY_SEPARATOR . $assets . DIRECTORY_SEPARATOR);
define("APP_ASSETS_BASE_PATH", APP_PATH . APP_ASSETS_PATH);

define("APP_SERVER_ASSETS_URL", 'http://localhost:12000');

include __DIR__ . "/../vendor/autoload.php";
