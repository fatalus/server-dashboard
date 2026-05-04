<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Kernel;

$kernel = new Kernel();
$response = $kernel->handle($_SERVER);

echo $response;