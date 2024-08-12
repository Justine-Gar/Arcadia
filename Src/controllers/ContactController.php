<?php

namespace App\Controllers;

class ContactController extends Controllers
{
  public function index()
  {
      $this->render('contact', [
        'title' => 'Contact',
      ]);
  }
}