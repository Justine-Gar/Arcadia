<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class HomeController extends Controllers {
  
  public function index() 
  {
    $this->render('home', [
      'title' => 'Acceuil',
    ]);
  }
}