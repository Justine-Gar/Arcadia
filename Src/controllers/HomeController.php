<?php
namespace Src\controllers;

use App\Controllers\Controllers;

class HomeController extends Controllers {
  
  public function index() 
  {
    $this->render('home', [
      'title' => 'Acceuil',
    ]);
  }
}