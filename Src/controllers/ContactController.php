<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class ContactController extends Controllers
{
  public function index()
  {
      $this->render('contact', [
        'title' => 'Contact',
      ]);
  }
}