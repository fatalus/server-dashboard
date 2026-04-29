<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Kernel;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$kernel = new Kernel();
$response = $kernel->handle($_SERVER);

echo $response;