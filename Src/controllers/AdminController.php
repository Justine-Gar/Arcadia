<?php

namespace App\Controllers;

class AdminController extends Controllers
{
  public function index()
  {
    $data = [
      'title' => 'Administration',
    ];
    return $this->renderDashboard('admin', $data);
  }



}
