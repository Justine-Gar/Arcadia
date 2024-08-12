<?php

namespace App\Controllers;

use App\Controllers\Controllers;

class ContactController extends Controllers
{
    public function index()
    {
        $data = [
            'title' => 'Contact',
        ];

        return $this->render('contact', $data);
    }
}