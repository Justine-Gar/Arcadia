<?php

namespace App\Controllers;

use App\Utils\UserService;

class StaffController extends Controllers
{
  
  public function index()
  {
    $userService = new UserService;
    $user = $userService->getCurrentUser();

    //var_dump($user);

    $data = [
      'title' => 'Administration',
    ];

    return $this->render('employe', $data, 'dashboard');
  }



}
