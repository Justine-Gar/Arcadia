<?php

namespace core;


function env($key, $default = null) {

  static $env;

  if (!$env) {
    $envPath = __DIR__ . '/../.env';
    if (!file_exists($envPath)) {
      throw new \Exception("Le fichier .env pas trouvé");
    }
    $env = parse_ini_file($envPath);
  }
  return isset($env[$key]) ? $env[$key] : $default;
}