<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class HomeController extends Controllers
{
    public function index()
    {
        $data = [
            'title' => 'Accueil',
        ];

        return $this->render('home', $data);
    }
}