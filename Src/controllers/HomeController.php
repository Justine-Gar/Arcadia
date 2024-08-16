<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class HomeController extends Controllers
{
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        $is_login = isset($_SESSION['user_id']);

        $data = [
            'title' => 'Accueil',
        ];

        return $this->render('home', $data);
    }
}