<?php
require_once __DIR__ . '/../core/Autoloader.php';
\lib\core\Autoloader::register();

// Initialiser le Logger
\lib\core\Logger::init(__DIR__ . '/../logs');

// Exécuter le build
\lib\core\JsBuild::buildAll();