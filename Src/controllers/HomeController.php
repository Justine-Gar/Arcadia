<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class HomeController extends Controllers {
  
  public function index() 
  {
    $data = [
      'title' => 'Acceuil',
    ];
    
    return $this->render('home', $data);
  }
}