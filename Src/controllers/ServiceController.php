<?php
namespace App\Controllers;

use App\Controllers\Controllers;

class ServiceController extends Controllers
{
    public function index()
    {
        $data = [
            'title' => 'Services',
        ];
        
        return $this->render('services', $data);
    }
}
