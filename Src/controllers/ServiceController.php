<?php

namespace App\Controllers;

class ServiceController extends Controllers
{
    public function index()
    {
        $this->render('services', [
            'title' => 'Services',
        ]);
    }
}
