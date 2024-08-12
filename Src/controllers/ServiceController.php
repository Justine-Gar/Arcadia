<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class ServiceController extends Controllers
{
    public function index()
    {
        $this->render('services', [
            'title' => 'Services',
        ]);
    }
}
