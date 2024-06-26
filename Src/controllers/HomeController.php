<?php
namespace Src\controllers;

class HomeController {
  
  public function index() {

    $title = 'ArcadiaZOO';
    $content = 'Bienvenue sur Arcadia';

    require_once __DIR__ . '/../views/Home.php';
  }
}