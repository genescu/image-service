<?php declare(strict_types=1);
include __DIR__ . '/app.php';

use Images\App\ImageHandler;

$arguments = $_GET;
$imageHandler = new ImageHandler();
$imageHandler->handleRequest($arguments);