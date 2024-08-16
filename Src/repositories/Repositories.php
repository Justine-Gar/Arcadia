<?php

namespace App\Repositories;

use lib\config\database;

abstract class Repositories
{
  protected function connexion() {

    $database = database::getInstance();
    //var_dump($database);
    return $database;
  }
}