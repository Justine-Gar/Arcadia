<?php

namespace App\Controllers;
use App\Controllers\Controllers;

class FaqController extends Controllers
{
  public function index()
  {
    $this->render('faq', [
      'title' => 'Faq',
    ]);
  }
}
