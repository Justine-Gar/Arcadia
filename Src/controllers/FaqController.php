<?php

namespace App\Controllers;

class FaqController extends Controllers
{
  public function index()
  {
    $this->render('faq', [
      'title' => 'Faq',
    ]);
  }
}
