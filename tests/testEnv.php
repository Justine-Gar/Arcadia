<?php


require_once __DIR__ . '/../core/helpers.php';

use function core\env;

echo 'DB_HOST: ' . env('DB_HOST') . PHP_EOL;
echo 'DB_NAME: ' . env('DB_NAME') . PHP_EOL;
echo 'DB_USER: ' . env('DB_USER') . PHP_EOL;
echo 'DB_PASS: ' . env('DB_PASS') . PHP_EOL;
echo 'APP_ENV: ' . env('APP_ENV') . PHP_EOL;