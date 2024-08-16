<?php

namespace lib\core;

class Autoloader
{
  //je renomme les namepaces
  private static $namespaces = [
    'App\\Controllers\\' => '/src/controllers/',
    'App\\Models\\' => '/src/models/',
    'App\\Repositories\\' => '/src/repositories/',
    'App\\Utils\\' => '/src/utils/',
    'lib\\core\\' => '/lib/core/',
    'lib\\config\\' => '/lib/config/'
  ];

  public static function register()
  {
    spl_autoload_register(array(__CLASS__, 'autoload'));
  }

  public static function autoload($class)
  {
    error_log("Attempting to autoload class: " . $class);
    //Localisation d'un fichier 
    //j'extrait le namespace de la classe
    $prefix = substr($class, 0, strrpos($class, '\\') + 1);
    //j'extrait le nom de la class sans le namaspace
    $relative_class = substr($class, strlen($prefix));

    error_log("Prefix: " . $prefix . ", Relative class: " . $relative_class);

    //vérification si préfix correspond au noms définis
    if (isset(self::$namespaces[$prefix]))
    {
      $base_dir = __DIR__ . '/../../' . self::$namespaces[$prefix];
      //chemin complet ver fichier est construit en combinant le chemin de base et le mon de la classe
      $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

      error_log("Attempting to load file: " . $file);

      //si fichier existe = chargé
      if (file_exists($file)) {
        require_once $file;
        error_log("Successfully loaded file: " . $file);
        return true;
      } else {
        error_log("File does not exist: " . $file);
      }
    } else {
      error_log("No matching namespace found for prefix: " . $prefix);
    }
    return false;
  }
}
